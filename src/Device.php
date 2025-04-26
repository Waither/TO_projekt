<?php

namespace Monitoring;

abstract class Device {
    protected int $ID;
    protected string $name;
    protected string $ip;
    protected string $status;

    public function __construct(int $ID, string $name, string $ip, bool $status) {
        $this->ID = $ID;
        $this->name = $name;
        $this->ip = $ip;
        $this->status = $status ? 'OK' : 'NOK';
    }

    public function getID(): int {
        return $this->ID;
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

    // Metoda abstrakcyjna do implementacji w klasach pochodnych
    abstract protected function analyzeSpecifics(): string;
}
