<?php
    session_start();
    require_once(__DIR__.'/../WebServiceClient.php');
    require_once(__DIR__.'/../const.php');
    var_dump($_SESSION['classesToDrop']);
    // Drop Classes functionality needs to be fixed
/*
    if (!isset($_SESSION, $_SESSION['user'], $_SESSION['user']['user_role'], $_SESSION['user']['id'], $_POST, $_POST['submitform'], $_SESSION['classesToDrop'])) {
        $_SESSION['errors'] = array("Session Error");
        //die(header("Location: dashboard.php"));
    }
    print var_dump($_SESSION) . "<br />";
    print var_dump($_POST);
    $user_id = json_decode($_SESSION['user'])->user_role;

    $url = "http://cnmt310.classconvo.com/classreg/";
    $client = new WebServiceClient($url);

    foreach($_SESSION['classesToDrop'] as $class){
        
    }

    $postData = array("apikey" => APIKEY,
                "apihash" => APIHASH,
                "data" => array("student_id"=>$user_id, "course_id"=>$_SESSION['classesToDrop']),
                "action" => "delstudentfromcourse"
                );


    $client->setPostFields($postData);
    $json = (object) json_decode($client->send());
    print var_dump($json);

    if ($json == null || !isset($json->result) || $json->result != "Success") { // might need more checks
        $_SESSION['errors'] = array("Error with dropping class");

        if ($json->result == "Error") {
            $_SESSION['errors'][] = $json->data->message;
        }

        die(header("Location: dashboard.php"));

    } else { // ik this not needed but since they are linked logically I like to have it

        unset($_SESSION['errors']);
        $_SESSION['successes'] = array("Success dropping a class(es).");
        die(header("Location:dashboard.php"));
    }
*/
?>