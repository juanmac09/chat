<?php

namespace App\Services;

use App\Interfaces\IMqtt;
use PhpMqtt\Client\Facades\MQTT;

class MqttServices implements IMqtt
{
   /**
    * Publishes a message to the specified MQTT topic.
    *
    * @param string $topic The MQTT topic to publish the message to.
    * @param string $message The message to be published.
    *
    * @return void
    */
   public function published(string $topic, string $message)
   {
      MQTT::publish($topic, $message);
   }
}
