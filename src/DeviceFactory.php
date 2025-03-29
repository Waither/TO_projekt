<?php

namespace Monitoring;

class DeviceFactory {
    public static function createDevice($name, $ip) {
        return new Device($name, $ip);
    }
}
