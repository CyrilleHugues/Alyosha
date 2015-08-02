<?php

namespace Alyosha\Time;

use Alyosha\Core\Event\AbstractEvent;

class TimeEvent extends AbstractEvent
{
    /**
     * @var \DateTime
     */
    protected $date;

    /**
     * @param \DateTime $date
     */
    public function __construct(\DateTime $date)
    {
        $this->date = $date;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return TimeModule::NAME.'.date';
    }
}