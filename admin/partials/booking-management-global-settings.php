<?php
$identifier = 'GLOBAL';
$dbhandler = new BM_DBhandler();

if(isset($_POST['saveglobal'])){
    $validated = validate_form($identifier);

    if($validated){

        $data = [
            'show_pro_bar' => isset($_POST["show_pro_bar"]) ? 1 : 0,
            'front_view_type' => my_sanitize_field($_POST["front_view_type"]),
            'show_svc_search' => isset($_POST["show_svc_search"]) ? 1 : 0,
            'show_cat_search' => isset($_POST["show_cat_search"]) ? 1 : 0,
            'is_read_more' => isset($_POST["is_read_more"]) ? 1 : 0,
            'show_svc_price' => isset($_POST["show_svc_price"]) ? 1 : 0,
            'show_svc_duration' => isset($_POST["show_svc_duration"]) ? 1 : 0,
            'show_svc_desc' => isset($_POST["show_svc_desc"]) ? 1 : 0,
            'show_svc_edit' => isset($_POST["show_svc_edit"]) ? 1 : 0,
            'show_pagination' => isset($_POST["show_pagination"]) ? 1 : 0,
            'show_ordering' => isset($_POST["show_ordering"]) ? 1 : 0,
        ];

        update_option('global_front_booking_settings', $data);
        echo ('<div id="successMessage"><p style="color:green;font-weight:bold;">');
        echo esc_html_e('Data Saved Sucessfully.', 'service-booking') ;
        echo ('</p></div>');
    }
}

if (isset($_POST['resetdata'])) {
    if(get_option('global_front_booking_settings') != null){
        delete_option('global_front_booking_settings');
        echo ('<div id="successMessage"><p style="color:green;font-weight:bold;">');
        echo esc_html_e('Data Cleared Sucessfully.', 'service-booking') ;
        echo ('</p></div>');
    }
}

$front_settings = maybe_unserialize(get_option('global_front_booking_settings'));

?>

<div class="wrap">
    <h1 class="section-title"><?php esc_html_e('Global Settings', 'service-booking'); ?></h1>
    <form role="form" method="post" action="admin.php?page=bm_global">
        <tbody>
            <br>
            <h2 class="title" style="font-weight: bold;"><?php esc_html_e('Front-End Settings', 'service-booking'); ?></h2>
            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row"><label for="show_pro_bar"><?php esc_html_e('Show Progress Bar', 'service-booking'); ?></label></th>
                    <td><input name="show_pro_bar" type="checkbox" id="show_pro_bar" class="regular-text" <?php echo isset($front_settings) && esc_attr($front_settings['show_pro_bar']) == 1 ? 'checked' : ''; ?>></td>
                </tr>
                <tr>
                    <th scope="row"><label for="front_view_type"><?php esc_html_e('Services View Type', 'service-booking'); ?></label></th>
                    <td>
                        <input name="front_view_type" type="radio" id="front_view_type" value="grid" <?php echo isset($front_settings) && esc_attr($front_settings['front_view_type']) == 'grid' ? 'checked' : ''; ?>> Grid &nbsp;
                        <input name="front_view_type" type="radio" id="front_view_type" value="list" <?php echo isset($front_settings) && esc_attr($front_settings['front_view_type']) == 'list' ? 'checked' : ''; ?>> List
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="show_svc_search"><?php esc_html_e('Show Service Search', 'service-booking'); ?></label></th>
                    <td><input name="show_svc_search" type="checkbox" id="show_svc_search" class="regular-text" <?php echo isset($front_settings) && esc_attr($front_settings['show_svc_search']) == 1 ? 'checked' : ''; ?>></td>
                </tr>
                <tr>
                    <th scope="row"><label for="show_cat_search"><?php esc_html_e('Show Category Search', 'service-booking'); ?></label></th>
                    <td><input name="show_cat_search" type="checkbox" id="show_cat_search" class="regular-text" <?php echo isset($front_settings) && esc_attr($front_settings['show_cat_search']) == 1 ? 'checked' : ''; ?>></td>
                </tr>
                <tr>
                    <th scope="row"><label for="is_read_more"><?php esc_html_e('Show Read More', 'service-booking'); ?></label></th>
                    <td><input name="is_read_more" type="checkbox" id="is_read_more" class="regular-text" <?php echo isset($front_settings) && esc_attr($front_settings['is_read_more']) == 1 ? 'checked' : ''; ?>></td>
                </tr>
                <tr>
                    <th scope="row"><label for="show_svc_price"><?php esc_html_e('Show Service Price', 'service-booking'); ?></label></th>
                    <td><input name="show_svc_price" type="checkbox" id="show_svc_price" class="regular-text" <?php echo isset($front_settings) && esc_attr($front_settings['show_svc_price']) == 1 ? 'checked' : ''; ?>></td>
                </tr>
                <tr>
                    <th scope="row"><label for="show_svc_duration"><?php esc_html_e('Show Service Duration', 'service-booking'); ?></label></th>
                    <td><input name="show_svc_duration" type="checkbox" id="show_svc_duration" class="regular-text" <?php echo isset($front_settings) && esc_attr($front_settings['show_svc_duration']) == 1 ? 'checked' : ''; ?>></td>
                </tr>
                <tr>
                    <th scope="row"><label for="show_svc_desc"><?php esc_html_e('Show Service Description', 'service-booking'); ?></label></th>
                    <td><input name="show_svc_desc" type="checkbox" id="show_svc_desc" class="regular-text" <?php echo isset($front_settings) && esc_attr($front_settings['show_svc_desc']) == 1 ? 'checked' : ''; ?>></td>
                </tr>
                <tr>
                    <th scope="row"><label for="show_svc_edit"><?php esc_html_e('Show Service Edit', 'service-booking'); ?></label></th>
                    <td><input name="show_svc_edit" type="checkbox" id="show_svc_edit" class="regular-text" <?php echo isset($front_settings) && esc_attr($front_settings['show_svc_edit']) == 1 ? 'checked' : ''; ?>></td>
                </tr>
                <tr>
                    <th scope="row"><label for="show_pagination"><?php esc_html_e('Show Pagination', 'service-booking'); ?></label></th>
                    <td><input name="show_pagination" type="checkbox" id="show_pagination" class="regular-text" <?php echo isset($front_settings) && esc_attr($front_settings['show_pagination']) == 1 ? 'checked' : ''; ?>></td>
                </tr>
                <tr>
                    <th scope="row"><label for="show_ordering"><?php esc_html_e('Show Result Ordering (Asc/Desc)', 'service-booking'); ?></label></th>
                    <td><input name="show_ordering" type="checkbox" id="show_ordering" class="regular-text" <?php echo isset($front_settings) && esc_attr($front_settings['show_ordering']) == 1 ? 'checked' : ''; ?>></td>
                </tr>
            </table>
            <div class="row">
                <p class="submit">
                    <input type="submit" name="saveglobal" id="saveglobal" class="button button-primary" value="Save Changes">
                    <input type="submit" name="resetdata" id="resetdata" class="button button-secondary" value="Reset">
                </p>
            </div>
        </tbody>
    </form>
</div>

<script>
    jQuery(function() {
        jQuery("#successMessage").delay(3000).fadeOut(300);
    });
</script>