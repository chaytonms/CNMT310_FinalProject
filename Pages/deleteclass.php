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

    if ((!isset($_POST) || !isset($_POST['id'])) && (!isset($_SESSION['manage']) || !isset($_SESSION['manage']['id']) || !isset($_SESSION['manage']['name']))) {
        $_SESSION['errors'] = array("Select a class to delete.");
        die(header("Location: dashboard.php"));
    }

    $classid;
    // At this point we know that $_SESSION['manage'] isset, but the logic is to handle the situation where
    // a user navigates to manageclass.php and then alters the url to deleteclass.php. In this case only $_SESSION['manage'] would be set.
    // If they click the delete button on manageclass.php, $_POST['id'] will bet set and used.
    if((!isset($_POST['id']) || empty($_POST['id']))){
      $classid = $_SESSION['manage']['id'];
    } else {
      $classid = $_POST['id'];
    }

    // Print HTML
    print $template->beginHTML();
    print $template->openMainNavigation($user->user_role);
    print $template->closeMainNavigation();
    print $VW->checkSessionErrors($_SESSION);
    print $VW->checkSessionSuccesses($_SESSION);
    print '<div class="container-fluid d-flex flex-column flex-md-row">
    <main class="ps-0 ps-md-5 flex-grow-1">
      <div class="container-fluid mt-1 mb-4">
        <h3>Are you sure you want to delete the following course?</h3>
        <div>
          <form action="submitDeleteClass.php" method="post">
            <ul class="mt-4 mb-4">';
    print "<li><h5>" . $_SESSION['manage']['name'] . "</h5></li>";
    print '</ul>
            <div class="mt-2">
              <button type="submit" name="submitform" class="btn btn-danger button" value="' . $classid . '">Confirm Delete</button>
              <a href="manageclass.php" class="btn btn-danger button">Cancel</a>
            </div>
          </form>
        </div>
      </div>
    </main>
  </div>';
    print $template->closeHTML();
    unset($_SESSION['errors']);
?>