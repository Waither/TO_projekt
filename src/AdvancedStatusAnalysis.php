<?php

namespace Monitoring;

class AdvancedStatusAnalysis implements StatusAnalysisStrategy {
    public function analyzeStatus(Device $device) {
        // Zaawansowana analiza statusu (np. sprawdzanie logów, itp.)
        return 'OK';  // Można zmienić na bardziej złożoną analizę
    }
}
