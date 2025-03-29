<?php

namespace Monitoring;

class Router extends Device {
    private $routingProtocol;
    private $activeConnections;  // Liczba aktywnych połączeń
    private $interfaces;         // Tablica z interfejsami sieciowymi i ich stanem

    public function __construct($name, $ip, $routingProtocol = 'RIP') {
        parent::__construct($name, $ip);
        $this->routingProtocol = $routingProtocol;
        $this->updateConnectionsAndInterfaces();
    }

    private function updateConnectionsAndInterfaces() {
        // Example logic to simulate active connections and interface statuses
        $this->activeConnections = rand(10, 100); // Random number of active connections
        $this->interfaces = [
            'eth0' => rand(0, 1) ? 'up' : 'down',
            'eth1' => rand(0, 1) ? 'up' : 'down',
            'eth2' => rand(0, 1) ? 'up' : 'down'
        ];
    }

    public function getRoutingProtocol() {
        return $this->routingProtocol;
    }

    public function getActiveConnections() {
        return $this->activeConnections;
    }

    public function getInterfaces() {
        return $this->interfaces;
    }

    public function getDeviceInfo() {
        $interfacesStatus = implode(', ', array_map(function($key, $value) {
            return "{$key}: {$value}";
        }, array_keys($this->interfaces), $this->interfaces));
        
        return parent::getDeviceInfo() . ", Protokół routingu: {$this->routingProtocol}, Aktywne połączenia: {$this->activeConnections}, Interfejsy: {$interfacesStatus}";
    }

    public function getInterfaceStatus($interface) {
        return isset($this->interfaces[$interface]) ? $this->interfaces[$interface] : 'Nieprawidłowy interfejs';
    }
}
