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

    use Monitoring\Controller;
    use Monitoring\View;
    use Monitoring\AlertLogger;
    use Monitoring\AlertNotifier;

    $controller = new Controller();

    $controller->attach(new AlertLogger());
    $controller->attach(new AlertNotifier());

    $controller->addDevice('server', 'Server 1', '192.168.1.3', ['services' => ['HTTP', 'FTP']]);
    $controller->addDevice('router', 'Router 1', '192.168.1.1');
    $controller->addDevice('switch', 'Switch 1', '192.168.1.2', 48);
    $controller->addDevice('server', 'Server 2', '192.168.1.4', ['services' => ['SSH', 'MySQL']]);
    $controller->addDevice('router', 'Router 2', '192.168.1.5', ['routingProtocol' => 'BGP']);
    $controller->addDevice('switch', 'Switch 2', '192.168.1.6');
    $controller->addDevice('server', 'Server 3', '192.168.1.7', ['services' => ['DNS', 'SMTP']]);
    $controller->addDevice('router', 'Router 3', '192.168.1.8', ['routingProtocol' => 'EIGRP']);
    $controller->addDevice('switch', 'Switch 3', '192.168.1.9', 12);
    $controller->addDevice('server', 'Localhost Server', '127.0.0.1', ['services' => ['IIS'], 'usage' => ['cpu' => 10, 'ram' => 76, 'disk' => 56]]);

    // Test dodawania urządzeń z niepoprawnym typem
    $controller->addDevice('router2', 'Router 4', '192.168.1.10', ['routingProtocol' => 'EIGRP']);

    // Test konfiguracji urządzeń
    $controller->configureDevice('Server 1', 'OK');
    $controller->configureDevice('Server 2', 'NOK');
    $controller->configureDevice('Router 1', 'NOK');

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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
            location.reload();
            }, 60000); // 1 minuta
        });
    </script>
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
