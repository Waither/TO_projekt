<?php

namespace Monitoring;

class SwitchDevice extends Device {
    private $ports;  // Tablica przechowująca stan każdego portu (true/false)

    public function __construct($name, $ip, $ports = 24) {
        parent::__construct($name, $ip);
        
        $this->ports = [];
        for ($i = 0; $i < $ports; $i++) {
            $this->ports[] = rand(0, 1) == 1;  // Losujemy true/false dla każdego portu
        }
    }

    public function getPortsCount(): int {
        return count($this->ports);
    }

    // Zliczanie liczby zajętych portów (liczba `true` w tablicy)
    public function getUsedPortsCount(): int {
        return count(array_filter($this->ports, function($port) {
            return $port === true;
        }));
    }

    // Sprawdzenie statusu konkretnego portu (zajęty / wolny)
    public function getPortStatus($portIndex): string {
        if ($portIndex >= 0 && $portIndex < $this->getPortsCount()) {
            return $this->ports[$portIndex] ? "Zajęty" : "Wolny";
        }
        return "Nieprawidłowy port.";
    }

    public function getDeviceInfo(): string {
        return parent::getDeviceInfo() . ", Liczba portów: {$this->getPortsCount()}, Zajęte porty: {$this->getUsedPortsCount()}/{$this->getPortsCount()}";
    }
}
