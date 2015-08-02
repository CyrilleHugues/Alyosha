<?php

namespace Alyosha\Time;

use Alyosha\Core\Event\EventInterface;
use Alyosha\Core\Module\AbstractModule;

class TimeModule extends AbstractModule
{
    const NAME = 'time_bringer';

    protected $lastDate;

    /**
     * Start the module by creating sockets and such actions with the given config
     */
    public function start(array $config)
    {
        $this->lastDate = new \DateTime('now', new \DateTimeZone('UTC'));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return self::NAME;
    }

    /**
     * Return events created during the notification phase
     *
     * @return EventInterface[]
     */
    public function getEvents()
    {
        $now = new \DateTime('now', new \DateTimeZone('UTC'));
        if ($this->lastDate->format('Y-m-d H:i') !== $now->format('Y-m-d H:i')) {
            $this->lastDate = $now;
            var_dump(new TimeEvent($now));
            return [
                new TimeEvent($now)
            ];
        }

        return [];
    }
}