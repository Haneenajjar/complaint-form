Complaint Form WordPress 

#Table of Contents
1. Project Overview
2. Features
3. Prerequisites
4. Installation and Setup
    1. Clone the Repository
    2. Upload Theme to WordPress
    3. Activate the Theme
    4. Create Database Table
    5. Install and Configure WP Mail SMTP Plugin
    6. Configure Google reCAPTCHA
5. Shortcode Usage
6. JavaScript and CSS
7. Project Structure
8. Design Decisions
9. Troubleshooting
10. License

#Project Overview
This project is a one-page WordPress website designed to handle customer complaints related to specific purchases. 
The page integrates a complaint form that allows users to submit complaints, which are then stored in a custom database table and sent via email to the designated recipient. 
The website also includes Google reCAPTCHA integration to prevent spam submissions.

#Features
Custom Complaint Form: Includes fields for name, email, phone number, receipt number, and complaint details.
Database Storage: Complaint details are stored in a custom database table (wp_complaints_form).
Email Notifications: Upon successful submission, an email confirmation is sent to the user.
Google reCAPTCHA Integration: Protects the form from spam and automated submissions.
Responsive Design: The form is fully responsive, ensuring compatibility across various devices.
AJAX Form Submission: The form uses AJAX for seamless user interaction without page reloads.
Customizable Text and Images: The form and its surrounding content can be customized using shortcode attributes.

#Prerequisites
Before starting, ensure you have the following:
Google reCAPTCHA keys (site key and secret key).

#Installation and Setup

-Clone the Repository
    Clone the repository to your local machine:
    git clone https://github.com/Haneenajjar/complaint-form.git
    
-Set Up WordPress Files
    The repository contains the complete WordPress installation, including the wp-content directory and all necessary files.
    -Copy all the files from the cloned repository to your server's root directory or to the desired location for the WordPress installation.
    -Ensure the correct file permissions are set on your server to allow WordPress to function properly.
    
-Import the Database
    The repository includes a database dump file (database.sql). To import the database:
    Access your database management tool (e.g., phpMyAdmin, MySQL command line).
    Create a new database (e.g., complaints_db).
    Import the database dump file:
        "mysql -u username -p complaints_db < database.sql"
        
-Update the wp-config.php file to match your database settings:
    define('DB_NAME', 'complaints_db');
    define('DB_USER', 'your_database_user');
    define('DB_PASSWORD', 'your_database_password');
    define('DB_HOST', 'localhost');  // or your database host
    
-Update Site URLs
    If your site URL or home URL differs from the original setup, update these values in the database:
    -Access your database management tool and navigate to the wp_options table.
    -Update the siteurl and home options to reflect your new site URL:
        UPDATE wp_options SET option_value = 'http://your-new-site-url.com' WHERE option_name = 'siteurl';
        UPDATE wp_options SET option_value = 'http://your-new-site-url.com' WHERE option_name = 'home';
    Alternatively, you can use the following commands via the WordPress CLI:
        wp option update siteurl 'http://your-new-site-url.com'
        wp option update home 'http://your-new-site-url.com'

#Admin panel user
Username: QprosUser
Password: tgmCjA7FV7gegqA
 
#Shortcode Usage
To embed the complaint form on any WordPress page, use the following shortcode:
    [complaint_form form_header="Form Header"]

Shortcode Parameters
    form_header: Header text for the form section.

#JavaScript
The theme includes custom JavaScript for form validation and AJAX form submission, located in js/complaint-form-validation.js. 
Key features:
AJAX Submission: Handles form data submission without reloading the page.
Validation: Ensures required fields are filled out before submission.

#CSS
Custom styles for the form and other theme elements are included in the style.css file within the child theme directory. 
This CSS file manages layout, responsiveness, and overall appearance.

#Project Structure
twentytwentyfour-child/: The child theme directory.
functions.php: Handles theme functions, including database interaction and form processing.
style.css: Contains custom styles for the theme.
js/complaint-form-validation.js: Custom JavaScript for form validation and AJAX handling.

#Design Decisions
Responsive Design
    The form is designed with a mobile-first approach, ensuring it is fully functional on mobile devices and scales well to desktop resolutions.
AJAX Submission
    The use of AJAX allows for a smooth user experience by submitting the form without reloading the page. 
    This also enables dynamic error handling and immediate feedback.
Email Delivery
    Using the WP Mail SMTP plugin ensures reliable email delivery by bypassing the often unreliable default wp_mail() function. 
    This is crucial for ensuring that complaint submissions reach their intended recipients.
        -Security Considerations
        Authentication: The WP Mail SMTP plugin requires SMTP authentication, meaning that emails are sent through a secure, authenticated SMTP server rather than relying on the default PHP mail function. 
        This reduces the risk of emails being marked as spam or failing to deliver.
        -Encryption: The plugin supports the use of TLS (Transport Layer Security) and SSL (Secure Sockets Layer) protocols, ensuring that emails are encrypted during transmission.
        This encryption helps to protect sensitive information contained in the complaint emails from being intercepted by malicious actors.
        -Password Protection: SMTP credentials, including the username and password for your email server, are stored securely within the WordPress database. 
        The plugin uses the WordPress options API, which ensures that sensitive information is stored in a protected manner.
        -OAuth 2.0 Support: For enhanced security, WP Mail SMTP supports OAuth 2.0, allowing you to connect to popular email services like Gmail without needing to store your email password.
        OAuth 2.0 provides a more secure method of authentication by issuing tokens that can be revoked or regenerated as needed.
        -SPF and DKIM Support: By configuring your SMTP server with SPF (Sender Policy Framework) and DKIM (DomainKeys Identified Mail) records, you can further enhance the security of your emails.
        These records help prevent email spoofing by verifying that the email was sent from an authorized server, which is essential for maintaining the integrity of your communication.
reCAPTCHA Integration
    Google reCAPTCHA is used to protect the form from spam submissions, enhancing the security and integrity of the data collected.

#Troubleshooting
Common Issues
-Emails Not Sending: Check the WP Mail SMTP settings and ensure the SMTP server credentials are correct.
-reCAPTCHA Errors: Verify that the site key and secret key are correctly entered in functions.php.
    search for "<div class="g-recaptcha" data-sitekey="6LdbBjQqAAAAAGVWr_vAj_yJ2AAs5k17GXrG9qpj"></div>"
    replace data-sitekey with your key.
-Form Not Displaying: Ensure the correct shortcode is used and the theme is activated.

Debugging
Use the browser's developer tools to inspect the form and check for JavaScript errors.
Check the WordPress debug log (wp-content/debug.log) for any PHP errors.

#Important Note on Website Performance and Security
The performance of this website may be affected by the server environment in which it is hosted.
Additionally, the site currently does not have an SSL certificate installed, which violates best practices for security and can negatively impact user trust and SEO rankings.
Installing an SSL certificate is strongly recommended to secure data transmission and adhere to industry standards.
Once the certificate is installed, these issues will be resolved, leading to improved security and performance.
