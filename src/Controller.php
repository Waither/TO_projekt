<?php

namespace Monitoring;

class Controller {
    private $monitor;
    private $log;
    private $configManager;
    private $alerts = [];

    public function __construct() {
        $this->monitor = Monitor::getInstance(); // Singleton
        $this->log = new Log();
        $this->configManager = new ConfigurationManager();
    }

    public function addDevice(string $type, string $name, string $ip, mixed $additionalParams = null): void {
        $device = DeviceFactory::createDevice($type, $name, $ip, $additionalParams); // Factory Method
        $this->monitor->addDevice($device);
    }
    

    public function monitorDevices(): void {
        $this->monitor->checkDeviceStatus();
        foreach ($this->monitor->getDevices() as $device) {
            $this->log->logStatus($device);
            if ($device->getStatus() === 'NOK') {
                $this->alerts[] = new Alert($device->getName(), 'Device is down', date('Y-m-d H:i:s'));
            }
        }
    }

    public function configureDevice($name, $newStatus): void {
        foreach ($this->monitor->getDevices() as $device) {
            if ($device->getName() === $name) {
                $this->configManager->configureDevice($device, $newStatus);
            }
        }
    }

    public function setMonitorStrategy($strategy): void { // Strategy Pattern
        $strategies = [
            'simple' => SimpleStatusAnalysis::class,
            'advanced' => AdvancedStatusAnalysis::class,
        ];

        if (isset($strategies[$strategy])) {
            $this->monitor->setStrategy(new $strategies[$strategy]());
        }
        else {
            throw new \InvalidArgumentException("Unknown strategy: $strategy");
        }
    }

    public function getDevices(): array {
        return $this->monitor->getDevices();
    }

    public function getAlerts() {
        return $this->alerts;
    }
}
