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

    // Tworzenie urządzenia na podstawie typu
    public static function createDevice(string $type, string $name, string $ip, mixed $additionalParams = null): Device {
        return match ($type) {
            'server' => self::createServer($name, $ip, $additionalParams ?? []),
            'router' => self::createRouter($name, $ip, $additionalParams['routingProtocol'] ?? 'RIP'),
            'switch' => self::createSwitch($name, $ip, $additionalParams ?? 24),
            default => throw new \Exception("Unknown device type."),
        };
    }
}
