<?php
// THIS PAGE WILL BE REMOVED WHEN WE TURN IN FINAL PROJECT
// THIS PAGE EXISTS TO ENSURE THERE IS TEST DATA
// IF STUDENT - IT WILL POTENTIALLY ADD AND ENROLL THE STUDENT IN 4 CLASSES (IF CLASS EXISTS ALREADY IT WON'T ADD, IF STUDENT IS ALREADUY ENROLLED IT WON't ENROLL)
// IF ADMIN - IT WILL POTENTIALLY ADD UP TO 4 CLASSES (IF CLASS EXISTS ALREADY IT WON'T ADD)
// **NOTE** - IF YOU CHANGE THE NAME OF ANY OF THESE CLASSES AND RERUN THIS PAGE - IT WILL ADD A NEW CLASS
// IF YOU WANT TO TEST EDITING A CLASS - DO NOT EDIT THE CLASS NAME UNLESS YOU WANT DUPLICATES :D (TECHNICALLY DIFFERENT COURSE ID, BUT ALL OTHER INFO WE BE SAME)
session_start();
require_once(__DIR__.'/../WebServiceClient.php');
require_once(__DIR__.'/../const.php');

function session_error() {
    $_SESSION['errors'] = array("Session Error");
    //print var_dump($_SESSION);
    die(header("Location: index.php"));
}

if (!isset($_SESSION) || !isset($_SESSION['user'])) {
    session_error();
}
$user = json_decode($_SESSION['user']);

if (!isset($user->user_role)) {
    session_error();
}

if (!isset($user->id)) {
    session_error();
}

$url = "http://cnmt310.classconvo.com/classreg/";
$client = new WebServiceClient($url);

// GRABBING CURRENT COURSES
$action = "listcourses";
$data = array("apikey" => APIKEY,
             "apihash" => APIHASH,
             "data" => array(),
             "action" => $action
             );

$client->setMethod("GET");
$client->setPostFields($data);
$json = (object) json_decode($client->send());
$allCourses = $json->data;

// COURSES TO BE ADDED
$newClasses = array(
          array("coursename" => "Cybersecurity and Society",
                "coursecode" => "CIS",
                "coursenum" => "303",
                "coursecredits" => "3",
                "coursedesc" => "Cybersecurity is a human problem",
                "courseinstr" => "Johnson",
                "meetingtimes" => "MW 2:00p-3:15p",
                "maxenroll" => "20"),
          array("coursename" => "Interactive Web Programming",
                "coursecode" => "CIS",
                "coursenum" => "341",
                "coursecredits" => "4",
                "coursedesc" => "Learn ASP.NET Core",
                "courseinstr" => "Heimonen",
                "meetingtimes" => "MW 4:00p-5:00p",
                "maxenroll" => "22"),
          array("coursename" => "Professional IT Communication",
                "coursecode" => "CNMT",
                "coursenum" => "410",
                "coursecredits" => "4",
                "coursedesc" => "Learn technical communication",
                "courseinstr" => "Suehring",
                "meetingtimes" => "M 8:00a-9:50a",
                "maxenroll" => "22"),
          array("coursename" => "Production Programming",
                "coursecode" => "CNMT",
                "coursenum" => "310",
                "coursecredits" => "4",
                "coursedesc" => "Learning things about the Internet",
                "courseinstr" => "Suehring",
                "meetingtimes" => "MW 11:00a-12:50p",
                "maxenroll" => "24"));

// Light Protection so that you don't add the same class multiple times
// If you change the name of the class and then navigate to this page, it will add the class to the database
$classNames = array();
foreach($allCourses as $existing){
    $classNames[] = $existing->coursename;
}

foreach($newClasses as $class){
    if(!in_array($class["coursename"], $classNames)){
        $action = "addcourse";
        $data = array("apikey" => APIKEY,
             "apihash" => APIHASH,
             "data" => $class,
             "action" => $action
             );
        $client->setMethod("GET");
        $client->setPostFields($data);
        $json = (object) json_decode($client->send());
    }
}

// If user is admin it won't attempt to add them to the courses
$user->user_role;
if($user->user_role === "admin"){
    die(header("Location: dashboard.php"));
}


// Grab All Courses 
$action = "listcourses";
$data = array("apikey" => APIKEY,
             "apihash" => APIHASH,
             "data" => array(),
             "action" => $action
             );

$client->setMethod("GET");
$client->setPostFields($data);
$json = (object) json_decode($client->send());
$allCourses = $json->data;


// Grab all of the Students Courses
$action = "getstudentcourses";
$data = array("apikey" => APIKEY,
             "apihash" => APIHASH,
             "data" => $user->id,
             "action" => $action
             );

$client->setMethod("GET");
$client->setPostFields($data);
$json = (object) json_decode($client->send());
$studentCourses = $json->data;


// Enroll Student in Courses they are not already enrolled in
$courseIDs = array();
foreach($studentCourses as $course){
    $courseIDs[] = $course->course_id;
}

foreach($allCourses as $course){
    if(!in_array($course->course_id, $courseIDs)){
        $action = "addstudent2course";
        $data = array("apikey" => APIKEY,
                "apihash" => APIHASH,
                "data" => array("student_id" => $user->id,
                                "course_id" => $course->course_id),
                                "action" => $action
             );
        $client->setMethod("POST");
        $client->setPostFields($data);
        $json = (object) json_decode($client->send());
    }
}

// REDIRECTS TO DASHBOARD
die(header("Location: dashboard.php"));
?>