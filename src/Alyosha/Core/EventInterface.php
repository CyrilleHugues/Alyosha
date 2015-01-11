<?php

namespace Alyosha\Core;

interface EventInterface
{
    public function isHaltSignal();
    public function getName();
}
