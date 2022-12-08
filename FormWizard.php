<?php

class FormWizard {
    // creates the standard inputs HTML string
    public function standardInput($label, $inputName, $inputType="text", $classes="") {
        $form = "<div class=\"row m-0 p-0\"><div class=\"col m-0 p-0\"><p>$label</p></div>";
        $form .= "<div class=\"col m-0 p-0\"><input type=\"$inputType\" ";
        $form .= "class=\"float-end " . $classes . "\" name=\"$inputName\" for=\"$inputName\"/></div></div>\n";
        return $form;
    }
    public function standardSubmit($fallback, $label="Submit") {
        $form = "<div class=\"row m-0\">";
        $form .= "<div class=\"col m-0 p-0\"><input type=\"submit\" name=\"submitform\" value=\"$label\"><br/></div>";
        $form .= "<div class=\"col m-0 p-0\"><a href=\"$fallback\">BACK</a></div></div>";
        return $form;
    }
}
?>
