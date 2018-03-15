<?php

namespace eBot\Message\Type;

use eBot\Message\Type;

class Attacked extends Type
{
    public $attackerName = "";
    public $attackerUserId = "";
    public $attackerSteamId = "";
    public $attackerTeam = "";
    public $attackerPosX = 0;
    public $attackerPosY = 0;
    public $attackerPosZ = 0;
    public $attackerWeapon = "";
    public $attackerDamage = 0;
    public $attackerDamageArmor = 0;
    public $attackerHitGroup = "";

    public $victimName = "";
    public $victimUserId = "";
    public $victimSteamId = "";
    public $victimTeam = "";
    public $victimPosX = 0;
    public $victimPosY = 0;
    public $victimPosZ = 0;
    public $victimHealth = 0;
    public $victimArmor = 0;

    public function __construct()
    {
        $this->setName("Attacked");
    }

    /**
     * @return string
     */
    public function getAttackerName()
    {
        return $this->attackerName;
    }

    /**
     * @param string $attackerName
     */
    public function setAttackerName($attackerName)
    {
        $this->attackerName = $attackerName;
    }

    /**
     * @return string
     */
    public function getAttackerUserId()
    {
        return $this->attackerUserId;
    }

    /**
     * @param string $attackerUserId
     */
    public function setAttackerUserId($attackerUserId)
    {
        $this->attackerUserId = $attackerUserId;
    }

    /**
     * @return string
     */
    public function getAttackerSteamId()
    {
        return $this->attackerSteamId;
    }

    /**
     * @param string $attackerSteamId
     */
    public function setAttackerSteamId($attackerSteamId)
    {
        $this->attackerSteamId = $attackerSteamId;
    }

    /**
     * @return string
     */
    public function getAttackerTeam()
    {
        return $this->attackerTeam;
    }

    /**
     * @param string $attackerTeam
     */
    public function setAttackerTeam($attackerTeam)
    {
        $this->attackerTeam = $attackerTeam;
    }

    /**
     * @return int
     */
    public function getAttackerPosX()
    {
        return $this->attackerPosX;
    }

    /**
     * @param int $attackerPosX
     */
    public function setAttackerPosX($attackerPosX)
    {
        $this->attackerPosX = $attackerPosX;
    }

    /**
     * @return int
     */
    public function getAttackerPosY()
    {
        return $this->attackerPosY;
    }

    /**
     * @param int $attackerPosY
     */
    public function setAttackerPosY($attackerPosY)
    {
        $this->attackerPosY = $attackerPosY;
    }

    /**
     * @return int
     */
    public function getAttackerPosZ()
    {
        return $this->attackerPosZ;
    }

    /**
     * @param int $attackerPosZ
     */
    public function setAttackerPosZ($attackerPosZ)
    {
        $this->attackerPosZ = $attackerPosZ;
    }

    /**
     * @return string
     */
    public function getAttackerWeapon()
    {
        return $this->attackerWeapon;
    }

    /**
     * @param string $attackerWeapon
     */
    public function setAttackerWeapon($attackerWeapon)
    {
        $this->attackerWeapon = $attackerWeapon;
    }

    /**
     * @return int
     */
    public function getAttackerDamage()
    {
        return $this->attackerDamage;
    }

    /**
     * @param int $attackerDamage
     */
    public function setAttackerDamage($attackerDamage)
    {
        $this->attackerDamage = $attackerDamage;
    }

    /**
     * @return int
     */
    public function getAttackerDamageArmor()
    {
        return $this->attackerDamageArmor;
    }

    /**
     * @param int $attackerDamageArmor
     */
    public function setAttackerDamageArmor($attackerDamageArmor)
    {
        $this->attackerDamageArmor = $attackerDamageArmor;
    }

    /**
     * @return string
     */
    public function getAttackerHitGroup()
    {
        return $this->attackerHitGroup;
    }

    /**
     * @param string $attackerHitGroup
     */
    public function setAttackerHitGroup($attackerHitGroup)
    {
        $this->attackerHitGroup = $attackerHitGroup;
    }

    /**
     * @return string
     */
    public function getVictimName()
    {
        return $this->victimName;
    }

    /**
     * @param string $victimName
     */
    public function setVictimName($victimName)
    {
        $this->victimName = $victimName;
    }

    /**
     * @return string
     */
    public function getVictimUserId()
    {
        return $this->victimUserId;
    }

    /**
     * @param string $victimUserId
     */
    public function setVictimUserId($victimUserId)
    {
        $this->victimUserId = $victimUserId;
    }

    /**
     * @return string
     */
    public function getVictimSteamId()
    {
        return $this->victimSteamId;
    }

    /**
     * @param string $victimSteamId
     */
    public function setVictimSteamId($victimSteamId)
    {
        $this->victimSteamId = $victimSteamId;
    }

    /**
     * @return string
     */
    public function getVictimTeam()
    {
        return $this->victimTeam;
    }

    /**
     * @param string $victimTeam
     */
    public function setVictimTeam($victimTeam)
    {
        $this->victimTeam = $victimTeam;
    }

    /**
     * @return int
     */
    public function getVictimPosX()
    {
        return $this->victimPosX;
    }

    /**
     * @param int $victimPosX
     */
    public function setVictimPosX($victimPosX)
    {
        $this->victimPosX = $victimPosX;
    }

    /**
     * @return int
     */
    public function getVictimPosY()
    {
        return $this->victimPosY;
    }

    /**
     * @param int $victimPosY
     */
    public function setVictimPosY($victimPosY)
    {
        $this->victimPosY = $victimPosY;
    }

    /**
     * @return int
     */
    public function getVictimPosZ()
    {
        return $this->victimPosZ;
    }

    /**
     * @param int $victimPosZ
     */
    public function setVictimPosZ($victimPosZ)
    {
        $this->victimPosZ = $victimPosZ;
    }

    /**
     * @return int
     */
    public function getVictimHealth()
    {
        return $this->victimHealth;
    }

    /**
     * @param int $victimHealth
     */
    public function setVictimHealth($victimHealth)
    {
        $this->victimHealth = $victimHealth;
    }

    /**
     * @return int
     */
    public function getVictimArmor()
    {
        return $this->victimArmor;
    }

    /**
     * @param int $victimArmor
     */
    public function setVictimArmor($victimArmor)
    {
        $this->victimArmor = $victimArmor;
    }
}