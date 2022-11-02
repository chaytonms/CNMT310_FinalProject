<?php
// home page
session_start();
require_once("SplitPageTemplate.php");
require_once("WebServiceClient.php");

$template = new SplitPageTemplate("Home");

$_SESSION['role'] = 'guest';


print $template->beginHTML() . $template->openLeftPane();

print "<h4>Testing</h4>";

if (isset($_SESSION['errors'])) {
    foreach ($_SESSION['errors'] as $e) {
        print "<p class=\"error\">$e</p>";
    }
}

print $template->closeLeftOpenRightPane();

print "
<form method=\"POST\" action=\"authentication.php\">
    Username: <input name=\"username\" id=\"username\"/><br/>
    Password: <input name=\"password\" id=\"password\"/><br/>
    <input type=\"submit\" name=\"submitform\" value=\"Submit\">
</form>
<a href=\"dashboard.php\">Continue as Guest</a>";
print $template->closeRightPane() . $template->closeHTML();
?>