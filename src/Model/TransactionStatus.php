<?php


namespace Source\Model;

use HttpRequest;
use HttpException;

class TransactionStatus
{
    private $URL = 'https://www.zenithbank.com.gh/api.globalpay/Service/confirmTransaction';

    public function execute($gpid, $reference_id)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->URL . "?ref=" . $reference_id . "&gpid=" . $gpid,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/x-www-form-urlencoded"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        if ($err == null) {
            return $response;
        } else {
            return array();
        }
    }
}
