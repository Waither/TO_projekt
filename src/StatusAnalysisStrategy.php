<?php

namespace Monitoring;

interface StatusAnalysisStrategy {
    public function analyzeStatus(Device $device);
}
