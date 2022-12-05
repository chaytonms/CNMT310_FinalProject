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

    if (!isset($_POST) || !isset($_POST['course_id'])) {
        $_SESSION['errors'] = array("Select a class to delete.");
        die(header("Location: dashboard.php"));
    }

    // Calling Web Service List Courses to get class information for delete class
    $url = "http://cnmt310.classconvo.com/classreg/";
    $client = new WebServiceClient($url);

    $postData = array("apikey" => APIKEY,
             "apihash" => APIHASH,
             "data" => array(),
             "action" => "listcourses"
             );
    $client->setPostFields($postData);
    $json = (object) json_decode($client->send());

    if ($json == null || !isset($json->result) || $json->result != "Success") {
        $_SESSION['errors'] = array("Error with fetching class.", json_encode($json));
        die(header("Location: dashboard.php"));
    } 

    $classid = $_POST['course_id'];
    $classes = $json->data;

    // Session Variable to use on Submit Delete Class
    $_SESSION['deleteId'] = $_POST['course_id'];

    // Print HTML
    print $template->beginHTML();
    print $template->openMainNavigation($user->user_role);
    print $template->closeMainNavigation();
    print '<div class="container-fluid d-flex flex-column flex-md-row">
    <main class="ps-0 ps-md-5 flex-grow-1">
      <div class="container-fluid mt-1 mb-4">
        <h3>Are you sure you want to delete the following course?</h3>
        <div>
          <form action="submitDeleteClass.php" method="post">
            <ul class="mt-4 mb-4">';
            foreach($classes as $c){
                if ($c->id == $classid) {
                    print "<li><h5>" . $c->coursecode . " " . $c->coursenum . ": " . $c->coursename . "</h5></li>";
                    break;
                }
            }
    print '</ul>
            <div class="mt-2">
              <button type="submit" name="submitform" class="btn btn-danger button" value="confirmed">Confirm Delete</button>
              <a href="dashboard.php" class="btn btn-danger button">Cancel</a>
            </div>
          </form>
        </div>
      </div>
    </main>
  </div>';
    print $template->closeHTML();
?>