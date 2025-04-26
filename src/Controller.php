<?php

namespace Monitoring;

class Controller extends Subject {
    private $monitor;
    private $log;
    private $configManager;
    private $alertNotifier;

    public function __construct() {
        $this->monitor = Monitor::getInstance(); // Singleton
        $this->log = new Log();
        $this->configManager = new ConfigurationManager();
        $this->alertNotifier = new AlertNotifier();
        $this->attach($this->alertNotifier);
    }

    public function addDevice(object $device): void {
        $deviceObject = DeviceFactory::createDevice($device); // Factory Method
        if ($deviceObject === null) {
            $alert = new Alert($device->name, "Failed to create device of type: {$device->type}", date('Y-m-d H:i:s'));
            $this->notify($alert); // Powiadom obserwatorów o nowym alercie
            return;
        }
        $this->monitor->addDevice($deviceObject);
    }
    

    public function monitorDevices(): void {
        $this->monitor->checkDeviceStatus();
        foreach ($this->monitor->getDevices() as $device) {
            $this->log->logStatus($device);
            if ($device->getStatus() === 'NOK') {
                $alert = new Alert($device->getName(), 'Device is down', date('Y-m-d H:i:s'));
                $this->notify($alert); // Powiadom obserwatorów o nowym alercie
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

    public function setMonitorStrategy($strategy): void { // Strategy
        $strategies = [
            'simple' => SimpleStatusAnalysis::class,
            'advanced' => AdvancedStatusAnalysis::class,
        ];

        if (isset($strategies[$strategy])) {
            $this->monitor->setStrategy(new $strategies[$strategy]());
        }
        else {
            $this->notify(new Alert('Controller', "Unknown strategy: $strategy", date('Y-m-d H:i:s')));
            $this->monitor->setStrategy(new $strategies["simple"]());
        }
    }

    public function getDevices(): array {
        return $this->monitor->getDevices();
    }

    public function getAlerts(): array {
        return $this->alertNotifier->getAlerts();
    }
}
