<?php
    require_once '../src/Device.php';
    require_once '../src/Server.php';
    require_once '../src/Router.php';
    require_once '../src/Switch.php';
    require_once '../src/Monitor.php';
    require_once '../src/Alert.php';
    require_once '../src/ObserverInterface.php';
    require_once '../src/Subject.php';
    require_once '../src/AlertLogger.php';
    require_once '../src/AlertNotifier.php';
    require_once '../src/Log.php';
    require_once '../src/ConfigurationManager.php';
    require_once '../src/StatusAnalysisStrategy.php';
    require_once '../src/SimpleStatusAnalysis.php';
    require_once '../src/AdvancedStatusAnalysis.php';
    require_once '../src/Controller.php';
    require_once '../src/View.php';
    require_once '../src/DeviceFactory.php';
    require_once '../src/Database.php';

    use Monitoring\Controller;
    use Monitoring\View;
    use Monitoring\AlertLogger;
    use Monitoring\AlertNotifier;
    use Monitoring\Database;

    $db = new Database();
    $devices = $db->getAllDevices();

    $controller = new Controller();

    foreach ($devices as $device) {
        $device = (object)$device;
        $controller->addDevice($device);
    }

    $controller->attach(new AlertLogger());
    $controller->attach(new AlertNotifier());

    // Ustawienie strategii analizy statusu na 'simple' lub 'advanced'
    $strategy = $_COOKIE['monitoring_strategy'] ?? 'simple';
    $controller->setMonitorStrategy($strategy);

    $controller->monitorDevices();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Device Tracker</title>
    <link rel="stylesheet" href="style.css">
    <script src="main.js"></script>
</head>
<body>

<?php
    
    // Wyświetlanie wyników na stronie
    $view = new View();
    $view->renderDeviceStatus($controller->getDevices());
    $view->renderAlerts($controller->getAlerts());
?>

</body>
</html>
