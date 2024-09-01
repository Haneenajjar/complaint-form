<?php
function twentytwentyfour_child_enqueue_styles() {
    // Enqueue parent theme's stylesheet
    wp_enqueue_style('twentytwentyfour-parent-style', get_template_directory_uri() . '/style.css');
    
    // Enqueue child theme's stylesheet
    wp_enqueue_style('twentytwentyfour-child-style', get_stylesheet_uri(), array('twentytwentyfour-parent-style'), wp_get_theme()->get('Version'));

    // Enqueue Google reCAPTCHA script for spam protection
    wp_enqueue_script('google-recaptcha', 'https://www.google.com/recaptcha/api.js', array(), null, true);
}
add_action('wp_enqueue_scripts', 'twentytwentyfour_child_enqueue_styles');

function create_complaint_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'complaints_form';
    $charset_collate = $wpdb->get_charset_collate();

    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
        $sql = "CREATE TABLE $table_name (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            name varchar(255) NOT NULL,
            phone_number varchar(20) NOT NULL,
            receipt_number varchar(50) NOT NULL,
            complaint_details text NOT NULL,
            email_address varchar(255) NOT NULL,
            submitted_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            PRIMARY KEY (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}
add_action('admin_init', 'create_complaint_table');


// Creating a shortcode to display the complaint form
function complaint_form_shortcode($atts) {
    
    $atts = shortcode_atts(array(
        'header_text' => '',
        'paragraph_one' => '',
        'paragraph_two' => '',
        'form_header' => '',
        'form_image' => '',
        'desktop_image_url' => ''
    ), $atts, 'complaint_form');

    $hide_on_mobile = empty($atts['header_text']) && empty($atts['paragraph_one']) && empty($atts['paragraph_two']) && empty($atts['desktop_image_url']) ? ' hide-on-mobile' : '';

    ob_start();
    ?>
    <div class="complaint-form-container">
        <div class="complaint-info<?php echo $hide_on_mobile; ?>">
            <?php if (!empty($atts['header_text'])) : ?>
                <h1><?php echo esc_html($atts['header_text']); ?></h1>
            <?php endif; ?>
            
            <?php if (!empty($atts['paragraph_one'])) : ?>
                <p><?php echo esc_html($atts['paragraph_one']); ?></p>
            <?php endif; ?>
            
            <?php if (!empty($atts['paragraph_two'])) : ?>
                <p><?php echo esc_html($atts['paragraph_two']); ?></p>
            <?php endif; ?>
            
            <?php if (!empty($atts['desktop_image_url'])) : ?>
                <img src="<?php echo esc_url($atts['desktop_image_url']); ?>" alt="Related Image" class="desktop-image">
            <?php endif; ?>
        </div>
        <div class="complaint-form-wrapper">
            <form id="complaintForm" method="post" action="<?php echo admin_url('admin-ajax.php'); ?>">
                <div>
                    <?php if (!empty($atts['form_image'])) : ?>
                        <img src="<?php echo esc_url($atts['form_image']); ?>" alt="Related Image" class="desktop-image">
                    <?php endif; ?>
                    <p class="form-header"><?php echo esc_html($atts['form_header']); ?></p>
                </div>
                <div class="form-group">
                    <input type="text" id="name" name="name" placeholder="Name*" required>
                </div>
                <div class="form-group">
                    <input type="email" id="email_address" name="email_address" placeholder="Email Address*" required>
                </div>
                <div class="form-group">
                    <input type="tel" id="phone_number" name="phone_number" placeholder="Phone Number*" required pattern="[0-9]+" minlength="10" maxlength="15">
                </div>
                <div class="form-group">
                    <input type="tel" id="receipt_number" name="receipt_number" placeholder="Receipt Number*" required pattern="[0-9]+">
                </div>
                <div class="form-group">
                    <textarea id="complaint_details" name="complaint_details" placeholder="Complaint Details*" required></textarea>
                </div>

                <input type="hidden" name="action" value="submit_complaint">
                <?php wp_nonce_field('complaint_form_nonce', 'complaint_nonce'); ?>
                <div class="g-recaptcha" data-sitekey="6LdbBjQqAAAAAGVWr_vAj_yJ2AAs5k17GXrG9qpj"></div>
                <button type="submit">Submit</button>
            </form>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('complaint_form', 'complaint_form_shortcode');

function handle_complaint_form_submission() {
    // Check for nonce
    if (!isset($_POST['complaint_nonce']) || !wp_verify_nonce($_POST['complaint_nonce'], 'complaint_form_nonce')) {
        wp_send_json_error(array('message' => 'Nonce verification failed. Please try again.', 'complaint_details' => null));
        exit();
    }

    // Sanitize and validate form inputs
    $name = sanitize_text_field($_POST['name']);
    $phone_number = sanitize_text_field($_POST['phone_number']);
    $receipt_number = sanitize_text_field($_POST['receipt_number']);
    $complaint_details = sanitize_textarea_field($_POST['complaint_details']);
    $email_address = sanitize_email($_POST['email_address']);
    
    // Store data in the database
    global $wpdb;
    $table_name = $wpdb->prefix . 'complaints_form';
    $data_saved = $wpdb->insert(
        $table_name,
        array(
            'name' => $name,
            'phone_number' => $phone_number,
            'receipt_number' => $receipt_number,
            'complaint_details' => $complaint_details,
            'email_address' => $email_address,
            'submitted_at' => current_time('mysql'),
        )
    );
        
    // Prepare the email content
    $email_subject = 'New Complaint Received';
    $email_message = sprintf(
        "Dear %s,\n\nThank you for submitting your complaint. We will review it and get back to you shortly.\n\n" .
        "Here are the details of your complaint:\n" .
        "Phone Number: %s\n" .
        "Receipt Number: %s\n" .
        "Complaint Details:\n%s\n\n" .
        "Best regards,\n",
        $name,
        $phone_number,
        $receipt_number,
        $complaint_details
    );
    
    // Send email to the customer
    $email_sent = wp_mail(
        $email_address,
        $email_subject,
        $email_message
    );
    
    // Handle email sending error
    if ($data_saved && $email_sent) {
        wp_send_json_success(array('redirect_url' => site_url('/thank-you')));
    } else {
        $error_message = !$email_sent ? 'There was a problem with sending the email. Please try again later.' : 'There was a problem processing your complaint. Please try again later.';
        wp_send_json_error(array(
            'message' => $error_message,
            'complaint_details' => array(
                'name' => $name,
                'phone_number' => $phone_number,
                'receipt_number' => $receipt_number,
                'complaint_details' => $complaint_details,
                'email_address' => $email_address,
            )
        ));
    }
    exit();
}
add_action('wp_ajax_submit_complaint', 'handle_complaint_form_submission');
add_action('wp_ajax_nopriv_submit_complaint', 'handle_complaint_form_submission');

function twentytwentyfour_child_enqueue_scripts() {
    wp_enqueue_script('complaint-form-validation', get_stylesheet_directory_uri() . '/js/complaint-form-validation.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'twentytwentyfour_child_enqueue_scripts');

?>