<?php


namespace Source\Controllers;

define('ROOT_DIR', dirname(dirname(__DIR__)) . '/');
define('SRC_DIR', ROOT_DIR . 'src/');
require_once SRC_DIR . 'Model/TransactionStatus.php';
require_once SRC_DIR . 'Controllers/Controller.php';

use Source\Model\TransactionStatus;

class TransactionStatusController implements Controller
{
    private $status_client;

    public function __construct()
    {
        $this->status_client = new TransactionStatus();
    }

    function execute($request)
    {
        $reference_id = $request['reference_id'];
        $gpid = "GPZEN065";
        return $this->parse($this->status_client->execute($gpid, $reference_id));
    }

    function parse($http_response)
    {
        return $http_response;
    }
}
