<?php
require_once '../src/Database.php';

use Monitoring\Database;

$db = new Database();

if (isset($_POST['add_device'])) {
    $ip = $_POST['device_ip'];
    $id = $db->addDevice($ip);
    echo json_encode($id);
}
elseif (isset($_POST['delete_device'])) {
    $deviceID = $_POST['device_id'];
    $db->deleteDevice($deviceID);
}