<?php

/**
 * eBot - A bot for match management for CS:GO
 *
 * @license http://creativecommons.org/licenses/by/3.0/ Creative Commons 3.0
 * @author  Julien Pardons <julien.pardons@esport-tools.net>
 * @version 3.0
 * @date    21/10/2012
 */

namespace eBot\Application;

use eTools\Task\TaskManager;
use eTools\Utils\Encryption;
use eTools\Utils\Logger;
use eTools\Application\AbstractApplication;
use eTools\Socket\UDPSocket as Socket;
use eBot\Manager\MessageManager;
use eBot\Manager\PluginsManager;
use eBot\Manager\MatchManagerClient;
use eBot\Config\Config;

class ApplicationClient extends AbstractApplication
{

    const VERSION = "3.0";

    private $socket = null;
    private $websocket = null;
    private $clientsConnected = false;
    private $portMain = 0;

    public function getPortMain()
    {
        return $this->portMain;
    }

    public function run()
    {
        global $argv;
        // Loading Logger instance
        Logger::getInstance();
        Logger::getInstance()->setName("#".$argv[1]);
        Logger::log($this->getName());

        // Loading eBot configuration
        Logger::log("Loading config");
        Config::getInstance()->printConfig();

        // Initializing database
        $this->initDatabase();

        // Registring components
        Logger::log("Registering MatchManagerClient");
        MatchManagerClient::getInstance();

        Logger::log("Registering Messages");
        MessageManager::createFromConfigFile();

        Logger::log("Registering PluginsManager");
        PluginsManager::getInstance();

        try {
            Application::getInstance()->websocket['match'] = new \WebSocket(
                "ws://".Config::getInstance()->getBotIp().":".(Config::getInstance(
                )->getBotPort())."/match"
            );
            Application::getInstance()->websocket['match']->open();
            Application::getInstance()->websocket['rcon'] = new \WebSocket(
                "ws://".Config::getInstance()->getBotIp().":".(Config::getInstance(
                )->getBotPort())."/rcon"
            );
            Application::getInstance()->websocket['rcon']->open();
            Application::getInstance()->websocket['logger'] = new \WebSocket(
                "ws://".Config::getInstance()->getBotIp().":".(Config::getInstance(
                )->getBotPort())."/logger"
            );
            Application::getInstance()->websocket['logger']->open();
            Application::getInstance()->websocket['livemap'] = new \WebSocket(
                "ws://".Config::getInstance()->getBotIp().":".(Config::getInstance(
                )->getBotPort())."/livemap"
            );
            Application::getInstance()->websocket['livemap']->open();
            Application::getInstance()->websocket['aliveCheck'] = new \WebSocket(
                "ws://".Config::getInstance()->getBotIp().":".(Config::getInstance(
                )->getBotPort())."/alive"
            );
            Application::getInstance()->websocket['aliveCheck']->open();
        } catch (\Exception $ex) {
            Logger::error("Unable to create Websocket.");
            die();
        }

        // Starting application
        Logger::log("Starting eBot Application");
        $this->portMain = Config::getInstance()->getBotPort();

        Config::getInstance()->setBotPort(Config::getInstance()->getBotPort() + $argv[1]);
        Logger::log("New port : ".Config::getInstance()->getBotPort());

        try {
            $this->socket = new Socket(Config::getInstance()->getBotIp(), Config::getInstance()->getBotPort());
        } catch (\Exception $ex) {
            Logger::error("Unable to bind socket");
            die();
        }

        PluginsManager::getInstance()->startAll();

        $time = time();
        while (true) {
            $data = $this->socket->recvfrom($ip);
            if ($data) {
                if (!preg_match("/L+\s+\d+\/\d+\/\d+/", $data)) {
                    if ($data == '__true__') {
                        $this->clientsConnected = true;
                    } elseif ($data == '__false__') {
                        $this->clientsConnected = false;
                    } elseif ($data == '__aliveCheck__') {
                        Application::getInstance()->websocket['aliveCheck']->sendData('__isAlive__');
                    } elseif (preg_match("!^addMatch (?<id>\d+)$!", $data, $preg)) {
                        MatchManagerClient::getInstance()->engageMatch($preg['id']);
                    } else {
                        $data = json_decode($data, true);
                        $authkey = MatchManagerClient::getInstance()->getAuthkey($data[1]);
                        $text = Encryption::decrypt($data[0], $authkey, 256);
                        if ($text) {
                            if (preg_match("!^(?<id>\d+) stopNoRs (?<ip>\d+\.\d+\.\d+\.\d+\:\d+)$!", $text, $preg)) {
                                $match = MatchManagerClient::getInstance()->getMatch($preg["ip"]);
                                if ($match) {
                                    $reply = $match->adminStopNoRs();
                                    if ($reply) {
                                        $send = json_encode(
                                            array('message' => 'button', 'content' => 'stop', 'id' => $preg["id"])
                                        );
                                        Application::getInstance()->websocket['match']->sendData($send);
                                    }
                                } else {
                                    Logger::error($preg["ip"]." is not managed !");
                                }
                            } elseif (preg_match("!^(?<id>\d+) stop (?<ip>\d+\.\d+\.\d+\.\d+\:\d+)$!", $text, $preg)) {
                                $match = MatchManagerClient::getInstance()->getMatch($preg["ip"]);
                                if ($match) {
                                    $reply = $match->adminStop();
                                    if ($reply) {
                                        $send = json_encode(
                                            array('message' => 'button', 'content' => 'stop', 'id' => $preg["id"])
                                        );
                                        Application::getInstance()->websocket['match']->sendData($send);
                                    }
                                } else {
                                    Logger::error($preg["ip"]." is not managed !");
                                }
                            } elseif (preg_match(
                                "!^(?<id>\d+) executeCommand (?<ip>\d+\.\d+\.\d+\.\d+\:\d+) (?<command>.*)$!",
                                $text,
                                $preg
                            )
                            ) {
                                $match = MatchManagerClient::getInstance()->getMatch($preg["ip"]);
                                if ($match) {
                                    $reply = $match->adminExecuteCommand($preg["command"]);
                                    if ($reply) {
                                        $send = json_encode(array('id' => $preg["id"], 'content' => $reply));
                                        Application::getInstance()->websocket['rcon']->sendData($send);
                                    }
                                } else {
                                    Logger::error($preg["ip"]." is not managed !");
                                }
                            } elseif (preg_match(
                                "!^(?<id>\d+) passknife (?<ip>\d+\.\d+\.\d+\.\d+\:\d+)$!",
                                $text,
                                $preg
                            )
                            ) {
                                $match = MatchManagerClient::getInstance()->getMatch($preg["ip"]);
                                if ($match) {
                                    $reply = $match->adminPassKnife();
                                    if ($reply) {
                                        $send = json_encode(
                                            array(
                                                'message' => 'button',
                                                'content' => $match->getStatus(),
                                                'id' => $preg["id"],
                                            )
                                        );
                                        Application::getInstance()->websocket['match']->sendData($send);
                                    }
                                } else {
                                    Logger::error($preg["ip"]." is not managed !");
                                }
                            } elseif (preg_match(
                                "!^(?<id>\d+) forceknife (?<ip>\d+\.\d+\.\d+\.\d+\:\d+)$!",
                                $text,
                                $preg
                            )
                            ) {
                                $match = MatchManagerClient::getInstance()->getMatch($preg["ip"]);
                                if ($match) {
                                    $reply = $match->adminForceKnife();
                                    if ($reply) {
                                        $send = json_encode(
                                            array(
                                                'message' => 'button',
                                                'content' => $match->getStatus(),
                                                'id' => $preg["id"],
                                            )
                                        );
                                        Application::getInstance()->websocket['match']->sendData($send);
                                    }
                                } else {
                                    Logger::error($preg["ip"]." is not managed !");
                                }
                            } elseif (preg_match(
                                "!^(?<id>\d+) forceknifeend (?<ip>\d+\.\d+\.\d+\.\d+\:\d+)$!",
                                $text,
                                $preg
                            )
                            ) {
                                $match = MatchManagerClient::getInstance()->getMatch($preg["ip"]);
                                if ($match) {
                                    $reply = $match->adminForceKnifeEnd();
                                    if ($reply) {
                                        $send = json_encode(
                                            array(
                                                'message' => 'button',
                                                'content' => $match->getStatus(),
                                                'id' => $preg["id"],
                                            )
                                        );
                                        Application::getInstance()->websocket['match']->sendData($send);
                                    }
                                } else {
                                    Logger::error($preg["ip"]." is not managed !");
                                }
                            } elseif (preg_match(
                                "!^(?<id>\d+) forcestart (?<ip>\d+\.\d+\.\d+\.\d+\:\d+)$!",
                                $text,
                                $preg
                            )
                            ) {
                                $match = MatchManagerClient::getInstance()->getMatch($preg["ip"]);
                                if ($match) {
                                    $reply = $match->adminForceStart();
                                    if ($reply) {
                                        $send = json_encode(
                                            array(
                                                'message' => 'button',
                                                'content' => $match->getStatus(),
                                                'id' => $preg["id"],
                                            )
                                        );
                                        Application::getInstance()->websocket['match']->sendData($send);
                                    }
                                } else {
                                    Logger::error($preg["ip"]." is not managed !");
                                }
                            } elseif (preg_match(
                                "!^(?<id>\d+) stopback (?<ip>\d+\.\d+\.\d+\.\d+\:\d+)$!",
                                $text,
                                $preg
                            )
                            ) {
                                $match = MatchManagerClient::getInstance()->getMatch($preg["ip"]);
                                if ($match) {
                                    $reply = $match->adminStopBack();
                                    if ($reply) {
                                        $send = json_encode(
                                            array(
                                                'message' => 'button',
                                                'content' => $match->getStatus(),
                                                'id' => $preg["id"],
                                            )
                                        );
                                        Application::getInstance()->websocket['match']->sendData($send);
                                    }
                                } else {
                                    Logger::error($preg["ip"]." is not managed !");
                                }
                            } elseif (preg_match(
                                "!^(?<id>\d+) pauseunpause (?<ip>\d+\.\d+\.\d+\.\d+\:\d+)$!",
                                $text,
                                $preg
                            )
                            ) {
                                $match = MatchManagerClient::getInstance()->getMatch($preg["ip"]);
                                if ($match) {
                                    $reply = $match->adminPauseUnpause();
                                    if ($reply) {
                                        $send = json_encode(
                                            array(
                                                'message' => 'button',
                                                'content' => $match->getStatus(),
                                                'id' => $preg["id"],
                                            )
                                        );
                                        Application::getInstance()->websocket['match']->sendData($send);
                                    }
                                } else {
                                    Logger::error($preg["ip"]." is not managed !");
                                }
                            } elseif (preg_match(
                                "!^(?<id>\d+) fixsides (?<ip>\d+\.\d+\.\d+\.\d+\:\d+)$!",
                                $text,
                                $preg
                            )
                            ) {
                                $match = MatchManagerClient::getInstance()->getMatch($preg["ip"]);
                                if ($match) {
                                    $reply = $match->adminFixSides();
                                    if ($reply) {
                                        $send = json_encode(
                                            array(
                                                'message' => 'button',
                                                'content' => $match->getStatus(),
                                                'id' => $preg["id"],
                                            )
                                        );
                                        Application::getInstance()->websocket['match']->sendData($send);
                                    }
                                } else {
                                    Logger::error($preg["ip"]." is not managed !");
                                }
                            } elseif (preg_match(
                                "!^(?<id>\d+) streamerready (?<ip>\d+\.\d+\.\d+\.\d+\:\d+)$!",
                                $text,
                                $preg
                            )
                            ) {
                                $match = MatchManagerClient::getInstance()->getMatch($preg["ip"]);
                                if ($match) {
                                    $reply = $match->adminStreamerReady();
                                    if ($reply) {
                                        $send = json_encode(
                                            array(
                                                'message' => 'button',
                                                'content' => $match->getStatus(),
                                                'id' => $preg["id"],
                                            )
                                        );
                                        Application::getInstance()->websocket['match']->sendData($send);
                                    }
                                } else {
                                    Logger::error($preg["ip"]." is not managed !");
                                }
                            } elseif (preg_match(
                                "!^(?<id>\d+) goBackRounds (?<ip>\d+\.\d+\.\d+\.\d+\:\d+) (?<round>\d+)$!",
                                $text,
                                $preg
                            )
                            ) {
                                $match = MatchManagerClient::getInstance()->getMatch($preg["ip"]);
                                if ($match) {
                                    $reply = $match->adminGoBackRounds($preg['round']);
                                    if ($reply) {
                                        $send = json_encode(
                                            array(
                                                'message' => 'button',
                                                'content' => $match->getStatus(),
                                                'id' => $preg["id"],
                                            )
                                        );
                                        Application::getInstance()->websocket['match']->sendData($send);
                                    }
                                } else {
                                    Logger::error($preg["ip"]." is not managed !");
                                }
                            } else {
                                Logger::error($text." not managed");
                            }
                        }
                    }
                } else {
                    $line = substr($data, 7);

                    if (MatchManagerClient::getInstance()->getMatch($ip)) {
                        file_put_contents(APP_ROOT."/logs/$ip", $line, FILE_APPEND);
                        $line = trim(substr($line, 23));
                        MatchManagerClient::getInstance()->getMatch($ip)->processMessage($line);
                        if ($this->clientsConnected) {
                            $line = substr($data, 7, strlen($data) - 8);
                            file_put_contents(
                                Logger::getInstance()->getLogPathAdmin(
                                )."/logs_".MatchManagerClient::getInstance()->getMatch($ip)->getMatchId(),
                                $line,
                                FILE_APPEND
                            );
                            $send = json_encode(
                                array(
                                    'id' => MatchManagerClient::getInstance()->getMatch(
                                        $ip
                                    )->getMatchId(),
                                    'content' => $line,
                                )
                            );
                            Application::getInstance()->websocket['logger']->sendData($send);
                        }
                    }
                }
            }
            if ($time + 5 < time()) {
                $time = time();
                Application::getInstance()->websocket['match']->send(json_encode(array("message" => "ping")));
                Application::getInstance()->websocket['logger']->send(json_encode(array("message" => "ping")));
                Application::getInstance()->websocket['rcon']->send(json_encode(array("message" => "ping")));
                Application::getInstance()->websocket['livemap']->send(json_encode(array("message" => "ping")));
                Application::getInstance()->websocket['aliveCheck']->send(json_encode(array("message" => "ping")));
            }

            MatchManagerClient::getInstance()->sendPub();
            TaskManager::getInstance()->runTask();
        }
    }

    private function initDatabase()
    {
        $conn = mysqli_connect(
            Config::getInstance()->getMysqlIp(),
            Config::getInstance()->getMysqlUser(),
            Config::getInstance()->getMysqlPass()
        );
        if (!$conn) {
            Logger::error(
                "Can't login into database ".Config::getInstance()->getMysqlUser()."@".Config::getInstance(
                )->getMysqlIp()
            );
            exit(1);
        }

        if (mysqli_select_db(Config::getInstance()->getMysqlBase(), $conn)) {
            Logger::error("Can't select database ".Config::getInstance()->getMysqlBase());
            exit(1);
        }
    }

    public function getName()
    {
        return "eBot CS:Go version ".$this->getVersion();
    }

    public function getVersion()
    {
        return self::VERSION;
    }

    public function getSocket()
    {
        return $this->socket;
    }

    public function getWebSocket($room)
    {
        return Application::getInstance()->websocket[$room];
    }
}
