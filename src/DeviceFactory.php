<?php

namespace Monitoring;

class DeviceFactory {

    // Tworzenie serwera
    private static function createServer(int $ID, string $name, string $ip, bool $status, int $cpu, int $ram, int $disk, array $services = []): Server {
        return new Server($ID, $name, $ip, $status, $cpu, $ram, $disk, $services);
    }

    // Tworzenie routera
    private static function createRouter(int $ID,string $name, string $ip, bool $status, string $routingProtocol, int $activeConnections, array $interfaces = []): Router {
        return new Router($ID, $name, $ip, $status, $routingProtocol, $activeConnections, $interfaces);
    }

    // Tworzenie switcha
    private static function createSwitch(int $ID,string $name, string $ip, bool $status, array $ports = []): SwitchDevice {
        return new SwitchDevice($ID, $name, $ip, $status, $ports);
    }

    // Tworzenie urządzenia na podstawie typu
    public static function createDevice(object $device): Device|null {
        try {
            return match ($device->type) {
                'server' => self::createServer($device->ID, $device->name, $device->IP, $device->status, $device->cpu, $device->ram, $device->disk, json_decode($device->server_services)),
                'router' => self::createRouter($device->ID, $device->name, $device->IP, $device->status, $device->router_protocol, $device->router_activeConnections, (array)json_decode($device->router_interfaces)),
                'switch' => self::createSwitch($device->ID, $device->name, $device->IP, $device->status, json_decode($device->switch_ports)),
                default => throw new \InvalidArgumentException("Invalid device type: {$device->type}"),
            };
        }
        catch (\Exception $e) {
            return null;
        }
    }
}
