<?php

namespace Monitoring;

class ConfigurationManager {

    // Ustawienie statusu urządzenia
    public function configureDevice(Device $device, string $newStatus) {
        $device->setStatus($newStatus);
    }
}
