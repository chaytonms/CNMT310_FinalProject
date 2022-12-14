<?php
/*
Page Description: Login page. Default landing page when a user first accesses the site.
*/
session_start();
require_once('../Template.php');
require_once('../User.php');

// Ensures an admin can't naviagte to manageclass.php, then to this page, and then back to manageclass.php
unset($_SESSION['manage']);

$template = new Template("Home");
$defaultUser = new User();

$_SESSION['user'] = json_encode($defaultUser);

$template->addHeadElement('<link href="login.css?" rel="stylesheet">');
print $template->beginHTML();

print '<div class="container-fluid d-flex flex-column flex-md-row">';
print '<main class="ps-0 ps-md-5 flex-grow-1">';
print '<div class="square-box col-md-8 offset-md-2 mx-auto text-center mt-5 border border-maroon rounded d-flex justify-content-center align-items-center">';
print '<form method="post" action="authentication.php">';
print '<h1 class="h3 mb-5">Class Registration - Sign in</h1>';
if (isset($_SESSION['errors'])) {
    foreach ($_SESSION['errors'] as $e) {
        print "<p class=\"error big-text\">$e</p>";
    }
}
print '<input id="username" name="username" class="form-control rounded mt-3" placeholder="Username">';
print '<input type="password" id="password" name="password" class="form-control rounded mt-3" placeholder="Password">';
print '<div class="mt-2">';
print '<button type="submit" name="submitform" class="button btn btn-lg btn-danger btn-block m-1 bg-maroon">Sign In</button>';
print '<a href="dashboard.php" class="button btn btn-lg btn-danger btn-block m-1 w-20">Continue as Guest</a>';
print '</div></form></div></main></div>';
print $template->closeHTML();
unset($_SESSION['errors']);
?>