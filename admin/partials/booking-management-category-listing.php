<?php
$dbhandler = new BM_DBhandler();
$cat_identifier = 'CATEGORY';
$cat_id = filter_input(INPUT_POST, 'cat_id', FILTER_VALIDATE_INT);

if ($cat_id == false || $cat_id == null) {
    $cat_id = 0;
}

if (( filter_input( INPUT_POST, 'editcat' ) ) || ( filter_input( INPUT_POST, 'delcat' ))) {

    $retrieved_nonce = filter_input( INPUT_POST, '_wpnonce' );
	if ( !wp_verify_nonce( $retrieved_nonce, 'edit_cat_section' ) ) {
		die( esc_html__( 'Failed security check', 'service-booking' ) );
    }

    if (filter_input( INPUT_POST, 'editcat' )) {
        if($cat_id != 0){
            wp_safe_redirect( esc_url_raw( 'admin.php?page=bm_add_category&id=' . $cat_id ) );
            exit;
        } else {
            echo ('<div><p style="color:red;font-weight:bold;">' . esc_html_e('Category Id could not fetched !!', 'service-booking') .'</p></div>');
        }
    }

    if (( filter_input( INPUT_POST, 'delcat' ) )) {
        $cat_deleted = $dbhandler->remove_row($cat_identifier, 'id', $cat_id, '%d');
        if ($cat_deleted){
            wp_safe_redirect( esc_url_raw( 'admin.php?page=bm_all_categories' ) );
            exit;
        } else {
            echo ('<div><p style="color:red;font-weight:bold;">' . esc_html_e('Category Could not be Deleted !!', 'service-booking') .'</p></div>');
        }
    }
}

$category_results = $dbhandler->get_all_result($cat_identifier, '*', 1, 'results');

?>

<!-- Categories -->
<div class="wrap">
    <br>
    <h2 class="title" style="font-weight: bold;"><?php esc_html_e('All Categories', 'service-booking'); ?></h2>
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
            if (isset($category_results)) {
                $i = 1;
                foreach ($category_results as $category) {
            ?>
                    <form role="form" method="post">
                        <tr>
                            <td style="text-align: center;"><?php echo esc_attr($i) ?></td>
                            <td style="text-align: center;"><?php echo isset($category->cat_name) ? esc_html($category->cat_name) : ''; ?></td>
                            <td style="text-align: center;"><?php echo esc_attr($category->cat_in_front) == 1 ? 'Yes' : 'No' ?></td>
                            <td style="text-align: center;">
                                <?php wp_nonce_field( 'edit_cat_section' ); ?>
                                <input type="hidden" name="cat_id" value="<?php echo isset($category->id) ? esc_attr($category->id) : ''; ?>">
                                <input type="submit" name="editcat" id="editcat" class="button button-primary" value="<?php esc_html_e('Edit', 'service-booking'); ?>">
                                <input type="submit" name="delcat" id="delcat" class="button button-secondary" value="<?php esc_html_e('Delete', 'service-booking'); ?>">
                            </td>
                        </tr>
                    </form>
                <?php $i++;
                }
            } else { ?>
                <td></td>
                <td></td>
                <td style="text-align: center;font-size: 14px"><b><?php esc_html_e('No Categories Found', 'service-booking'); ?></b></td>
                <td></td>
                <?php } ?>
        </tbody>
    </table>
</div>