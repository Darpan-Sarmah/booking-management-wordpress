<?php
$dbhandler = new BM_DBhandler();
$svc_identifier = 'SERVICE';
$service_id = filter_input(INPUT_POST, 'service_id', FILTER_VALIDATE_INT);

if ($service_id == false || $service_id == null) {
    $service_id = 0;
}

if (( filter_input( INPUT_POST, 'editsvc' ) ) || filter_input( INPUT_POST, 'delsvc' )) {

    $retrieved_nonce = filter_input( INPUT_POST, '_wpnonce' );
	if ( !wp_verify_nonce( $retrieved_nonce, 'edit_svc_section' ) ) {
		die( esc_html__( 'Failed security check', 'service-booking' ) );
    }

    if (filter_input( INPUT_POST, 'editsvc' )){
        if($service_id != 0){
            wp_safe_redirect( esc_url_raw( 'admin.php?page=bm_add_service&id=' . $service_id ) );
            exit;
        } else {
            echo ('<div><p style="color:red;font-weight:bold;">' . esc_html_e('Service Id could not fetched !!', 'service-booking') . '</p></div>');
        }
    }

    if (filter_input( INPUT_POST, 'delsvc' )) {
        $svc_deleted = $dbhandler->remove_row($svc_identifier, 'id', $service_id, '%d');
        if ($svc_deleted) {
            wp_safe_redirect( esc_url_raw( 'admin.php?page=bm_all_services' ) );
            exit;
        } else {
            echo ('<div><p style="color:red;font-weight:bold;">' . esc_html_e('Service Could Not be Deleted !!', 'service-booking') . '</p></div>');
        }
    }
}

$service_results = $dbhandler->get_all_result($svc_identifier, '*', 1, 'results');

?>

<!-- Services -->
<div class="wrap">
    <br>
    <h2 class="title" style="font-weight: bold;"><?php esc_html_e('All Services', 'service-booking'); ?></h2>
    <table class="wp-list-table widefat striped">
        <thead>
            <tr>
                <th width="10%" style="text-align: center;font-weight: 600;"><?php esc_html_e('Serial No', 'service-booking'); ?></th>
                <th style="text-align: center;font-weight: 600;"><?php esc_html_e('Name', 'service-booking'); ?></th>
                <th style="text-align: center;font-weight: 600;"><?php esc_html_e('Show on Site', 'service-booking'); ?></th>
                <th width="25%" style="text-align: center;font-weight: 600;"><?php esc_html_e('Actions', 'service-booking'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (isset($service_results)) {
                $i = 1;
                foreach ($service_results as $service) {
            ?>
                    <form role="form" method="post">
                        <tr>
                            <td style="text-align: center;"><?php echo esc_attr($i) ?></td>
                            <td style="text-align: center;"><?php echo isset($service->service_name) ? esc_html($service->service_name) : ''; ?></td>
                            <td style="text-align: center;"><?php echo esc_attr($service->is_service_front) == 1 ? 'Yes' : 'No' ?></td>
                            <td style="text-align: center;">
                                <?php wp_nonce_field( 'edit_svc_section' ); ?>
                                <input type="hidden" name="service_id" value="<?php echo isset($service->id) ? esc_attr($service->id) : ''; ?>">
                                <input type="submit" name="editsvc" id="editsvc" class="button button-primary" value="<?php esc_html_e('Edit', 'service-booking'); ?>">
                                <input type="submit" name="delsvc" id="delsvc" class="button button-secondary" value="<?php esc_html_e('Delete', 'service-booking'); ?>">
                            </td>
                        </tr>
                    </form>
                <?php $i++;
                }
            } else { ?>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td><p><b><?php esc_html_e('No Services Found', 'service-booking'); ?></p></b></td><td></td><?php } ?>
        </tbody>
    </table>
</div>