<?php
require_once(__DIR__.'/../WebServiceClient.php');
require_once(__DIR__.'/../const.php');
require_once(__DIR__ . '/../ValidationWizard.php');

session_start();

if (!isset($_SESSION) || !isset($_SESSION['user'])) {
    session_error();
}
$user = json_decode($_SESSION['user']);

if (!isset($user->user_role)) {
    session_error();
}

if ($user->user_role != "admin") {
    forbidden_error();
}

if(!isset($_POST['submitform']) || empty($_POST['submitform'])){
    $_SESSION['errors'] = array("Please confirm deletion before attempting to navigate to this page.");
    die(header("Location: deleteclass.php"));
}

$course_id = $_POST['submitform'];

$url = "http://cnmt310.classconvo.com/classreg/";
$client = new WebServiceClient($url);

// Get Students for Course that will be Deleted
$postData = array("apikey" => APIKEY,
                 "apihash" => APIHASH,
                 "data" => array( "course_id" => $course_id ),
                 "action" => "getstudentlistbycourse"
                 );

$client->setPostFields($postData);
$json = (object) json_decode($client->send());

// Has Enrollment will be used to determine if I need to remove any students from the class
$hasEnrollment = true;
// Ask Professor About This Validation
if($json == null || !isset($json->result) || $json->result != "Success"){
    if((!is_array($json->data) && !isset($json->data->message)) || $json->data->message != "No students found"){
        deletion_error();
        if ($json->result == "Error") {
            $_SESSION['errors'][] = $json->data->message;
        }
        die(header("Location: dashboard.php"));
    }
    $hasEnrollment = false;  
}

// If there are students enrolled in the course - remove them
if($hasEnrollment){
    $students = $json->data;
    foreach ($students as $s) {
        $postData = array("apikey" => APIKEY,
                          "apihash" => APIHASH,
                          "data" => array( "course_id" => $course_id, "student_id"=> $s->student_id ),
                          "action" => "delstudentfromcourse"
                        );
        $client->setPostFields($postData);
        $json = (object) json_decode($client->send());

        if (!isset($json, $json->result) || $json->result != "Success") {
            deletion_error();
        }
    }
}
// Delete the course
$postData = array("apikey" => APIKEY,
                 "apihash" => APIHASH,
                 "data" => array( "course_id" => $course_id ),
                 "action" => "deletecourse"
                 );
$client->setPostFields($postData);
$json = (object) json_decode($client->send());
if ($json == null || !isset($json->result) || $json->result != "Success") {
    deletion_error();
}

unset($_SESSION['errors']);
unset($_SESSION['manage']);
$_SESSION['successes'] = array("Successfully deleted the class!");
die(header("Location: dashboard.php"));
?>