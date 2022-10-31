<?php
// home page

require_once("Template.php");
require_once("WebServiceClient.php");

$template = new Template("Home");

print $template->beginHTML();
print "
<form method=\"POST\" action=\"authentication.php\">
    Username<input name=\"username\" id=\"username\"/>
    Password<input name=\"password\" id=\"password\"/>
    <input type=\"submit\" name=\"submitform\" value=\"Submit\">
</form>
";
print $template->closeHTML();
?>