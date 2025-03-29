<?php
    require_once '../src/Device.php';
    require_once '../src/Server.php';
    require_once '../src/Router.php';
    require_once '../src/Switch.php';
    require_once '../src/Monitor.php';
    require_once '../src/Alert.php';
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
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring urządzeń</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php
    $controller = new Controller();
    $controller->addDevice('server', 'Server 1', '192.168.1.3', ['services' => ['HTTP', 'FTP']]);
    $controller->addDevice('router', 'Router 1', '192.168.1.1', ['routingProtocol' => 'OSPF']);
    $controller->addDevice('switch', 'Switch 1', '192.168.1.2', ['ports' => 48]);
    $controller->addDevice('server', 'Server 2', '192.168.1.4', ['services' => ['SSH', 'MySQL']]);
    $controller->addDevice('router', 'Router 2', '192.168.1.5', ['routingProtocol' => 'BGP']);
    $controller->addDevice('switch', 'Switch 2', '192.168.1.6', ['ports' => 24]);
    $controller->addDevice('server', 'Server 3', '192.168.1.7', ['services' => ['DNS', 'SMTP']]);
    $controller->addDevice('router', 'Router 3', '192.168.1.8', ['routingProtocol' => 'EIGRP']);
    $controller->addDevice('switch', 'Switch 3', '192.168.1.9', ['ports' => 12]);

    // Ustawienie strategii analizy statusu na 'simple'
    $controller->setMonitorStrategy('simple');

    // Monitorowanie urządzeń
    $controller->monitorDevices();

    // Wyświetlanie wyników na stronie
    $view = new View();
    $view->renderDeviceStatus($controller->getDevices());
    $view->renderAlerts($controller->getAlerts());
?>

</body>
</html>