<?php
$identifier = 'CATEGORY';
$dbhandler = new BM_DBhandler();
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($id == false || $id == null) {
    $id = 0;
}

if ($id != 0) {
    $cat_row = $dbhandler->get_row($identifier, $id);
}

if ((filter_input(INPUT_POST, 'savecat')) || (filter_input(INPUT_POST, 'upcat'))) {

    $retrieved_nonce = filter_input( INPUT_POST, '_wpnonce' );
	if ( !wp_verify_nonce( $retrieved_nonce, 'save_cat_section' ) ) {
		die( esc_html__( 'Failed security check', 'service-booking' ) );
    }
    
    $validated = validate_form($identifier);

    if ($validated) {
        $data = ['cat_name' => my_sanitize_field($_POST["cat_name"]), 'cat_in_front' => isset($_POST["cat_in_front"]) ? 1 : 0];

        if ((filter_input(INPUT_POST, 'savecat'))) {
            $category_id = $dbhandler->insert_row($identifier, $data);
            if ($category_id) {
                wp_safe_redirect(esc_url_raw('admin.php?page=bm_all_categories'));
                exit;
            } else {
                echo ('<div id="errorMessage"><p style="color:red;font-weight:bold;">');
                echo esc_html_e('Category Could not be Added !!', 'service-booking') ;
                echo ('</p></div>');
            }
        }

        if ((filter_input(INPUT_POST, 'upcat'))) {
            $cat_updated = $dbhandler->update_row($identifier, 'id', $id, $data, '', '%d');
            if ($cat_updated) {
                wp_safe_redirect(esc_url_raw('admin.php?page=bm_add_category&id=' . esc_attr($id)));
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
            <?php if (!isset($cat_row)) { ?>
                <h2 class="title" style="font-weight: bold;"><?php esc_html_e('Add Category', 'service-booking'); ?></h2>
            <?php } else { ?>
                <a href="admin.php?page=bm_all_categories" class="button button-secondary" title="<?php esc_html_e('Category Home', 'service-booking'); ?>"><i class="fa fa-home" aria-hidden="true"></i></a>
                <h2 class="title" style="font-weight: bold;"><?php esc_html_e('Update Category', 'service-booking'); ?></h2>
            <?php } ?>
            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row"><label for="cat_name"><?php esc_html_e('Name', 'service-booking'); ?></label></th>
                    <td><input name="cat_name" type="text" id="cat_name" placeholder="name" class="regular-text" value="<?php echo isset($cat_row) && !empty($cat_row->cat_name) ? esc_html($cat_row->cat_name) : ''; ?>" autocomplete="off"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="cat_in_front"><?php esc_html_e('Show on Site', 'service-booking'); ?></label></th>
                    <td><input name="cat_in_front" type="checkbox" id="cat_in_front" class="regular-text" <?php echo isset($cat_row) && esc_attr($cat_row->cat_in_front) == 1 ? 'checked' : '' ?>></td>
                </tr>
            </table>
            <div class="row">
                <p class="submit">
                    <?php wp_nonce_field( 'save_cat_section' ); ?>
                    <?php if (!isset($cat_row)) { ?>
                        <input type="submit" name="savecat" id="savecat" class="button button-primary" value="<?php esc_attr_e('Save', 'service-booking'); ?>">
                    <?php } else { ?>
                        <input type="submit" name="upcat" id="upcat" class="button button-primary" value="<?php esc_attr_e('Update', 'service-booking'); ?>">
                    <?php } ?>
                    <a href="admin.php?page=bm_all_categories" class="button button-secondary"><?php esc_attr_e('Cancel', 'service-booking'); ?></a>
                    <?php if (!isset($cat_row)) { ?>
                    <input type="reset" name="resetfrm" id="resetfrm" class="button button-default" value="<?php esc_attr_e('Reset', 'service-booking'); ?>">
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