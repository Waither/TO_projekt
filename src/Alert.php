<?php

namespace Monitoring;

class Alert {
    public function sendAlert(Device $device) {
        // Zwracamy HTML, który dodaje alert do stałego kontenera alertDiv
        echo "<div class='alert'>Alert: Urządzenie {$device->getName()} o IP {$device->getIp()} jest {$device->getStatus()}.</div>";
    }
}
