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

class Connected extends Type
{

    public $userId = "";
    public $userName = "";
    public $userSteamid = "";
    public $address = "";

    public function __construct()
    {
        $this->setName("Connected");
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
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }
}
