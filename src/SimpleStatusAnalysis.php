<?php

namespace Monitoring;

class SimpleStatusAnalysis implements StatusAnalysisStrategy {
    public function analyzeStatus(Device $device): string {
        return $device->getStatus();
    }
}
