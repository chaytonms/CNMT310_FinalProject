<?php
session_start();
require_once(__DIR__.'/../SplitPageTemplate.php');
require_once(__DIR__.'/../WebServiceClient.php');
require_once(__DIR__.'/../const.php');
require_once(__DIR__.'/../ValidationWizard.php');

$VW = new ValidationWizard();
$template = new SplitPageTemplate("Enrollment Confirmation");

// Validation
if (!isset($_SESSION) || !isset($_SESSION['user'])) {
    session_error();
}
$user = json_decode($_SESSION['user']);

if (!isset($user->user_role)) {
    session_error();
}
$role = $user->user_role;

if ($role != "student") {
    if($role == "guest"){
        forbidden_error();
    } else {
        $_SESSION['errors'] = array("This is a student function.");
        die(header("Location: dashboard.php"));
    }
    
}

if(!isset($_POST['course_id']) || empty($_POST['course_id'])){
    $_SESSION['errors'] = array("You must select a class before attempting to navigate to this page.");
    die(header("Location: searchresults.php"));
}

// Find class from listcourses by $_POST['course_id]
// I decided to grab the course from list courses because multiple people could attempt to enroll in the course at the same time
// so we want the most up to date information on the course
// In the future it might be a good idea to have a GetCourseByID Webservice
$url = "http://cnmt310.classconvo.com/classreg/";
$client = new WebServiceClient($url);

// Get List Courses
$postData = array("apikey" => APIKEY,
                  "apihash" => APIHASH,
                  "data" => array(),
                  "action" => "listcourses"
                );

$client->setPostFields($postData);
$json = (object) json_decode($client->send());

if($json == null || !isset($json->result) || $json->result != "Success" || !isset($json->data) || count($json->data) <= 0){
    $_SESSION['errors'] = array("There was an error retrieving class information. Please try again.");
    die(header("Location: searchresults.php"));
}

$course;
foreach($json->data as $class){
    if($class->id == $_POST['course_id']){
        $course = $class;
    }
}

if($course == null){
    $_SESSION['errors'] = array("There was an error retrieving class information. Please try again.");
    die(header("Location: searchresults.php"));
}

// PRINT HTML
print $template->beginHTML();
print $template->openMainNavigation($role);
print $template->closeMainNavigation();
print $VW->checkSessionErrors($_SESSION);
print $VW->checkSessionSuccesses($_SESSION);
print '<div class="container-fluid d-flex flex-column flex-md-row">';
print '<main class="ps-0 ps-md-5 flex-grow-1">';
print '<div class="container-fluid mt-1 mb-4">';
print '<h3>Course Enrollment</h3>';
print '<div><form action="submitEnrollClassStudent.php" method="post">';
$coursename = $course->coursecode . " " . $course->coursenum . ": " . $course->coursename;
print "<h5>" . $coursename . "</h5>";
print '<ul><li><p class="h6">Description: ' . $class->coursedesc . '</p></li>';
print '<li><p class="h6">Instructor: ' . $class->courseinstr . '</p></li>';
print '<li><p class="h6">Credits: ' . $class->coursecredits . '</p></li>';
print '<li><p class="h6">Meeting Times: ' . $class->meetingtimes . '</p></li>';
print '<li><p class="h6">Max Enrollment: ' . $class->maxenroll . '</p></li></ul>';
print '<input type="hidden" name="coursename" value="' . $coursename . '">';
print '<input type="hidden" name="student_id" value="' . $user->id . '">';
print '<input type="hidden" name="max" value="' . $class->maxenroll . '">';
print '<button type="submit" name="enroll_id" class="btn btn-danger button" value="' . $course->id . '">Confirm Enrollment</button>';
print '<a href="searchresults.php" class="btn btn-danger button m-2">Cancel</a>';
print '</form></div></div></main></div>';
print $template->closeHTML();
unset($_SESSION['errors']);
?>