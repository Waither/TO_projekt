<?php

namespace Monitoring;

class Log {
    public function logStatus(Device $device) {
        // Zapis logu do pliku
        $datetime = date('Y-m-d H:i:s') . '.' . sprintf('%03d', (int) (microtime(true) * 1000) % 1000);
        file_put_contents('log.txt', "{$datetime} | Urządzenie: {$device->getName()} IP: {$device->getIp()} Status: {$device->getStatus()}\n", FILE_APPEND);
    }
}
