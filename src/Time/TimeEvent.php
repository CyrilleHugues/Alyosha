<?php

namespace Alyosha\Time;

use Alyosha\Core\Event\AbstractEvent;

class TimeEvent extends AbstractEvent
{
    protected $date;

    public function __construct(\DateTime $date)
    {
        $this->date = $date;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function getName()
    {
        return TimeModule::NAME.'.date';
    }
}