<?php
session_start();
require_once(__DIR__.'/../SplitPageTemplate.php');
require_once(__DIR__.'/../TableTemplate.php');
require_once(__DIR__.'/../WebServiceClient.php');
require_once(__DIR__.'/../const.php');
require_once(__DIR__.'/../ValidationWizard.php');

$VW = new ValidationWizard();
$table = new TableTemplate();

$url = "http://cnmt310.classconvo.com/classreg/";
$client = new WebServiceClient($url);
$client->setMethod("GET");

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
$role = $user->user_role;

$name;
if($user->user_role != "guest"){
    if (!isset($user->name)) {
        session_error();
    }
    $name = $user->name; 
}

$template = new SplitPageTemplate("Auth");

print $template->beginHTML();
print $template->openMainNavigation($role);
print $template->closeMainNavigation();

$table = new TableTemplate();
$url = "http://cnmt310.classconvo.com/classreg/";
$client = new WebServiceClient($url);
$client->setMethod("GET");
if($role === "admin"){
    print $template->openAdminDashboard($role, $name);
    print $VW->checkSessionErrors($_SESSION);
    print $VW->checkSessionSuccesses($_SESSION);
    print '<h4>Course List</h4>';
    $action = "listcourses";
    $data = array("apikey" => APIKEY,
             "apihash" => APIHASH,
             "data" => array(),
             "action" => $action
             );
    $client->setPostFields($data);
    $json = (object) json_decode($client->send());
    if($json->result === "Success" && isset($json->data)){
        if(sizeof($json->data) == 0){
            print '<p> There are currently no classes.</p>';
        }else{
            print $table->createAdminDashboardClassTable($json->data);
        }
    }
    print $template->closeDashboard();
} else if($role === "student"){
    print $template->openStudentDashboard($role, $name);
    print $VW->checkSessionErrors($_SESSION);
    print $VW->checkSessionSuccesses($_SESSION);
    print '<h4>Enrolled Coures</h4>';
    $action = "getstudentcourses";
    $data = array("apikey" => APIKEY,
             "apihash" => APIHASH,
             "data" => array("student_id" => $user->id),
             "action" => $action
             );
    $client->setPostFields($data);
    $json = (object) json_decode($client->send());
    if($json->result === "Success" && isset($json->data)){
        if(sizeof($json->data) == 0){
            print '<p>You currently are not enrolled in any courses.</p>';
        }else{
            print $table->createStudentDashboardClassTable($json->data, true);
        }
    }
    print $template->closeDashboard();
} else if($role === "guest"){
    print $template->openGuestDashboard($role);
    print $VW->checkSessionErrors($_SESSION);
    print $VW->checkSessionSuccesses($_SESSION);
    print '<h4>Available Courses</h4>';
    $action = "listcourses";
    $data = array("apikey" => APIKEY,
             "apihash" => APIHASH,
             "data" => array(),
             "action" => $action
             );
    $client->setPostFields($data);
    $json = (object) json_decode($client->send());
    if($json->result === "Success" && isset($json->data)){
        if(sizeof($json->data) == 0){
            print '<p>There are currently no classes available.</p>';
        }else{
            print $table->createStudentClassSearchTables($json->data, false);
        }
    }
    print $template->closeDashboard();
}
print $template->closeHTML();
unset($_SESSION['errors']);
unset($_SESSION['successes']);
?>