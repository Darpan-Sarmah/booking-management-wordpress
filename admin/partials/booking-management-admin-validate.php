<?php

function validate_form($type)
{

    $errors = new WP_Error();

    // Service
    if($type == 'SERVICE'){
        if (isset($_POST['service_name']) && $_POST['service_name'] == '') {
            $errors->add('service_name_error', 'Service name is required.');
        }

        if (!isset($_POST['service_category']) || $_POST['service_category'] == '') {
            $errors->add('service_category_error', 'Service category is required.');
        }

        if (isset($_POST['service_duration']) && $_POST['service_duration'] == '') {
            $errors->add('service_duration_error', 'Service duration is required.');
        }

        if (isset($_POST['service_price']) && $_POST['service_price'] == '') {
            $errors->add('service_price_error', 'Service price is required.');
        } else if (! filter_var( $_POST['service_price'], FILTER_SANITIZE_NUMBER_INT )){
            $errors->add('service_price_number_error', 'Service price should be a number.');
        }

        if (filter_var( $_POST['service_min_cap'], FILTER_SANITIZE_NUMBER_INT ) > filter_var( $_POST['service_max_cap'], FILTER_SANITIZE_NUMBER_INT )){
            $errors->add('service_min_greater_error', 'Minimum capacity should be less than maximum capacity.');
        }
    }

    // Global Settings
    if($type == 'GLOBAL'){
        if (!isset($_POST['front_view_type'])) {
            $errors->add('front_view_type_error', 'Front view type is required.');
        }
    }

    // Category
    if($type == 'CATEGORY'){
        if (isset($_POST['cat_name']) && $_POST['cat_name'] == '') {
            $errors->add('cat_name_error', 'Category name is required.');
        }
    }

    if (is_wp_error($errors) && empty($errors->errors)) {
        return true;
    }

    if (is_wp_error($errors) && !empty($errors->errors)) {
        $error_messages = $errors->get_error_messages();
        echo ('<div id="errorMessage">');
        foreach ($error_messages as $message) {
            echo ('<p style="color:red;"><b>' . $message . '</b></p>');
        }
        echo ('</div>');
        return false;
    }
}

function my_sanitize_field($input)
{
    return trim(stripslashes(sanitize_text_field($input)));
}

?>