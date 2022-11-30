<?php
    session_start();

    require_once(__DIR__.'/../WebServiceClient.php');
    require_once("../ValidationWizard.php");
    require_once(__DIR__.'/../const.php');
    $VW = new ValidationWizard();

    if (!isset($_POST) || !isset($_SESSION) || !isset($_POST['submitform'])) {
        $_SESSION['errors'] = array("Session Error");
        die(header("Location: index.php"));
    }

    if (!isset(
        $_SESSION['user'],
        $_SESSION['deleteId'],
    )) {
    
        $_SESSION['errors'] = array("Session Error");
        die(header("Location: index.php"));        
    }

    $course_id = $_SESSION['deleteId'];

    // Call GetStudentListByCourse and foreach remove each Student from course using DeleteStudentFromCourse

    print(var_dump($_SESSION));
    //$course_id = $_POST['id'];

    // make the request to the api
    $url = "http://cnmt310.classconvo.com/classreg/";
    $client = new WebServiceClient($url);
    
    $postData = array("apikey" => APIKEY,
                 "apihash" => APIHASH,
                 "data" => array( "course_id" => $course_id ),
                 "action" => "deletecourse"
                 );
    
    $client->setPostFields($postData);
    $json = (object) json_decode($client->send());

    if ($json == null || !isset($json->result) || $json->result != "Success") { // might need more checks
        $_SESSION['errors'] = array("Error with adding class", json_encode($json));
        die(header("Location: addClass.php"));
    } else { // ik this not needed but since they are linked logically I like to have it
        unset($_SESSION['errors']);
        $_SESSION['successes'] = array("Success adding a class!");
        die(header("Location: dashboard.php"));
    }       
?>