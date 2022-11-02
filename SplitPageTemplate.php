<?php
    require_once('Template.php');
    class SplitPageTemplate extends Template {
        protected $LEFT_WIDTH = 3; // the width of the left pane, taking up x out of the 12 slots in a bootstrap row

        /**
         * function openLeftPane 
         * Returns: String
         * Splits the page into two parts, and prepares editting
         * of the left pane. Defaulted to contain 25% of the screen
         * 
         * Close with closeLeftOpenRightPane()
         */
        public function openLeftPane() {
            $output = '<div class="container">
                <div class="row">
                    <div class="col-' . $this->LEFT_WIDTH . '">';
            return $output;

        }

        /**
         * function closeLeftOpenRightPane()
         * Returns: String
         * Closes the left pane's html and opens the larger (75% by default) pane
         * and prepares for editting
         * 
         * Close with closeRightPane()
         */
        public function closeLeftOpenRightPane() {
            return '</div><div class="col-' . 12 - $this->LEFT_WIDTH . '">';
        }

        /**
         * function closeRightPane()
         * Returns: String
         * Closes the right pane's HTML tags
         */
        public function closeRightPane() {
            return '</div></div></div>';
        }
    }

?>