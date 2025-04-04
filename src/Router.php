<?php

namespace Monitoring;

class Router extends Device {
    private string $routingProtocol;
    private int $activeConnections;
    private array $interfaces;

    public function __construct($name, $ip, $routingProtocol = 'RIP') {
        parent::__construct($name, $ip);
        $this->routingProtocol = $routingProtocol;
        $this->updateConnectionsAndInterfaces();
    }

    private function updateConnectionsAndInterfaces(): void {
        if ($this->status === "NOK") {
            $this->interfaces = [
                'eth0' => 'down',
                'eth1' => 'down',
                'eth2' => 'down'
            ];
        }
        else {
            $this->interfaces = [
            'eth0' => rand(0, 4) ? 'up' : 'down',
            'eth1' => rand(0, 4) ? 'up' : 'down',
            'eth2' => rand(0, 4) ? 'up' : 'down'
            ];
        }
        $this->activeConnections = $this->getActiveInterfaces() ? rand(10, 100) : 0;
    }

    public function analyzeSpecifics(): string {
        $interfaces = '';
        foreach ($this->interfaces as $interface => $status) {
            $interfaces .= "{$interface}: {$status}<br>";
        }
        return "Protokół routingu: {$this->routingProtocol}<br>Aktywne połączenia: {$this->activeConnections}<br>Interfejsy:<br>{$interfaces}";
    }

    public function setStatus(string $status): void {
        parent::setStatus($status);
        if ($this->status === "NOK") $this->updateConnectionsAndInterfaces();
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
