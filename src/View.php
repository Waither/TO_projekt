<?php

namespace Monitoring;

class View {
    public function renderDeviceStatus($devices) {
        // Grupowanie urządzeń po typie
        $servers = [];
        $routers = [];
        $switches = [];

        // Przypisywanie urządzeń do odpowiednich tablic
        foreach ($devices as $device) {
            if ($device instanceof Server) {
                $servers[] = $device;
            } elseif ($device instanceof Router) {
                $routers[] = $device;
            } elseif ($device instanceof SwitchDevice) {
                $switches[] = $device;
            }
        }

        // Wyświetlanie urządzeń w kontenerze
        echo "<div class='devices-container'>";

        // Wyświetlanie nagłówka i urządzeń dla serwerów
        if (count($servers) > 0) {
            echo "<div class='device-category'>";
            echo "<h1 class='category-header'>Servers</h1>";
            echo "<div class='device-items'>";
            foreach ($servers as $device) {
                $this->renderDevice($device);
            }
            echo "</div></div>";
        }

        // Wyświetlanie nagłówka i urządzeń dla routerów
        if (count($routers) > 0) {
            echo "<div class='device-category'>";
            echo "<h1 class='category-header'>Routers</h1>";
            echo "<div class='device-items'>";
            foreach ($routers as $device) {
                $this->renderDevice($device);
            }
            echo "</div></div>";
        }

        // Wyświetlanie nagłówka i urządzeń dla switchy
        if (count($switches) > 0) {
            echo "<div class='device-category'>";
            echo "<h1 class='category-header'>Switches</h1>";
            echo "<div class='device-items'>";
            foreach ($switches as $device) {
                $this->renderDevice($device);
            }
            echo "</div></div>";
        }

        echo "</div>"; // end devices-container
    }

    // Funkcja pomocnicza do renderowania pojedynczego urządzenia
    private function renderDevice($device) {
        echo "<div class='device'>";
        echo "<h3>{$device->getName()}</h3>";
        echo "<p>IP: {$device->getIp()}</p>";
        echo "<p>Status: <span class='" . ($device->getStatus() == 'DOWN' ? 'down' : 'ok') . "'>{$device->getStatus()}</span></p>";
        
        // Wyświetlanie specyficznych informacji w zależności od typu urządzenia
        if ($device instanceof Server) {
            $services = implode(', ', $device->getServices());
            echo "<p>Usługi: {$services}</p>";  // Usługi serwera
            echo "<p>CPU: {$device->getCpuUsage()}%, RAM: {$device->getRamUsage()}%, Dysk: {$device->getDiskSpace()}%</p>";  // Zasoby serwera
        } elseif ($device instanceof Router) {
            echo "<p>Protokół routingu: {$device->getRoutingProtocol()}</p>";  // Protokół routingu routera
            echo "<p>Aktywne połączenia: {$device->getActiveConnections()}</p>";  // Aktywne połączenia
            echo "<p>Interfejsy: </p><ul>";
            foreach ($device->getInterfaces() as $interface => $status) {
                echo "<li>{$interface}: {$status}</li>";
            }
            echo "</ul>";
        } elseif ($device instanceof SwitchDevice) {
            echo "<p>Liczba portów: {$device->getPortsCount()}</p>";  // Liczba portów switcha
            echo "<p>Zajęte porty: {$device->getUsedPortsCount()}/{$device->getPortsCount()}</p>";  // Zajęte porty switcha
            
            // Wyświetlanie statusu portów w kompaktowej formie (np. "P: Zajęty, P: Wolny")
            $portStatus = [];
            for ($i = 0; $i < $device->getPortsCount(); $i++) {
                $portStatus[] = $device->getPortStatus($i) == "Zajęty" ? "Z" : "W";  // Skrót "Z" dla zajętych, "W" dla wolnych
            }
            echo "<p>Status portów: " . implode(', ', $portStatus) . "</p>";  // Kompaktowy status portów
        }

        echo "</div>";
    }

    public function renderAlerts($alerts) {
        // Dodajemy stały kontener dla alertów
        echo "<div class='alertDiv'>";
        if (!empty($alerts)) {
            foreach ($alerts as $alert) {
                echo "<div class='alert'>{$alert}</div>";
            }
        }
        echo "</div>";
    }
}
