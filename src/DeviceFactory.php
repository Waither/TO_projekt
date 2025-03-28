<?php

namespace Monitoring;

class DeviceFactory {

    // Tworzenie serwera
    public static function createServer(string $name, string $ip, array $services = []): Server {
        return new Server($name, $ip, $services);
    }

    // Tworzenie routera
    public static function createRouter(string $name, string $ip, string $routingProtocol = 'RIP'): Router {
        return new Router($name, $ip, $routingProtocol);
    }

    // Tworzenie switcha
    public static function createSwitch(string $name, string $ip, int $ports): SwitchDevice {
        return new SwitchDevice($name, $ip, $ports);
    }

    // Można dodać metodę, która utworzy urządzenie na podstawie typu
    public static function createDevice(string $type, string $name, string $ip, mixed $additionalParams = null): Router|Server|SwitchDevice {
        switch ($type) {
            case 'server':
                return self::createServer($name, $ip, $additionalParams ?? []);
            case 'router':
                return self::createRouter($name, $ip, $additionalParams['routingProtocol'] ?? 'RIP');
            case 'switch':
                return self::createSwitch($name, $ip, $additionalParams ?? 24);
            default:
                throw new \Exception("Unknown device type.");
        }
    }
}
