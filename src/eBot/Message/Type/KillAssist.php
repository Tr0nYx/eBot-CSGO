<?php

/**
 * eBot - A bot for match management for CS:GO
 *
 * @license http://creativecommons.org/licenses/by/3.0/ Creative Commons 3.0
 * @author  Julien Pardons <julien.pardons@esport-tools.net>
 * @version 3.0
 * @date    21/10/2012
 */

namespace eBot\Message\Type;

use eBot\Message\Type;

class KillAssist extends Type
{

    public $userId = "";
    public $userName = "";
    public $userTeam = "";
    public $userSteamid = "";
    public $killedUserId = "";
    public $killedUserName = "";
    public $killedUserTeam = "";
    public $killedUserSteamid = "";

    public function __construct()
    {
        $this->setName("KillAssist");
    }

    /**
     * @return string
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param string $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return string
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * @param string $userName
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;
    }

    /**
     * @return string
     */
    public function getUserTeam()
    {
        return $this->userTeam;
    }

    /**
     * @param string $userTeam
     */
    public function setUserTeam($userTeam)
    {
        $this->userTeam = $userTeam;
    }

    /**
     * @return string
     */
    public function getUserSteamid()
    {
        return $this->userSteamid;
    }

    /**
     * @param string $userSteamid
     */
    public function setUserSteamid($userSteamid)
    {
        $this->userSteamid = $userSteamid;
    }

    /**
     * @return string
     */
    public function getKilledUserId()
    {
        return $this->killedUserId;
    }

    /**
     * @param string $killedUserId
     */
    public function setKilledUserId($killedUserId)
    {
        $this->killedUserId = $killedUserId;
    }

    /**
     * @return string
     */
    public function getKilledUserName()
    {
        return $this->killedUserName;
    }

    /**
     * @param string $killedUserName
     */
    public function setKilledUserName($killedUserName)
    {
        $this->killedUserName = $killedUserName;
    }

    /**
     * @return string
     */
    public function getKilledUserTeam()
    {
        return $this->killedUserTeam;
    }

    /**
     * @param string $killedUserTeam
     */
    public function setKilledUserTeam($killedUserTeam)
    {
        $this->killedUserTeam = $killedUserTeam;
    }

    /**
     * @return string
     */
    public function getKilledUserSteamid()
    {
        return $this->killedUserSteamid;
    }

    /**
     * @param string $killedUserSteamid
     */
    public function setKilledUserSteamid($killedUserSteamid)
    {
        $this->killedUserSteamid = $killedUserSteamid;
    }
}
