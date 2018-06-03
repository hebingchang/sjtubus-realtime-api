<?php
/**
 * Created by PhpStorm.
 * User: hebingchang
 * Date: 2018/6/1
 * Time: 上午11:06
 */
header('Content-Type: application/json');

require "vendor/autoload.php";

$data = ["userid" => "6243",  "ordertype" => "getbusinfo"];

$bytes = [117, 110, 105, 118, 108, 105, 118, 101];
$iv = implode(array_map("chr", $bytes));

$order = openssl_encrypt(json_encode($data), "des-cbc", "85281581", 0, $iv);

try {
    $curl = new Curl\Curl();
    $curl->post('http://202.120.1.144/campuslifedispatch/WebService.asmx/BusService', array(
        'order' => $order,
    ));

    $xml = simplexml_load_string($curl->response);
    $ret = openssl_decrypt($xml[0], "des-cbc", "85281581", 0, $iv);

    $buses = json_decode(iconv("GB2312", "utf-8", $ret));

    echo json_encode($buses->data->result1);

} catch (ErrorException $e) {
}
