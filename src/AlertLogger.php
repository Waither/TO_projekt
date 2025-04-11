<?php

namespace Monitoring;

class AlertLogger implements ObserverInterface {

    // Logowanie alertu
    public function update(Alert $alert): void {
        echo "<script>";
        echo "console.error('Logging alert: {$alert->getDeviceName()} | {$alert->getMessage()} at {$alert->getTimestamp()}');";
        echo "</script>";
    }
}
