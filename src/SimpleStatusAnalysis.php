<?php

namespace Monitoring;

class SimpleStatusAnalysis implements StatusAnalysisStrategy {
    public function analyzeStatus(Device $device) {
        return rand(0, 1) ? 'OK' : 'DOWN';  // Prosta analiza
    }
}
