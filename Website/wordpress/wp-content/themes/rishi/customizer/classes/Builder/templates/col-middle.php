<?php

$get_header = rishi_customizer()->customize_manager->header_builder;

if ( $mid_col ) { ?>
    <div class="rishi-header-col-middle">
        <?php $get_header->get_single_row_elements( $row, 'middle', $device ); ?>
    </div>
<?php
}