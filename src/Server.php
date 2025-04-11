<?php

namespace Monitoring;

class Server extends Device {
    private array $services;
    private int $cpuUsage;
    private int $ramUsage;
    private int $diskSpace;

    public function __construct(string $name, string $ip, array $additionalParams, string $status = "OK") {
        parent::__construct($name, $ip, $status);
        $this->services = $additionalParams["services"] ?? [];
        $this->updateUsage($additionalParams["usage"] ?? []);
    }

    private function updateUsage(array $usages = []): void {
        if ($this->status === "OK") {
            $this->cpuUsage = $usages["cpu"] ?? rand(10, 100);
            $this->ramUsage = $usages["ram"] ?? rand(10, 100);
            $this->diskSpace = $usages["disk"] ?? rand(10, 100);
        }
        elseif ($this->status === "NOK") {
            $this->cpuUsage = 0;
            $this->ramUsage = 0;
            $this->diskSpace = 0;
        }
    }

    public function analyzeSpecifics(): string {
        $services = implode(', ', $this->services);
        return "Usługi: {$services}<br>CPU: {$this->cpuUsage}%<br>RAM: {$this->ramUsage}%<br>Dysk: {$this->diskSpace}%";
    }

    public function setStatus(string $status): void {
        $this->status = $status;
        if ($this->status === "NOK") {
            $this->updateUsage();
        }
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
