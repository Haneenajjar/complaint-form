document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('complaintForm');
    
    if (form) {
        let isSubmitting = false; // Flag to track submission status

        jQuery(form).on('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission

            if (isSubmitting) return; // Prevent multiple submissions

            isSubmitting = true; // Set flag to true to indicate submission

            const submitButton = jQuery('button[type="submit"]');
            submitButton.prop('disabled', true); // Disable the submit button

            var recaptchaResponse = grecaptcha.getResponse();
            if (recaptchaResponse.length === 0) {
                alert('Please complete the reCAPTCHA.');
                submitButton.prop('disabled', false); // Re-enable the submit button
                isSubmitting = false; // Reset the flag
                return; // Stop execution if reCAPTCHA is not complete
            }

            const ajaxUrl = jQuery(form).attr('action') || admin_url + 'admin-post.php';

            const formData = jQuery(this).serialize(); // Serialize form data

            jQuery.ajax({
                url: ajaxUrl,
                method: 'POST',
                data: {
                    action: 'handle_complaint_form_submission',
                    ...jQuery(this).serializeArray().reduce((obj, item) => {
                        obj[item.name] = item.value;
                        return obj;
                    }, {}),
                    'g-recaptcha-response': recaptchaResponse // Add reCAPTCHA response
                },
                dataType: 'json',
                success: function(data) {
                    if (data.success) {
                        window.location.href = data.data.redirect_url;
                    } else {
                        showPopup('Sorry', data.data.message, data.data.complaint_details);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    showPopup('Error', 'There was a problem with the request. Please try again later.', jQuery('input[name="complaint_details"]').val());
                },
                complete: function() {
                    submitButton.prop('disabled', false); // Re-enable the submit button after request is complete
                    isSubmitting = false; // Reset the flag
                }
            });
        });
    }

    function showPopup(title, message, complaintDetails) {
        // Create and show a popup with title and message
        const popup = document.createElement('div');
        popup.className = 'popup-overlay';

        const popupContent = document.createElement('div');
        popupContent.className = 'popup-content';

        const popupTitle = document.createElement('h2');
        popupTitle.textContent = title;
        popupTitle.style.color = '#d2004d';
        popupContent.appendChild(popupTitle);

        const popupMessage = document.createElement('p');
        popupMessage.textContent = message;
        popupContent.appendChild(popupMessage);

        const reloadButton = document.createElement('button');
        reloadButton.textContent = 'OK, close and reload';
        reloadButton.addEventListener('click', () => window.location.reload());
        popupContent.appendChild(reloadButton);

        if (complaintDetails) {
            const showMessageButton = document.createElement('button');
            showMessageButton.textContent = 'Show my message';
            showMessageButton.addEventListener('click', () => {
                const messageContent = `<p>Here is the complaint you tried to send:</p>
                                        <pre>${complaintDetails}</pre>
                                        <p>You can save this message and try sending it again.</p>`;
                popupMessage.innerHTML = messageContent;
                showMessageButton.style.display = 'none'; // Hide the button after click
            });
            popupContent.appendChild(showMessageButton);
        }

        popup.appendChild(popupContent);
        document.body.appendChild(popup);
    }
});
