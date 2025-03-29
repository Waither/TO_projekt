<?php

namespace Monitoring;

class Device {
    protected $name;
    protected $ip;
    protected $status;

    public function __construct($name, $ip) {
        $this->name = $name;
        $this->ip = $ip;
        $this->status = 'OK';
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function getName() {
        return $this->name;
    }

    public function getIp() {
        return $this->ip;
    }

    public function getDeviceInfo() {
        return "Urządzenie: {$this->name}, IP: {$this->ip}, Status: {$this->status}";
    }
}
