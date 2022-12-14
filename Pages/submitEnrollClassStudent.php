<?php
session_start();
require_once(__DIR__.'/../WebServiceClient.php');
require_once(__DIR__.'/../const.php');
require_once(__DIR__.'/../ValidationWizard.php');

// Validation
if (!isset($_SESSION) || !isset($_SESSION['user'])) {
    session_error();
}
$user = json_decode($_SESSION['user']);

if (!isset($user->user_role)) {
    session_error();
}

if ($user->user_role != "student") {
    if($user->user_role == "guest"){
        forbidden_error();
    } else {
        $_SESSION['errors'] = array("This is a student function.");
    }
    die(header("Location: dashboard.php"));
}

if(!isset($_POST['student_id']) || empty($_POST['student_id']) || !isset($_POST['enroll_id']) 
|| empty($_POST['enroll_id']) || !isset($_POST['coursename']) || empty($_POST['coursename'])
|| !isset($_POST['max']) || empty($_POST['max'])){
    $_SESSION['errors'] = array("Please confirm enrollment to the class before attempting to navigate to this page.");
    die(header("Location: classconfirm.php"));
}

$max = $_POST['max'];
$coursename = $_POST['coursename'];
$course_id = $_POST['enroll_id'];
$student_id = $_POST['student_id'];
$url = "http://cnmt310.classconvo.com/classreg/";
$client = new WebServiceClient($url);

// Get Student Count
$postData = array("apikey" => APIKEY,
                  "apihash" => APIHASH,
                  "data" => array( "course_id" => $course_id ),
                  "action" => "getstudentlistbycourse"
                );

$client->setPostFields($postData);
$json = (object) json_decode($client->send());

if($json == null || !isset($json->result) || !isset($json->data) || $json->result != "Success"){
    if(!isset($json->data) && !isset($json->data->message) && $json->result != "Error"){
        $_SESSION['errors'] = array("There was an error processing your request.");
        die(header("Location: manageclass.php"));
    }
}

// Check if there is room in course before attempting to add student to the course
$currentEnrolled;
if($json->result != "Success"){
    $currentEnrolled = 0;
} else {
    $currentEnrolled = count($json->data);
}

if($currentEnrolled + 1 > $max){
    $_SESSION['errors'] = array("Unable to Enroll in $coursename - Course Currently Full.");
    die(header("Location: dashboard.php"));
}

// Add Student To Course
$postData = array("apikey" => APIKEY,
                 "apihash" => APIHASH,
                 "data" => array( "course_id" => $course_id,
                                  "student_id" => $student_id),
                 "action" => "addstudent2course"
                 );

$client->setPostFields($postData);
$json = (object) json_decode($client->send());

if($json == null || !isset($json->result) || $json->result != "Success"){
    if (isset($json->data) && isset($json->data->message) && $json->result == "Error") {
        $_SESSION['errors'][] = $json->data->message;
        die(header("Location: classconfirm.php"));
    }
    $_SESSION['errors'] = array("There was an error processing your request.");
    die(header("Location: dashboard.php"));
}
$_SESSION['successes'] = array("You have successfully enrolled in $coursename");
unset($_SESSION['errors']);
die(header("Location: dashboard.php"));
?>