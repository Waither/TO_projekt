<?php

namespace Monitoring;

class View {
    public function renderDeviceStatus($devices) {
        // Grupowanie urządzeń po typie
        $groupedDevices = [
            'Servers' => [],
            'Routers' => [],
            'Switches' => []
        ];

        // Przypisywanie urządzeń do odpowiednich grup
        foreach ($devices as $device) {
            if ($device instanceof Server) {
                $groupedDevices['Servers'][] = $device;
            }
            elseif ($device instanceof Router) {
                $groupedDevices['Routers'][] = $device;
            }
            elseif ($device instanceof SwitchDevice) {
                $groupedDevices['Switches'][] = $device;
            }
        }

        // Wyświetlanie urządzeń w kontenerze
        echo "<div class='devices-container'>";
        echo "<h1 class='header'>Device Tracker</h1>";
        $selectedAnalysis = isset($_COOKIE['monitoring_strategy']) ? htmlspecialchars($_COOKIE['monitoring_strategy']) : 'default';
        echo "
        <p class='header'>Analysis Type: 
            <select name='analysis_type' id='analysis_type' onchange='updateAnalysisType(this.value)'>
                <option value='simple'" . ($selectedAnalysis === 'simple' ? ' selected' : '') . ">Simple</option>
                <option value='advanced'" . ($selectedAnalysis === 'advanced' ? ' selected' : '') . ">Advanced</option>
            </select>
        </p>";
        echo "
        <script>
            function updateAnalysisType(value) {
                document.cookie = 'monitoring_strategy=' + value + '; path=/';
                location.reload();
            }
        </script>";

        // Iteracja po grupach urządzeń
        foreach ($groupedDevices as $category => $devices) {
            if (count($devices) > 0) {
                echo "<div class='device-category'>";
                echo "<h1 class='category-header'>{$category}</h1>";
                echo "<div class='device-items'>";
                foreach ($devices as $device) {
                    $this->renderDevice($device);
                }
                echo "</div></div>";
            }
        }

        echo "</div>";
    }

    // Funkcja do renderowania pojedynczego urządzenia
    private function renderDevice($device) {
        $selectedAnalysis = isset($_COOKIE['monitoring_strategy']) ? htmlspecialchars($_COOKIE['monitoring_strategy']) : 'simple';

        echo "<div class='device'>";
        echo "<h3>{$device->getName()}</h3>";
        echo "<p>IP: {$device->getIp()}</p>";
        echo "<p>Status: <span class='".($device->getStatus() == 'NOK' ? 'down' : 'ok')."'>{$device->getStatus()}</span></p>";
        
        // Wyświetlanie specyficznych informacji w zależności od typu urządzenia
        if ($device instanceof Server) {
            $services = implode(', ', $device->getServices());
            echo "<p>Usługi: {$services}</p>";
            if ($selectedAnalysis === 'advanced') {
                echo "<p>CPU: {$device->getCpuUsage()}%<br>RAM: {$device->getRamUsage()}%<br>Dysk: {$device->getDiskSpace()}%</p>";
            }
        }
        elseif ($device instanceof Router) {
            if ($selectedAnalysis === 'advanced') {
                echo "<p>Protokół routingu: {$device->getRoutingProtocol()}</p>";
                echo "<p>Aktywne połączenia: {$device->getActiveConnections()}</p>";
                echo "<p>Interfejsy: </p><ul>";
                foreach ($device->getInterfaces() as $interface => $status) {
                    echo "<li>{$interface}: {$status}</li>";
                }
                echo "</ul>";
            }
        }
        elseif ($device instanceof SwitchDevice) {
            echo "<p>Liczba portów: {$device->getPortsCount()}</p>";
            if ($selectedAnalysis === 'advanced') {
                $usedPorts = $device->getUsedPortsCount();
                $ports = $device->getPortsCount();
                $percentUsed = $device->getPercentUsed();
                
                echo "<p>Zajęte porty: {$usedPorts}/{$ports} ({$percentUsed}%)</p>";
                
                $portStatus = [];
                for ($i = 0; $i < $device->getPortsCount(); $i++) {
                    $portStatus[] = $device->getPortStatus($i) == "Zajęty" ? "Z" : "W";
                }
                echo "<p>Status portów:<br>".implode(', ', $portStatus)."</p>";
            }
        }

        echo "</div>";
    }

    // Stały kontener dla alertów
    public function renderAlerts($alerts) {
        echo "<div class='alertDiv'>";
        if (!empty($alerts)) {
            foreach ($alerts as $alert) {
                echo $alert->getAlert();
            }
        }
        echo "</div>";
    }
}
