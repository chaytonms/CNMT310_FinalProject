<?php

class FormWizard {

    // creates the standard inputs HTML string
    public function standardInput($label, $inputName, $inputType="text", $classes="") {
        return "<div class=\"row m-0 p-0\">
        <div class=\"col m-0 p-0\">
            <p>$label</p>
        </div>
        <div class=\"col m-0 p-0\">
            <input type=\"$inputType\" class=\"float-end " . $classes . "\" name=\"$inputName\" for=\"$inputName\"/>
        </div>
    </div>\n";
    }

    public function standardSubmit($fallback, $label="Submit") {
        return "<div class=\"row m-0\">
            <div class=\"col m-0 p-0\">
                <input type=\"submit\" name=\"submitform\" value=\"$label\"><br/>
            </div>
            <div class=\"col m-0 p-0\">
                <a href=\"$fallback\">BACK</a>
            </div>
        </div>";
    }


}



?>
