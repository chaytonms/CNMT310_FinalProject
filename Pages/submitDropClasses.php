<?php
    session_start();
    require_once("../FormWizard.php");
    require_once("../ValidationWizard.php");
    require_once("../Template.php");
    require_once(__DIR__.'/../WebServiceClient.php');
    require_once(__DIR__.'/../const.php');

    $url = "http://cnmt310.classconvo.com/classreg/";
    $client = new WebServiceClient($url);

    $action = "listcourses";
    $data = array("apikey" => APIKEY,
             "apihash" => APIHASH,
             "data" => array(),
             "action" => $action
             );
    $client->setPostFields($data);
    $json = (object) json_decode($client->send());

    $client->setPostFields($postData);
    $json = (object) json_decode($client->send());
    var_dump($json);


?>