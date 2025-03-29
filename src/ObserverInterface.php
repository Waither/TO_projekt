<?php

namespace Monitoring;

interface ObserverInterface {
    public function update(Alert $alert): void;
}