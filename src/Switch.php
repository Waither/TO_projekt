<?php

namespace Monitoring;

class SwitchDevice extends Device {
    private $ports = [];

    public function __construct(string $name, string $ip, int $ports) {
        parent::__construct($name, $ip);
        
        for ($i = 0; $i < $ports; $i++) {
            $this->ports[] = (bool)rand(0, 5);
        }
    }

    // Zmiana statusu portu na zajęty (true) lub wolny (false)
    public function setStatus(string $status): void {
        $this->status = $status;
        if ($this->status === "NOK") {
            foreach ($this->ports as $index => $port) {
                $this->ports[$index] = false; // Ustawienie wszystkich portów na wolne
            }
        }
    }

    public function analyzeSpecifics(): string {
        $usedPorts = $this->getUsedPortsCount();
        $ports = $this->getPortsCount();
        $percentUsed = $this->getPercentUsed();
        return "Liczba portów: {$ports}<br>Zajęte porty: {$usedPorts}/{$ports} ({$percentUsed}%)";
    }

    // Zliczenie wszystkich portów
    public function getPortsCount(): int {
        return count($this->ports);
    }

    // Zliczanie liczby zajętych portów (liczba `true` w tablicy)
    public function getUsedPortsCount(): int {
        return count(array_filter($this->ports, function($port) {
            return $port === true;
        }));
    }

    // Zliczanie procentu zajęcia portów
    public function getPercentUsed(): float {
        $usedPorts = $this->getUsedPortsCount();
        $totalPorts = $this->getPortsCount();
        return $totalPorts > 0 ? round(($usedPorts / $totalPorts) * 100) : 0;
    }

    // Sprawdzenie statusu konkretnego portu (zajęty / wolny)
    public function getPortStatus($portIndex): string {
        if ($portIndex >= 0 && $portIndex < $this->getPortsCount()) {
            return $this->ports[$portIndex] ? "Zajęty" : "Wolny";
        }
        return "Nieprawidłowy port.";
    }
}
