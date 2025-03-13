<?php

$get_header = rishi_customizer()->customize_manager->header_builder;

if ( $count_array['start'] > 0 && $count_array['start-middle'] > 0 )
	$active_start_col += 1;

if ( $start_col ){
    if ( $row !== 'offcanvas' ) echo '<div class="rishi-header-col-start header-sub-col-'. absint($active_start_col) .'">'; ?>
        <?php
        if ($count_array['start'] > 0){
            if ( $row !== 'offcanvas' ) echo '<div class="header-items first-wrapper">';
                echo $get_header->get_single_row_elements( $row, 'start', $device );
            if ( $row !== 'offcanvas' ) echo '</div>';
        }
        if ( $count_array['start-middle'] > 0 ) { ?>
            <div class="header-items second-wrapper">
                <?php echo $get_header->get_single_row_elements( $row, 'start-middle', $device ); ?>
            </div>
            <?php
        }
    if ( $row !== 'offcanvas' ) echo '</div>';
}
