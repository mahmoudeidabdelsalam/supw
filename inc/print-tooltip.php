<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Helper function to print wp style tooltip
function supw_print_tooltip($text = '') {
    echo '<span class="apf-tooltip">?
            <span class="apf-tooltiptext">'.$text.'</span>
          </span>';
}
?>