<?php
// home page

require_once("SplitPageTemplate.php");
require_once("WebServiceClient.php");

$template = new SplitPageTemplate("Home");

print $template->beginHTML() . $template->openLeftPane();

print "<h4>Testing</h4>" . $template->closeLeftOpenRightPane();

print "
<form method=\"POST\" action=\"authentication.php\">
    Username: <input name=\"username\" id=\"username\"/><br/>
    Password: <input name=\"password\" id=\"password\"/><br/>
    <input type=\"submit\" name=\"submitform\" value=\"Submit\">
</form>
";
print $template->closeRightPane() . $template->closeHTML();
?>