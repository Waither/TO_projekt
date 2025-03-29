<?php

namespace Monitoring;

class Server extends Device {
    private $services;
    private $cpuUsage;
    private $ramUsage;
    private $diskSpace;

    public function __construct($name, $ip, $services = [], $status = "up") {
        parent::__construct($name, $ip);
        $this->services = $services;
        $this->status = $status;
        $this->updateUsage();
    }

    private function updateUsage() {
        if ($this->status === "OK") {
            $this->cpuUsage = rand(1, 100);
            $this->ramUsage = rand(1, 100);
            $this->diskSpace = rand(1, 100);
        }
        else {
            $this->cpuUsage = 0;
            $this->ramUsage = 0;
            $this->diskSpace = 0;
        }
    }

    public function setStatus($status) {
        $this->status = $status;
        $this->updateUsage();
    }

    public function getStatus() {
        return $this->status;
    }

    public function getServices() {
        return $this->services;
    }

    public function addService($service) {
        $this->services[] = $service;
    }

    public function getCpuUsage() {
        return $this->cpuUsage;
    }

    public function getRamUsage() {
        return $this->ramUsage;
    }

    public function getDiskSpace() {
        return $this->diskSpace;
    }

    public function getDeviceInfo() {
        $servicesList = implode(', ', $this->services);
        return parent::getDeviceInfo() . ", Status: {$this->status}, Usługi: {$servicesList}, CPU: {$this->cpuUsage}%, RAM: {$this->ramUsage}%, Dysk: {$this->diskSpace}%";
    }
}
