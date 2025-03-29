<?php

namespace Monitoring;

class Device {
    protected string $name;
    protected string $ip;
    protected string $status;

    public function __construct($name, $ip, $status = null) {
        $this->name = $name;
        $this->ip = $ip;
        $this->status = $status ?? rand(0, 4) ? 'OK' : 'NOK';
    }

    public function getStatus(): string {
        return $this->status;
    }

    public function setStatus(string $status): void {
        $this->status = $status;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getIp(): string {
        return $this->ip;
    }
}
