<?php

namespace Monitoring;

class Server extends Device {
    private array $services;
    private int $cpuUsage;
    private int $ramUsage;
    private int $diskSpace;

    public function __construct(int $ID, string $name, string $ip, bool $status, int $cpu, int $ram, int $disk, array $services) {
        parent::__construct($ID, $name, $ip, $status);
        $this->services = $services['services'] ?? [];
        $this->cpuUsage = $cpu;
        $this->ramUsage = $ram;
        $this->diskSpace = $disk;
    }

    public function analyzeSpecifics(): string {
        $services = implode(', ', $this->services);
        return "Usługi: {$services}<br>CPU: {$this->cpuUsage}%<br>RAM: {$this->ramUsage}%<br>Dysk: {$this->diskSpace}%";
    }
 
    public function getStatus(): string {
        return $this->status;
    }

    public function getServices(): array {
        return $this->services;
    }

    public function addService(string $service): void {
        $this->services[] = $service;
    }

    public function getCpuUsage(): int {
        return $this->cpuUsage;
    }

    public function getRamUsage(): int {
        return $this->ramUsage;
    }

    public function getDiskSpace(): int {
        return $this->diskSpace;
    }
}
