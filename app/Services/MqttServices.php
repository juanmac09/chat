<?php

namespace App\Services;

use App\Interfaces\IMqtt;
use PhpMqtt\Client\Facades\MQTT;
class MqttServices implements IMqtt
{
   public function published(string $topic, string $message)
   {
        MQTT::publish($topic, $message);
   }
}
