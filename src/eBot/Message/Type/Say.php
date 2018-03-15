<?php

/**
 * eBot - A bot for match management for CS:GO
 * @license     http://creativecommons.org/licenses/by/3.0/ Creative Commons 3.0
 * @author      Julien Pardons <julien.pardons@esport-tools.net>
 * @version     3.0
 * @date        21/10/2012
 */

namespace eBot\Message\Type;

use eBot\Message\Type;

class Say extends Type
{

    const SAY_TEAM = 1;
    const SAY = 0;

    public $userId = "";
    public $userName = "";
    public $userTeam = "";
    public $userSteamid = "";
    public $text;
    public $type;

    public function __construct()
    {
        $this->setName("Say");
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
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }
}
