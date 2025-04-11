<?php

namespace Monitoring;

class AlertNotifier implements ObserverInterface {
    private array $alerts = [];

    // Dodaj alert do tablicy
    public function update(Alert $alert): void {
        $this->alerts[] = $alert;
    }

    // Zwróć wszystkie alerty
    public function getAlerts(): array {
        return $this->alerts;
    }
}
