<?php

/**
 * eBot - A bot for match management for CS:GO
 * @license     http://creativecommons.org/licenses/by/3.0/ Creative Commons 3.0
 * @author      Julien Pardons <julien.pardons@esport-tools.net>
 * @version     3.0
 * @date        21/10/2012
 */

namespace eBot\Application;

use eTools\Task\TaskManager;
use eTools\Utils\Encryption;
use eTools\Utils\Logger;
use eTools\Application\AbstractApplication;
use eTools\Socket\UDPSocket as Socket;
use eBot\Manager\MessageManager;
use eBot\Manager\PluginsManager;
use eBot\Manager\MatchManagerServer;
use eBot\Config\Config;

class ApplicationServer extends AbstractApplication
{

    const VERSION = "3.0";

    private $socket = null;
    private $websocket = null;
    private $clientsConnected = false;
    public $instance = array();

    public function run()
    {
        // Loading Logger instance
        Logger::getInstance();
        Logger::getInstance()->setName("#0");
        Logger::log($this->getName());

        // Loading eBot configuration
        Logger::log("Loading config");
        Config::getInstance()->printConfig();

        // Initializing database
        $this->initDatabase();

        // Registring components
        Logger::log("Registering MatchManagerServer");
        MatchManagerServer::getInstance();

        Logger::log("Registering Messages");
        MessageManager::createFromConfigFile();

        Logger::log("Registering PluginsManager");
        PluginsManager::getInstance();

        Logger::log("Spawning instance");
        $config = parse_ini_file(APP_ROOT.DIRECTORY_SEPARATOR."config".DIRECTORY_SEPARATOR."config.server.ini");
        $instance = 1;
        if (is_numeric($config['NUMBER'])) {
            $instance = $config['NUMBER'];
        }

        for ($i = 1; $i <= $instance; $i++) {
            $descriptorspec = array(
                0 => STDIN,
                1 => STDOUT,
                2 => STDOUT,
            );
            $process = proc_open(
                PHP_BINDIR.'/php '.EBOT_DIRECTORY.'/bootstrap_client.php '.$i,
                $descriptorspec,
                $pipes
            );
            $status = proc_get_status($process);
            $this->instance[] = $process;
            Logger::log("Spawned instance ".$status['pid']);
        }

        // Starting application
        Logger::log("Starting eBot Application");

        try {
            $this->socket = new Socket(Config::getInstance()->getBotIp(), Config::getInstance()->getBotPort());
        } catch (\Exception $ex) {
            Logger::error("Unable to bind socket");
            die();
        }

        try {
            $this->websocket['match'] = new \WebSocket(
                "ws://".Config::getInstance()->getBotIp().":".(Config::getInstance()->getBotPort())."/match"
            );
            $this->websocket['match']->open();
            $this->websocket['rcon'] = new \WebSocket(
                "ws://".Config::getInstance()->getBotIp().":".(Config::getInstance()->getBotPort())."/rcon"
            );
            $this->websocket['rcon']->open();
            $this->websocket['logger'] = new \WebSocket(
                "ws://".Config::getInstance()->getBotIp().":".(Config::getInstance()->getBotPort())."/logger"
            );
            $this->websocket['logger']->open();
            $this->websocket['livemap'] = new \WebSocket(
                "ws://".Config::getInstance()->getBotIp().":".(Config::getInstance()->getBotPort())."/livemap"
            );
            $this->websocket['livemap']->open();
            $this->websocket['aliveCheck'] = new \WebSocket(
                "ws://".Config::getInstance()->getBotIp().":".(Config::getInstance()->getBotPort())."/alive"
            );
            $this->websocket['aliveCheck']->open();
        } catch (\Exception $ex) {
            Logger::error("Unable to create Websocket.");
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
                        for ($i = 1; $i <= count($this->instance); $i++) {
                            $this->socket->sendto(
                                $data,
                                Config::getInstance()->getBotIp(),
                                Config::getInstance()->getBotPort() + $i
                            );
                        }
                    } elseif ($data == '__false__') {
                        $this->clientsConnected = false;
                        for ($i = 1; $i <= count($this->instance); $i++) {
                            $this->socket->sendto(
                                $data,
                                Config::getInstance()->getBotIp(),
                                Config::getInstance()->getBotPort() + $i
                            );
                        }
                    } elseif ($data == '__aliveCheck__') {
                        $this->websocket['aliveCheck']->sendData('__isAlive__');
                        for ($i = 1; $i <= count($this->instance); $i++) {
                            $this->socket->sendto(
                                $data,
                                Config::getInstance()->getBotIp(),
                                Config::getInstance()->getBotPort() + $i
                            );
                        }
                    } elseif (preg_match("!^removeMatch (?<id>\d+)$!", $data, $preg)) {
                        Logger::log("Removing ".$preg['id']);
                        MatchManagerServer::getInstance()->removeMatch($preg['id']);
                    } else {
                        $origData = $data;
                        $data = json_decode($data, true);
                        $authkey = MatchManagerServer::getInstance()->getAuthkey($data[1]);
                        $text = Encryption::decrypt($data[0], $authkey, 256);
                        if ($text) {
                            if (preg_match("!^(?<id>\d+) stopNoRs (?<ip>\d+\.\d+\.\d+\.\d+\:\d+)$!", $text, $preg)) {
                                $match = MatchManagerServer::getInstance()->getMatch($preg["ip"]);
                                if ($match) {
                                    $this->socket->sendto(
                                        $origData,
                                        Config::getInstance()->getBotIp(),
                                        Config::getInstance()->getBotPort() + $match['i']
                                    );
                                } else {
                                    Logger::error($preg["ip"]." is not managed !");
                                }
                            } elseif (preg_match("!^(?<id>\d+) stop (?<ip>\d+\.\d+\.\d+\.\d+\:\d+)$!", $text, $preg)) {
                                $match = MatchManagerServer::getInstance()->getMatch($preg["ip"]);
                                if ($match) {
                                    $this->socket->sendto(
                                        $origData,
                                        Config::getInstance()->getBotIp(),
                                        Config::getInstance()->getBotPort() + $match['i']
                                    );
                                } else {
                                    Logger::error($preg["ip"]." is not managed !");
                                }
                            } elseif (preg_match(
                                "!^(?<id>\d+) executeCommand (?<ip>\d+\.\d+\.\d+\.\d+\:\d+) (?<command>.*)$!",
                                $text,
                                $preg
                            )) {
                                $match = MatchManagerServer::getInstance()->getMatch($preg["ip"]);
                                if ($match) {
                                    $this->socket->sendto(
                                        $origData,
                                        Config::getInstance()->getBotIp(),
                                        Config::getInstance()->getBotPort() + $match['i']
                                    );
                                } else {
                                    Logger::error($preg["ip"]." is not managed !");
                                }
                            } elseif (preg_match(
                                "!^(?<id>\d+) passknife (?<ip>\d+\.\d+\.\d+\.\d+\:\d+)$!",
                                $text,
                                $preg
                            )) {
                                $match = MatchManagerServer::getInstance()->getMatch($preg["ip"]);
                                if ($match) {
                                    $this->socket->sendto(
                                        $origData,
                                        Config::getInstance()->getBotIp(),
                                        Config::getInstance()->getBotPort() + $match['i']
                                    );
                                } else {
                                    Logger::error($preg["ip"]." is not managed !");
                                }
                            } elseif (preg_match(
                                "!^(?<id>\d+) forceknife (?<ip>\d+\.\d+\.\d+\.\d+\:\d+)$!",
                                $text,
                                $preg
                            )) {
                                $match = MatchManagerServer::getInstance()->getMatch($preg["ip"]);
                                if ($match) {
                                    $this->socket->sendto(
                                        $origData,
                                        Config::getInstance()->getBotIp(),
                                        Config::getInstance()->getBotPort() + $match['i']
                                    );
                                } else {
                                    Logger::error($preg["ip"]." is not managed !");
                                }
                            } elseif (preg_match(
                                "!^(?<id>\d+) forceknifeend (?<ip>\d+\.\d+\.\d+\.\d+\:\d+)$!",
                                $text,
                                $preg
                            )) {
                                $match = MatchManagerServer::getInstance()->getMatch($preg["ip"]);
                                if ($match) {
                                    $this->socket->sendto(
                                        $origData,
                                        Config::getInstance()->getBotIp(),
                                        Config::getInstance()->getBotPort() + $match['i']
                                    );
                                } else {
                                    Logger::error($preg["ip"]." is not managed !");
                                }
                            } elseif (preg_match(
                                "!^(?<id>\d+) forcestart (?<ip>\d+\.\d+\.\d+\.\d+\:\d+)$!",
                                $text,
                                $preg
                            )) {
                                $match = MatchManagerServer::getInstance()->getMatch($preg["ip"]);
                                if ($match) {
                                    $this->socket->sendto(
                                        $origData,
                                        Config::getInstance()->getBotIp(),
                                        Config::getInstance()->getBotPort() + $match['i']
                                    );
                                } else {
                                    Logger::error($preg["ip"]." is not managed !");
                                }
                            } elseif (preg_match(
                                "!^(?<id>\d+) stopback (?<ip>\d+\.\d+\.\d+\.\d+\:\d+)$!",
                                $text,
                                $preg
                            )) {
                                $match = MatchManagerServer::getInstance()->getMatch($preg["ip"]);
                                if ($match) {
                                    $this->socket->sendto(
                                        $origData,
                                        Config::getInstance()->getBotIp(),
                                        Config::getInstance()->getBotPort() + $match['i']
                                    );
                                } else {
                                    Logger::error($preg["ip"]." is not managed !");
                                }
                            } elseif (preg_match(
                                "!^(?<id>\d+) pauseunpause (?<ip>\d+\.\d+\.\d+\.\d+\:\d+)$!",
                                $text,
                                $preg
                            )) {
                                $match = MatchManagerServer::getInstance()->getMatch($preg["ip"]);
                                if ($match) {
                                    $this->socket->sendto(
                                        $origData,
                                        Config::getInstance()->getBotIp(),
                                        Config::getInstance()->getBotPort() + $match['i']
                                    );
                                } else {
                                    Logger::error($preg["ip"]." is not managed !");
                                }
                            } elseif (preg_match(
                                "!^(?<id>\d+) fixsides (?<ip>\d+\.\d+\.\d+\.\d+\:\d+)$!",
                                $text,
                                $preg
                            )) {
                                $match = MatchManagerServer::getInstance()->getMatch($preg["ip"]);
                                if ($match) {
                                    $this->socket->sendto(
                                        $origData,
                                        Config::getInstance()->getBotIp(),
                                        Config::getInstance()->getBotPort() + $match['i']
                                    );
                                } else {
                                    Logger::error($preg["ip"]." is not managed !");
                                }
                            } elseif (preg_match(
                                "!^(?<id>\d+) streamerready (?<ip>\d+\.\d+\.\d+\.\d+\:\d+)$!",
                                $text,
                                $preg
                            )) {
                                $match = MatchManagerServer::getInstance()->getMatch($preg["ip"]);
                                if ($match) {
                                    $this->socket->sendto(
                                        $origData,
                                        Config::getInstance()->getBotIp(),
                                        Config::getInstance()->getBotPort() + $match['i']
                                    );
                                } else {
                                    Logger::error($preg["ip"]." is not managed !");
                                }
                            } elseif (preg_match(
                                "!^(?<id>\d+) goBackRounds (?<ip>\d+\.\d+\.\d+\.\d+\:\d+) (?<round>\d+)$!",
                                $text,
                                $preg
                            )) {
                                $match = MatchManagerServer::getInstance()->getMatch($preg["ip"]);
                                if ($match) {
                                    $this->socket->sendto(
                                        $origData,
                                        Config::getInstance()->getBotIp(),
                                        Config::getInstance()->getBotPort() + $match['i']
                                    );
                                } else {
                                    Logger::error($preg["ip"]." is not managed !");
                                }
                            } else {
                                Logger::error($text." not managed");
                            }
                        }
                    }
                }
            }
            if ($time + 5 < time()) {
                $time = time();
                $this->websocket['match']->send(json_encode(array("message" => "ping")));
                $this->websocket['logger']->send(json_encode(array("message" => "ping")));
                $this->websocket['rcon']->send(json_encode(array("message" => "ping")));
                $this->websocket['livemap']->send(json_encode(array("message" => "ping")));
                $this->websocket['aliveCheck']->send(json_encode(array("message" => "ping")));
            }

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
        return $this->websocket[$room];
    }
}
