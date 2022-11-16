<?php

class FormWizard {

    public function standardInput($label, $inputName, $inputType="text") {
        return "<div class=\"row m-0 p-0\">
        <div class=\"col m-0 p-0\">
            <p>$label</p>
        </div>
        <div class=\"col m-0 p-0\">
            <input type=\"$inputType\" class=\"float-end\" name=\"$inputName\" for=\"$inputName\"/>
        </div>
    </div>\n";
    }


}



?>
