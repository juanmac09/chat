<?php

namespace App\Interfaces;

interface IMqtt
{
    public function published(string $topic, string $message);
}
