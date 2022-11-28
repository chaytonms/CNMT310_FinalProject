<?php
    session_start();

    require_once(__DIR__.'/../WebServiceClient.php');
    require_once("../ValidationWizard.php");
    $VW = new ValidationWizard();

    if (!isset($_POST) || !isset($_SESSION) || !isset($_POST['submitform'])) {
        $_SESSION['errors'] = array("Session Error");
        die(header("Location: index.php"));
    }

    if (!isset(
        $_SESSION['apihash'],
        $_SESSION['apikey'],
        $_SESSION['user'],
        $_SESSION['deleteId'],
    )) {
    
        $_SESSION['errors'] = array("Session Error");
        die(header("Location: index.php"));        
    }

    $course_id = $_SESSION['deleteId'];

    print(var_dump($_SESSION));
    //$course_id = $_POST['id'];

    // make the request to the api
    $url = "http://cnmt310.classconvo.com/classreg/";
    $client = new WebServiceClient($url);
    
    $postData = array("apikey" => $_SESSION['apikey'],
                 "apihash" => $_SESSION['apihash'],
                 "data" => array( "course_id" => $course_id ),
                 "action" => "deletecourse"
                 );
    
    $client->setPostFields($postData);
    $json = (object) json_decode($client->send());
    print var_dump($json);

?>