<?php

namespace Monitoring;

class Subject {
    private $observers = [];

    public function attach(ObserverInterface $observer): void {
        $this->observers[] = $observer;
    }

    public function detach(ObserverInterface $observer): void {
        $this->observers = array_filter($this->observers, fn($obs) => $obs !== $observer);
    }

    public function notify(Alert $alert): void {
        foreach ($this->observers as $observer) {
            $observer->update($alert);
        }
    }
}
