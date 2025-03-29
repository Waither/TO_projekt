<?php

namespace Monitoring;

class ConfigurationManager {
    public function configureDevice(Device $device, $newStatus) {
        $device->setStatus($newStatus);
    }
}
