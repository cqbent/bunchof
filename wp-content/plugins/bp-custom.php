<?php

define ( 'BP_AVATAR_ORIGINAL_MAX_FILESIZE', 1500000 );
define( 'BP_MESSAGES_AUTOCOMPLETE_ALL', true );

function bunchof_filter_group_stati( $stati ) {
    if( $key = array_search( ‘hidden’, $stati ) ) {
        unset( $stati[$key] );
    }
    return $stati;
}
add_filter( 'groups_valid_status', 'bunchof_filter_group_stati' );
