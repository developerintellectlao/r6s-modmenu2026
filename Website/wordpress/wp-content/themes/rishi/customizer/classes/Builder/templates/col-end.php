<?php

$get_header = rishi_customizer()->customize_manager->header_builder;

if ( $count_array['end'] > 0 && $count_array['end-middle'] > 0 )
	$active_end_col += 1;

if ( $end_col ){ ?>
    <div class="rishi-header-col-end header-sub-col-<?php echo absint($active_end_col); ?>">
        <?php if($count_array['end-middle'] > 0){ ?>
            <div class="header-items first-wrapper">
                <?php echo $get_header->get_single_row_elements( $row, 'end-middle', $device ); ?>
            </div>
        <?php } if ( $count_array['end'] > 0 ) { ?>
            <div class="header-items second-wrapper">
                <?php echo $get_header->get_single_row_elements( $row, 'end', $device ); ?>
            </div>
        <?php } ?>
    </div>
<?php
}