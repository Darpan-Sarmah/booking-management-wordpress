<?php
$svc_identifier = 'SERVICE';
$cat_identifier = 'CATEGORY';
$dbhandler = new BM_DBhandler();
$catresults = $dbhandler->get_all_result($cat_identifier, '*', ['cat_in_front' => 1], 'results');
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($id == false || $id == null) {
    $id = 0;
}

if ($id != 0) {
    $svc_row = $dbhandler->get_row($svc_identifier, $id);
}

if ((filter_input(INPUT_POST, 'savesvc')) || (filter_input(INPUT_POST, 'upsvc'))) {

    $retrieved_nonce = filter_input( INPUT_POST, '_wpnonce' );
	if ( !wp_verify_nonce( $retrieved_nonce, 'save_svc_section' ) ) {
		die( esc_html__( 'Failed security check', 'service-booking' ) );
    }

    $validated = validate_form($svc_identifier);

    if ($validated) {

        $data = [
            'service_name' => my_sanitize_field($_POST["service_name"]),
            'service_category' => my_sanitize_field($_POST["service_category"]),
            'service_duration' => my_sanitize_field($_POST["service_duration"]),
            'service_min_cap' => !empty($_POST["service_min_cap"]) ? my_sanitize_field($_POST["service_min_cap"]) : 1,
            'service_max_cap' => !empty($_POST["service_max_cap"]) ? my_sanitize_field($_POST["service_max_cap"]) : 1,
            'is_service_front' => isset($_POST["is_service_front"]) ? 1 : 0,
            'service_desc' => my_sanitize_field($_POST["service_desc"]),
            'service_price' => my_sanitize_field($_POST["service_price"]),
        ];

        if ((filter_input(INPUT_POST, 'savesvc'))) {
            $service_id = $dbhandler->insert_row($svc_identifier, $data);
            if ($service_id) {
                wp_safe_redirect(esc_url_raw('admin.php?page=bm_all_services'));
                exit;
            } else {
                echo ('<div id="errorMessage"><p style="color:red;font-weight:bold;">');
                echo esc_html_e('Service Could not be Added !!', 'service-booking') ;
                echo ('</p></div>');
            }
        }

        if ((filter_input(INPUT_POST, 'upsvc'))) {
            $svc_updated = $dbhandler->update_row($svc_identifier, 'id', $id, $data, '', '%d');
            if ($svc_updated) {
                wp_safe_redirect(esc_url_raw('admin.php?page=bm_add_service&id=' . esc_attr($id)));
                exit;
            }
        }
    }
}

?>

<div class="wrap">
    <form role="form" method="post">
        <tbody>
            <br>
            <?php if (!isset($svc_row)) { ?>
                <h2 class="title" style="font-weight: bold;"><?php esc_html_e('Add Service', 'service-booking'); ?></h2>
            <?php } else { ?>
                <a href="admin.php?page=bm_all_services" class="button button-secondary" title="<?php esc_html_e('Service Home', 'service-booking'); ?>"><i class="fa fa-home" aria-hidden="true"></i></a>
                <h2 class="title" style="font-weight: bold;"><?php esc_html_e('Update Service', 'service-booking'); ?></h2>
            <?php } ?>
            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row"><label for="service_name"><?php esc_html_e('Name', 'service-booking'); ?></label></th>
                    <td><input name="service_name" type="text" id="service_name" placeholder="name" class="regular-text" value="<?php echo isset($svc_row) && !empty($svc_row->service_name) ? esc_html($svc_row->service_name) : ''; ?>" autocomplete="off"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="service_category"><?php esc_html_e('Category', 'service-booking'); ?></label></th>
                    <td>
                        <?php if (isset($catresults)) { ?>
                            <select name="service_category" id="service_category" class="regular-text">
                                <option value=""><?php esc_html_e('Select Category', 'service-booking'); ?></option>
                                <?php foreach ($catresults as $cat) { ?>
                                    <option value="<?php echo esc_attr($cat->id) ?? '' ?>" <?php echo isset($svc_row) && (esc_attr($svc_row->service_category ) == esc_attr($cat->id) ) ? 'selected': ''; ?>><?php echo esc_html($cat->cat_name) ?? '' ?></option>
                                <?php } ?>
                            </select>
                        <?php } else { ?>
                            <p><?php esc_html_e('No categories found', 'service-booking'); ?> &nbsp;&nbsp;<a href="admin.php?page=bm_add_category" target="_blank" class="button button-secondary"><?php esc_html_e('Add Category', 'service-booking'); ?></a></p>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="service_duration"><?php esc_html_e('Duration (in hrs)', 'service-booking'); ?></label></th>
                    <td><input name="service_duration" type="number" min="0" step="0.5" max="24" id="service_duration" value="<?php echo isset($svc_row) && !empty($svc_row->service_duration) ? esc_attr($svc_row->service_duration) : ''; ?>" placeholder="duration" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="service_min_cap"><?php esc_html_e('Minimum Capacity', 'service-booking'); ?></label></th>
                    <td><input name="service_min_cap" type="number" min="1" id="service_min_cap" placeholder="minimum capacity" value="<?php echo isset($svc_row) && !empty($svc_row->service_min_cap) ? esc_attr($svc_row->service_min_cap) : ''; ?>" class="regular-text" autocomplete="off"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="service_max_cap"><?php esc_html_e('Maximum Capacity', 'service-booking'); ?></label></th>
                    <td><input name="service_max_cap" type="number" min="1" id="service_max_cap" placeholder="maximum capacity" value="<?php echo isset($svc_row) && !empty($svc_row->service_max_cap) ? esc_attr($svc_row->service_max_cap) : ''; ?>" class="regular-text" autocomplete="off"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="is_service_front"><?php esc_html_e('Show service on site', 'service-booking'); ?></label></th>
                    <td><input name="is_service_front" type="checkbox" id="is_service_front" <?php echo isset($svc_row) && esc_attr($svc_row->is_service_front == 1) ? 'checked' : ''; ?> class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="service_desc"><?php esc_html_e('Description', 'service-booking'); ?></label></th>
                    <td><textarea name="service_desc" id="service_desc" cols="5" rows="5" class="regular-text" tabindex="4" placeholder="description...."><?php echo isset($svc_row) && !empty($svc_row->service_desc) ? esc_html($svc_row->service_desc) : ''; ?></textarea></td>
                </tr>
                <tr>
                    <th scope="row"><label for="service_price"><?php esc_html_e('Price (in &euro;)', 'service-booking'); ?></label></th>
                    <td><input name="service_price" type="text" id="service_price" placeholder="price" value="<?php echo isset($svc_row) && !empty($svc_row->service_price) ? esc_html($svc_row->service_price) : ''; ?>" class="regular-text" autocomplete="off"></td>
                </tr>
            </table>
            <div class="row">
                <p class="submit">
                    <?php wp_nonce_field( 'save_svc_section' ); ?>
                    <?php if (!isset($svc_row)) { ?>
                        <input type="submit" name="savesvc" id="savesvc" class="button button-primary" value="<?php esc_attr_e('Save', 'service-booking'); ?>">
                    <?php } else { ?>
                        <input type="submit" name="upsvc" id="upsvc" class="button button-primary" value="<?php esc_attr_e('Update', 'service-booking'); ?>">
                    <?php } ?>
                    <a href="admin.php?page=bm_all_services" class="button button-secondary"><?php esc_attr_e('Cancel', 'service-booking'); ?></a>
                    <?php if (!isset($svc_row)) { ?>
                    <input type="reset" name="resetfrm" id="resetfrm" class="button-custom" value="<?php esc_attr_e('Reset', 'service-booking'); ?>">
                    <?php } ?>
                </p>
            </div>
        </tbody>
    </form>
</div>

<script>
    jQuery(function() {
        jQuery("#errorMessage").delay(3000).fadeOut(300);
    });
</script>