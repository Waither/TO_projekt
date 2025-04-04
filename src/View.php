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
        echo "<div class='device'>";
        echo "<h3>{$device->getName()}</h3>";
        echo "<p>IP: {$device->getIp()}</p>";
        echo "<p>Status: <span class='".($device->getStatus() == 'NOK' ? 'down' : 'ok')."'>{$device->getStatus()}</span></p>";
        if ($_COOKIE['monitoring_strategy'] === 'advanced') {
            echo "<p>{$device->analyzeSpecifics()}</p>";
        }
        echo "</div>";
    }

    // Stały kontener dla alertów
    public function renderAlerts($alerts) {
        echo "<div class='alertDiv'>";
        if (!empty($alerts)) {
            foreach ($alerts as $alert) {
                $alert->getAlert();
            }
        }
        echo "</div>";
    }
}
