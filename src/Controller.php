<?php

namespace Monitoring;

class Controller {
    private $monitor;
    private $alert;
    private $log;
    private $configManager;
    private $alerts = [];

    public function __construct() {
        $this->monitor = Monitor::getInstance(); // Singleton
        $this->alert = new Alert();
        $this->log = new Log();
        $this->configManager = new ConfigurationManager();
    }

    public function addDevice($type, $name, $ip, $additionalParams = []) {
        switch ($type) {
            case 'server':
                $device = new Server($name, $ip, $additionalParams['services'] ?? []);
                break;
            case 'router':
                $device = new Router($name, $ip, $additionalParams['routingProtocol'] ?? 'RIP');
                break;
            case 'switch':
                $device = new SwitchDevice($name, $ip, $additionalParams['ports'] ?? 24);
                break;
            default:
                throw new \Exception("Unknown device type.");
        }

        $this->monitor->addDevice($device);
    }

    public function monitorDevices() {
        $this->monitor->checkDeviceStatus();
        foreach ($this->monitor->getDevices() as $device) {
            $this->log->logStatus($device);
            if ($device->getStatus() === 'DOWN') {
                $this->alerts[] = "Alert: Urządzenie {$device->getName()} jest {$device->getStatus()}.";
            }
        }
    }

    public function configureDevice($name, $newStatus) {
        foreach ($this->monitor->getDevices() as $device) {
            if ($device->getName() === $name) {
                $this->configManager->configureDevice($device, $newStatus);
            }
        }
    }

    public function setMonitorStrategy($strategy) {
        if ($strategy === 'simple') {
            $this->monitor->setStrategy(new SimpleStatusAnalysis());
        }
        elseif ($strategy === 'advanced') {
            $this->monitor->setStrategy(new AdvancedStatusAnalysis());
        }
    }

    public function getDevices() {
        return $this->monitor->getDevices();
    }

    public function getAlerts() {
        return $this->alerts;
    }
}
