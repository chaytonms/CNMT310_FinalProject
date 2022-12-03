<?php
// home page
session_start();
require_once('../Template.php');
require_once('../User.php');

$template = new Template("Home");
$defaultUser = new User();

$_SESSION['user'] = json_encode($defaultUser);

$template->addHeadElement('<link href="login.css?" rel="stylesheet">');
print $template->beginHTML();

print '<div class="container-fluid d-flex flex-column flex-md-row">
<main class="ps-0 ps-md-5 flex-grow-1">
    <div class="square-box col-md-8 offset-md-2 mx-auto text-center mt-5 border border-maroon rounded  d-flex justify-content-center align-items-center">
    <form method="post" action="authentication.php">
    <h1 class="h3 mb-5">Class Registration - Sign in</h1>';
    if (isset($_SESSION['errors'])) {
        foreach ($_SESSION['errors'] as $e) {
            print "<p class=\"error big-text\">$e</p>";
        }
    }
print '<input id="username" name="username" class="form-control rounded mt-3" placeholder="Username">
        <input type="password" id="password" name="password" class="form-control rounded mt-3" placeholder="Password">
        <div class="mt-2">
          <button type="submit" name="submitform" class="button btn btn-lg btn-danger btn-block mt-2 bg-maroon">Sign In</button>
          <a href="dashboard.php" class="button btn btn-lg btn-danger btn-block mt-2 w-20">Continue as Guest</a>
        </div>
      </form>
    </div>
  </main>
</div>';

print $template->closeHTML();
unset($_SESSION['errors']);
?>