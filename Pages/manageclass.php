<?php
session_start();
require_once("../FormWizard.php");
require_once("../ValidationWizard.php");
require_once(__DIR__.'/../SplitPageTemplate.php');
require_once(__DIR__.'/../const.php');
require_once(__DIR__.'/../WebServiceClient.php');

$FW = new FormWizard();
$VW = new ValidationWizard();
$template = new SplitPageTemplate("Delete Class Confirmation");

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

// If POST['id'] and $_SESSION['manage'] are both not set that means they have never selected Manage Course on Dashboard
// I unset $_SESSION['manage'] on dashboard.php, submitDeleteClass.php, submitEnrollClass.php, and submitUnenroll.php
// This ensure that if they ever confirm an action they will have to access manageclass.php through the use of the button on the table
if ((!isset($_POST) || !isset($_POST['id'])) && !isset($_SESSION['manage'])) {
    $_SESSION['errors'] = array("Select a class to Manage.");
    die(header("Location: dashboard.php"));
}

if (!isset($user->name)) {
    session_error();
}
$name = $user->name; 
$role = $user->user_role;

// Save post to session so we can use redirect back to this page
// If manage is not set then set it, else continue
if(!isset($_SESSION['manage']) || empty($_SESSION['manage']) || !isset($_SESSION['manage']['id']) || !isset($_SESSION['manage']['name'])){
    $_SESSION['manage']['id'] = $_POST['id'];
}

$template = new SplitPageTemplate("Manage Course");
$url = "http://cnmt310.classconvo.com/classreg/";
$client = new WebServiceClient($url);
$client->setMethod("GET");

$data = array("apikey" => APIKEY,
    "apihash" => APIHASH,
    "data" => array(),
    "action" => "listcourses"
);

$client->setPostFields($data);
$json = (object) json_decode($client->send());
if($json == null || !isset($json->result) || $json->result != "Success"){
    $_SESSION['errors'] = array("An error occured while retrieving the class.");
    die(header("Location: dashboard.php"));
}

$class;
foreach($json->data as $course){
    if($course->id == $_SESSION['manage']['id']){
        $class = $course;
    }
}

// Set class name and max enrollment in session to be resued on other pages
$_SESSION['manage']['name'] = $class->coursecode . " " . $class->coursenum . ": " . $class->coursename;
$_SESSION['manage']['max'] = $class->maxenroll;

// PRINT HTML
print $template->beginHTML();
print $template->openMainNavigation($role);
print $template->closeMainNavigation();
print $template->openManageClass($role, $name);
print $VW->checkSessionErrors($_SESSION);
print $VW->checkSessionSuccesses($_SESSION);
print '<h4>Course Manager</h4>';
print '<div class="d-flex flex-column">';
print '<div class="d-flex flex-column">';
print '<h5 class="m-2">' . $_SESSION['manage']['name'] . '</h5>';
print '<ul class="m-2">';
print '<li><p class="h6">Description: ' . $class->coursedesc . '</p></li>';
print '<li><p class="h6">Instructor: ' . $class->courseinstr . '</p></li>';
print '<li><p class="h6">Credits: ' . $class->coursecredits . '</p></li>';
print '<li><p class="h6">Meeting Times: ' . $class->meetingtimes . '</p></li>';
print '<li><p class="h6">Max Enrollment: ' . $class->maxenroll . '</p></li></ul>';
print '<div class="d-flex flex-column">';
print '<form class="m-2" action="addstudenttoclass.php" method="post">';
print '<button class="btn btn-danger button" name="id" value="' . $class->id . '">Add Student To Course</button></form>';
print '<form class="m-2" action="removestudentfromclass.php" method="post">';
print '<button class="btn btn-danger button" name="id" value="' . $class->id . '">Remove Student From Course</button></form>';
print '<form class="m-2" action="deleteclass.php" method="post">';
print '<button class="btn btn-danger button" name="id" value="' . $class->id . '">Delete</button></form>';
print '</div></div></div>';
print $template->closeDashboard();
print $template->closeHTML();
unset($_SESSION['errors']);
?>