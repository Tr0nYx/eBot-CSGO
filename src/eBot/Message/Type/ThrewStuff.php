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

class ThrewStuff extends Type
{

    public $userId = "";
    public $userName = "";
    public $userTeam = "";
    public $userSteamid = "";
    public $posX = 0;
    public $posY = 0;
    public $posZ = 0;
    public $stuff = 0;

    public function __construct()
    {
        $this->setName("ThrewStuff");
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
     * @return int
     */
    public function getPosX()
    {
        return $this->posX;
    }

    /**
     * @param int $posX
     */
    public function setPosX($posX)
    {
        $this->posX = $posX;
    }

    /**
     * @return int
     */
    public function getPosY()
    {
        return $this->posY;
    }

    /**
     * @param int $posY
     */
    public function setPosY($posY)
    {
        $this->posY = $posY;
    }

    /**
     * @return int
     */
    public function getPosZ()
    {
        return $this->posZ;
    }

    /**
     * @param int $posZ
     */
    public function setPosZ($posZ)
    {
        $this->posZ = $posZ;
    }

    /**
     * @return int
     */
    public function getStuff()
    {
        return $this->stuff;
    }

    /**
     * @param int $stuff
     */
    public function setStuff($stuff)
    {
        $this->stuff = $stuff;
    }
}
