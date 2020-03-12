<?php

define('ROOT_DIR', __DIR__ . '/');
define('SRC_DIR', ROOT_DIR . 'src/');
define('VENDOR_DIR', ROOT_DIR . 'vendor/');
define('TEMPLATES_DIR', ROOT_DIR . 'templates/');
require_once SRC_DIR . 'Controllers/TransactionStatusController.php';
require_once VENDOR_DIR . 'smarty/smarty/libs/Smarty.class.php';

use Source\Controllers\TransactionStatusController;


$request = [
    'reference_id' => $_GET['ref']
];
$controller = new TransactionStatusController();
$http_response = $controller->execute($request);
$response = json_decode($http_response, TRUE);

$smarty = new Smarty();
$status = false;
$datetime = "";
$description = "";
$reference_id = "";
if (isset($response['status'])) {
    $status = $response['status'];
}

if (isset($response['dateTime'])) {
    $datetime = $response['dateTime'];
}

if (isset($response['description'])) {
    $description = $response['description'];
}

if (isset($response['refID'])) {
    $reference_id = $response['refID'];
}

$smarty->assign('datetime', $datetime);
if ($status) {
    $smarty->assign('paystatus', 'SUCCESS');
    $smarty->assign('color', 'success');
} else {
    $smarty->assign('paystatus', 'PENDING');
    $smarty->assign('color', 'warning');
}
$smarty->assign('description', $description);
$smarty->assign('reference_id', $reference_id);
$smarty->display(TEMPLATES_DIR . 'status.tpl.html');
