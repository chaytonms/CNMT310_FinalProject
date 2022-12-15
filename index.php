<?php
/*
Page Description: Redirect in case a user attempts to navigate to a page outside of /Pages directory.
*/
die(header("Location: Pages/index.php"));
?>