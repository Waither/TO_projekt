<?php

namespace Monitoring;

class Monitor {
    private static $instance;
    private $devices;
    private $strategy;

    private function __construct() {
        $this->devices = [];
        $this->strategy = new SimpleStatusAnalysis(); // Domyślna strategia
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Monitor();
        }
        return self::$instance;
    }

    public function setStrategy(StatusAnalysisStrategy $strategy) {
        $this->strategy = $strategy;
    }

    public function addDevice(Device $device) {
        $this->devices[] = $device;
    }

    public function checkDeviceStatus() {
        foreach ($this->devices as $device) {
            $device->setStatus($this->strategy->analyzeStatus($device));
        }
    }

    public function getDevices() {
        return $this->devices;
    }
}
