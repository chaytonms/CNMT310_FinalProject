<?php 

class ValidationWizard {

    // returns array of items not set
    public function AreSet (array $array) {
        $notSet = array();
        foreach ($array as $item) {
            if (!isset($item)) {
                $notSet[] = $item;
            }
        }
        return $notSet;
    }

    // formats the errors into a p tag with error class.
    public function formatErrors (array $errorArray) {
        $htmlString = "";
        foreach ($errorArray as $e) {
            $htmlString .= "<p class=\"error big-text\">$e</p>\n";
        }
        return $htmlString;
    }

    // formats the successes into a p tag with success class.
    public function formatSuccesses (array $successArray) {
        $htmlString = "";
        foreach ($successArray as $s) {
            $htmlString .= "<p class=\"success big-text\">$s</p>\n";
        }
        return $htmlString;
    }

    // checks if the provided session has the errors fields, returns them formatted, and optionally unset the errors after
    public function checkSessionErrors ($Session, bool $removeErrors = true) {
        $output = "";
        if (isset($Session['errors'])) {
            $output = $this->formatErrors($Session['errors']);
        }
        if ($removeErrors) {
            unset($Session['errors']);
        }
        return $output;
    }

    public function checkSessionSuccesses ($Session, bool $remove = true) {
        $output = "";
        if (isset($Session['successes'])) {
            $output = $this->formatSuccesses($Session['successes']);
        }
        if ($remove) {
            unset($Session['successes']);
        }
        $output;
    }

}




?>