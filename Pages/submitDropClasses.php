<?php
session_start();
require_once(__DIR__.'/../WebServiceClient.php');
require_once(__DIR__.'/../const.php');

function session_error() {
    $_SESSION['errors'] = array("Session Error");
    die(header("Location: index.php"));
}

if (!isset($_SESSION) || !isset($_SESSION['user'])) {
    session_error();
}
$user = json_decode($_SESSION['user']);

if (!isset($user->user_role)) {
    session_error();
}

if ($user->user_role != "student" && $user->user_role != "admin") {
    $_SESSION['errors'] = array("Page Forbidden");
    die(header("Location: dashboard.php"));
}

if($user->user_role != "student"){
    $_SESSION['errors'] = array("Drop classes is a student function.");
    die(header("Location: dashboard.php"));
}

if(!isset($_SESSION['classesToDrop']) || empty($_SESSION['classesToDrop']) || !isset($_POST['submitform']) || empty($_POST['submitform'])){
    $_SESSION['errors'] = array("You must confirm courses to drop before attempting to access this page.");
    die(header("Location: dashboard.php"));
}

$user_id = json_decode($_SESSION['user'])->id;
$url = "http://cnmt310.classconvo.com/classreg/";
$client = new WebServiceClient($url);

// Loop Through Classes to Drop and Drop each Course
foreach($_SESSION['classesToDrop'] as $class){
    $postData = array("apikey" => APIKEY,
                        "apihash" => APIHASH,
                        "data" => array("student_id"=>$user_id, "course_id"=>$class),
                        "action" => "delstudentfromcourse"
                    );
    $client->setPostFields($postData);
    $json = (object) json_decode($client->send());

    if($json == null || !isset($json->result) || $json->result != "Success"){
        $_SESSION['errors'] = array("An error occured while attempting to drop a course. Please try again.");
        if ($json->result == "Error") {
            $_SESSION['errors'][] = $json->data->message;
        }
        die(header("Location: dashboard.php"));  
    } 
}

unset($_SESSION['errors']);
unset($_SESSION['classesToDrop']);
$_SESSION['successes'] = array("Course(s) dropped successfully!");
die(header("Location:dashboard.php"));
?>