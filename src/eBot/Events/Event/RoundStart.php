<?php

namespace eBot\Events\Event;

use eBot\Events\Event;

/**
 * Class MatchStart
 *
 * @package eBot\Events\Event
 */
class MatchStart extends Event
{
    protected $match;
    protected $score1;
    protected $score2;
}
