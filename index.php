<?php
// home page
session_start();
require_once("Template.php");
require_once("WebServiceClient.php");

$template = new Template("Home");

$_SESSION['role'] = 'guest';


print $template->beginHTML();

print "
<div class=\"hazy\"> 
    <div class=\"white-overlay align-items-center mt\">
    <h4> Welcome! Sign in </h4>";

if (isset($_SESSION['errors'])) {
    foreach ($_SESSION['errors'] as $e) {
        print "<p class=\"error\">$e</p>";
    }
}
print "
        <div>
            <form method=\"POST\" action=\"authentication.php\">
                <div class=\"container\"> <div class=\"row\">
                <div class=\"col-6 float-left\">
                    <p>Username:</p>
                    <p>Password: </p>
                    <input type=\"submit\" name=\"submitform\" value=\"Submit\">
                </div>
                <div class=\"col-6\">
                    <input name=\"username\" id=\"username\"/><br/>
                    <input name=\"password\" id=\"password\"/><br/>
                </div>

                </div></div>
            </form>
            <a href=\"dashboard.php\">Continue as Guest</a> 
        </div>
    </div>
</div>
";

print $template->closeHTML();
?>