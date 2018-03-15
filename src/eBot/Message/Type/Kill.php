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

class Kill extends Type
{
    /**
     * @var string
     */
    public $userId = "";

    /**
     * @var string
     */
    public $userName = "";

    /**
     * @var string
     */
    public $userTeam = "";

    /**
     * @var string
     */
    public $userSteamid = "";

    /**
     * @var int
     */
    public $killerPosX = 0;

    /**
     * @var int
     */
    public $killerPosY = 0;

    /**
     * @var int
     */
    public $killerPosZ = 0;

    /**
     * @var string
     */
    public $killedUserId = "";

    /**
     * @var string
     */
    public $killedUserName = "";

    /**
     * @var string
     */
    public $killedUserTeam = "";

    /**
     * @var string
     */
    public $killedUserSteamid = "";

    /**
     * @var int
     */
    public $killedPosX = 0;

    /**
     * @var int
     */
    public $killedPosY = 0;

    /**
     * @var int
     */
    public $killedPosZ = 0;

    /**
     * @var
     */
    public $weapon;

    /**
     * @var
     */
    public $headshot;

    public function __construct()
    {
        $this->setName("Kill");
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
    public function getKillerPosX()
    {
        return $this->killerPosX;
    }

    /**
     * @param int $killerPosX
     */
    public function setKillerPosX($killerPosX)
    {
        $this->killerPosX = $killerPosX;
    }

    /**
     * @return int
     */
    public function getKillerPosY()
    {
        return $this->killerPosY;
    }

    /**
     * @param int $killerPosY
     */
    public function setKillerPosY($killerPosY)
    {
        $this->killerPosY = $killerPosY;
    }

    /**
     * @return int
     */
    public function getKillerPosZ()
    {
        return $this->killerPosZ;
    }

    /**
     * @param int $killerPosZ
     */
    public function setKillerPosZ($killerPosZ)
    {
        $this->killerPosZ = $killerPosZ;
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

    /**
     * @return int
     */
    public function getKilledPosX()
    {
        return $this->killedPosX;
    }

    /**
     * @param int $killedPosX
     */
    public function setKilledPosX($killedPosX)
    {
        $this->killedPosX = $killedPosX;
    }

    /**
     * @return int
     */
    public function getKilledPosY()
    {
        return $this->killedPosY;
    }

    /**
     * @param int $killedPosY
     */
    public function setKilledPosY($killedPosY)
    {
        $this->killedPosY = $killedPosY;
    }

    /**
     * @return int
     */
    public function getKilledPosZ()
    {
        return $this->killedPosZ;
    }

    /**
     * @param int $killedPosZ
     */
    public function setKilledPosZ($killedPosZ)
    {
        $this->killedPosZ = $killedPosZ;
    }

    /**
     * @return mixed
     */
    public function getWeapon()
    {
        return $this->weapon;
    }

    /**
     * @param mixed $weapon
     */
    public function setWeapon($weapon)
    {
        $this->weapon = $weapon;
    }

    /**
     * @return mixed
     */
    public function getHeadshot()
    {
        return $this->headshot;
    }

    /**
     * @param mixed $headshot
     */
    public function setHeadshot($headshot)
    {
        $this->headshot = $headshot;
    }
}
