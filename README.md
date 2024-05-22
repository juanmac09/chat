# Laravel MQTT Chat Backend

## Descripción

Este proyecto es el backend de un sistema de chat utilizando el framework Laravel. El chat utiliza MQTT (Message Queuing Telemetry Transport) para la transmisión de mensajes en tiempo real. El backend gestiona la autenticación de usuarios, el envío y recepción de mensajes, así como la persistencia y consulta de mensajes no leídos.

## Tabla de Contenidos

- [Requisitos](#requisitos)
- [Instalación](#instalación)
- [Configuración](#configuración)
- [Estructura del Proyecto](#estructura-del-proyecto)
- [Uso](#uso)
- [Eventos](#eventos)
- [Consultas SQL](#consultas-sql)
- [Contribuir](#contribuir)
- [Licencia](#licencia)

## Requisitos

- PHP >= 7.3
- Composer
- Laravel >= 8.x
- MySQL o PostgreSQL
- Mosquitto (Servidor MQTT) o cualquier otro servidor MQTT
- Node.js (para el manejo de WebSockets y el cliente MQTT)

## Instalación

1. Clona el repositorio:
    sh
    git clone https://github.com/juanmac09/chat.git
    

2. Ve al directorio del proyecto:
    sh
    cd laravel-mqtt-chat-backend
    

3. Instala las dependencias de PHP:
    sh
    composer install
    

4. Configura tu archivo .env:
    sh
    cp .env.example .env
    

5. Genera la clave de la aplicación:
    sh
    php artisan key:generate
    

6. Configura la base de datos en el archivo .env y migra las tablas:
    sh
    php artisan migrate
    

7. Instala las dependencias de Node.js:
    sh
    npm install
    

## Configuración

En el archivo .env, configura los siguientes parámetros para la conexión a la base de datos y al servidor MQTT:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nombre_base_datos
DB_USERNAME=usuario
DB_PASSWORD=contraseña

MQTT_HOST=127.0.0.1
MQTT_PORT=1883
MQTT_USERNAME=usuario
MQTT_PASSWORD=contraseña
