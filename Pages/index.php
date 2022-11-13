<?php
// home page
session_start();
require_once(__DIR__.'/../Template.php');

$template = new Template("Home");

$_SESSION['role'] = 'guest';


print $template->beginHTML();

print "
<div class=\"hazy\"> 
    <div class=\"white-overlay align-items-center mt\">
    <div class=\"align-items-center align-self-center justify-content-center\">
        <h2 class=\"d-flex justify-content-center\"> Welcome! Sign in </h4>";

if (isset($_SESSION['errors'])) {
    foreach ($_SESSION['errors'] as $e) {
        print "<p class=\"error big-text\">$e</p>";
    }
}
print "
        
            <form method=\"POST\" action=\"authentication.php\">
                <div class=\"container-fluid\"> 
                    <div class=\"row mt-2 g-2\">
                        <div class=\"col d-block float-left\">
                            <p class=\"big-text\">Username:</p>
                        </div>
                        <div class=\"col d-block float-right\">
                            <input class=\"d-flex float-right\" name=\"username\" id=\"username\"/>
                        </div>
                    </div>
                    <div class=\"row mt-2 g-2\">
                        <div class=\"col d-block float-left\">
                            <p class=\"big-text\">Password: </p>
                        </div>
                        <div class=\"col d-block float-right\">
                            <input class=\"d-flex float-right\" name=\"password\" id=\"password\"/><br/>
                        </div>
                    </div>
                    <div class=\"row mt-2 g-2\">
                        <div class=\"col justify-content-right\">

                            <input class=\"big-text\" type=\"submit\" name=\"submitform\" value=\"Submit\">
                        </div>

                    </div>
                </div>
                <a class=\"d-flex justify-content-center float-bottom\" href=\"dashboard.php\">Continue as Guest</a> 
            </form>
        </div>
    </div>
</div>
";

print $template->closeHTML();
?>