<?php

namespace Monitoring;

class Alert {
    private string $deviceName;
    private string $message;
    private string $timestamp;

    public function __construct(string $deviceName, string $message, string $timestamp) {
        $this->deviceName = $deviceName;
        $this->message = $message;
        $this->timestamp = $timestamp;
    }

    public function getAlert(): void {
        echo "<div class='alert'>{$this->deviceName} | {$this->message}</div>";
    }

    public function getMessage(): string {
        return $this->message;
    }

    public function getTimestamp(): string {
        return $this->timestamp;
    }

    public function getDeviceName(): string {
        return $this->deviceName;
    }
}
