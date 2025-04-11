<?php

namespace Monitoring;

class AdvancedStatusAnalysis implements StatusAnalysisStrategy {

    public function analyzeStatus(Device $device): string {
        if ($device instanceof Server) {
            $cpuUsage = $device->getCpuUsage();
            $ramUsage = $device->getRamUsage();
            $diskSpace = $device->getDiskSpace();
            $alerts = [];

            if ($device->getStatus() === 'OK') {
                if ($cpuUsage >= 90) {
                    $alerts[] = '<br><span style="color:red;">High CPU Usage</span>';
                }
                if ($ramUsage >= 90) {
                    $alerts[] = '<br><span style="color:red;">High RAM Usage</span>';
                }
                if ($diskSpace >= 90) {
                    $alerts[] = '<br><span style="color:red;">Low Disk Space</span>';
                }
            }
            
            return $device->getStatus().implode('', $alerts);
        }

        if ($device instanceof Router) {
            $activeConnections = $device->getActiveConnections();
            $interfaces = $device->getInterfaces();
            $activeInterfaces = $device->getActiveInterfaces();
            $alerts = [];

            if ($device->getStatus() === 'OK') {
                if ($activeConnections / ($activeInterfaces? : 1) > 30) {
                    $alerts[] = '<br><span style="color:red;">Too many active connections</span>';
                }

                foreach ($interfaces as $interface => $status) {
                    if ($status === 'down') {
                        $alerts[] = "<br><span style=\"color:red;\">Interface {$interface} is down</span>";
                    }
                }
            }
            

            return $device->getStatus().implode('', $alerts);
        }

        if ($device instanceof SwitchDevice) {
            $ports = $device->getPortsCount();
            $percentUsed = $device->getPercentUsed();
            $alerts = [];

            if ($device->getStatus() === 'OK') {
                if ($percentUsed == 100) {
                    $alerts[] = '<br><span style="color:red;">All ports are used</span>';
                }
                elseif ($ports > 0 && $percentUsed >= 90) {
                    $alerts[] = '<br><span style="color:orange;">High port utilization</span>';
                }
                elseif ($ports == 0) {
                    $alerts[] = '<br><span style="color:red;">No ports available</span>';
                }
            }

            return $device->getStatus().implode('', $alerts);
        }

        return 'UNKNOWN<br><span style="color:red;">ALERT: Unknown device type</span>';
    }
}
