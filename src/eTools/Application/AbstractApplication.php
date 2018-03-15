<?php
/**
 * eBot - A bot for match management for CS:GO
 *
 * @license http://creativecommons.org/licenses/by/3.0/ Creative Commons 3.0
 * @author  Julien Pardons <julien.pardons@esport-tools.net>
 * @version 3.0
 * @date    21/10/2012
 */

namespace eTools\Application;

use eTools\Utils\Singleton;

abstract class AbstractApplication extends Singleton
{

    abstract public function run();

    abstract public function getName();

    abstract public function getVersion();
}
