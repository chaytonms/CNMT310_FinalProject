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

// check if user is admin
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

if ($user->user_role != "admin") {
    $_SESSION['errors'] = array("Page Forbidden");
    die(header("Location: dashboard.php"));
}

if (!isset($_POST) || !isset($_POST['id'])) {
    $_SESSION['errors'] = array("Select a class to Manage.");
    die(header("Location: dashboard.php"));
}

$name;
if($user->user_role != "guest"){
    if (!isset($user->name)) {
        session_error();
    }
    $name = $user->name; 
}
$role = $user->user_role;

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
    if($course->id == $_POST['id']){
        $class = $course;
    }
}
print $template->beginHTML();
print $template->openMainNavigation($role);
print $template->closeMainNavigation();

print $template->openManageClass($role, $name, $_POST['id']);
print $VW->checkSessionErrors($_SESSION);
print $VW->checkSessionSuccesses($_SESSION);
print '<h4>Course Manager</h4>';
print '<div class="card w-75">
<div class="card-body">
  <h5 class="card-title">' . $class->coursecode . ' ' . $class->coursenum . ': ' . $class->coursename . '</h5>
  <p class="card-text">' . $class->coursedesc . '</p>
  <ul class="navbar-nav w-100 d-flex flex-md-column text-center text-md-end">
    <li>
        <p class="h5">Instructor' . $class->courseinstr . '</p>
    </li>
    <li>
        <p class="h5">Credits' . $class->coursecredits . '</p>
    </li>
    <li>
        <p class="h5">Meeting Times' . $class->meetingtimes . '</p>
    </li>
    <li>
        <p class="h5">Max Enrollment' . $class->maxenroll . '</p>
    </li>
  </ul>
  <div>
    <form action="addstudenttoclass.php" method="post">
    <button class="btn btn-danger button" name="id" value="' . $class->id . '">Add Student To Course</button>
    </form>
    <form action="removestudentfromcourse.php" method="post">
    <button class="btn btn-danger button" name="id" value="' . $class->id . '">Remove Student From Course</button>
    </form>
    <form action="deleteclass.php" method="post">
    <button class="btn btn-danger button" name="id" value="' . $class->id . '">Delete</button>
    </form>
  </div>
</div>
</div>';
print $template->closeDashboard();
?>