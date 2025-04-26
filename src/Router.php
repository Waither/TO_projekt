<?php

namespace Monitoring;

class Router extends Device {
    private string $routingProtocol;
    private int $activeConnections;
    private array $interfaces;

    public function __construct(int $ID, string $name, string $ip, bool $status, string $routingProtocol, int $activeConnections, array $interfaces) {
        parent::__construct($ID, $name, $ip, $status);
        $this->routingProtocol = $routingProtocol;
        $this->activeConnections = $activeConnections;
        $this->interfaces = $interfaces;
    }

    public function analyzeSpecifics(): string {
        $interfaces = '';
        foreach ($this->interfaces as $interface => $status) {
            $interfaces .= "{$interface}: {$status}<br>";
        }
        return "Protokół routingu: {$this->routingProtocol}<br>Aktywne połączenia: {$this->activeConnections}<br>Interfejsy:<br>{$interfaces}";
    }

    public function getRoutingProtocol(): string {
        return $this->routingProtocol;
    }

    public function getActiveConnections(): int {
        return $this->activeConnections;
    }

    public function getActiveInterfaces(): int {
        return count(array_filter($this->interfaces, fn($status) => $status === 'up'));
    }

    public function getInterfaces(): array {
        return $this->interfaces;
    }
}
