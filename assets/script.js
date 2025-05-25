function highlightStepButton(step) {
    $('.step-btn').each(function () {
        const btnStep = $(this).data('step');

        if (btnStep === 1 || btnStep === step || (step === 3 && btnStep === 2)) {
            // Always highlight Step 1
            // Highlight clicked step
            // Also highlight Step 2 when Step 3 is active
            $(this).removeClass('btn-default').addClass('btn-primary')
                .attr('aria-selected', 'true').attr('tabindex', '0');
        } else {
            $(this).removeClass('btn-primary').addClass('btn-default')
                .attr('aria-selected', 'false').attr('tabindex', '-1');
        }
    });
}
$(document).ready(function () {
    highlightStepButton(1); // default active step 1

    $('.step-btn').click(function () {
        var step = $(this).data('step');
        highlightStepButton(step);

        if (step === 1) {
            $('#step-content').load('profile_form.php');
        } if (step === 2) {
            $('#step-content').html('<iframe src="maindash.php" style="width:100%; height:600px; border:none;"></iframe>');
            // Adjust iframe height after it loads
            $('#dashboardFrame').on('load', function () {
                const iframe = this;
                try {
                    const iframeBody = iframe.contentWindow.document.body;
                    const newHeight = iframeBody.scrollHeight;
                    $(iframe).height(newHeight + 20); // add some padding
                } catch (e) {
                    // Cross-origin protection may block access, fallback:
                    $(iframe).height(800); // fixed fallback height
                }
            });
        }
        else if (step === 3) {
            $('#step-content').html('<p>Loading Preview...</p>');
            // Load preview content via ajax
            $('#step-content').load('preview.php');
        }
    });
});


$(document).ready(function() {
    $('#applicantForm').submit(function(e) {
        e.preventDefault();

        var formData = new FormData(this);

        $.ajax({
            url: 'insert_applicant.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                Swal.fire({
                    icon: response.type,
                    title: response.type.charAt(0).toUpperCase() + response.type.slice(1),
                    text: response.message,
                    confirmButtonText: 'OK'
                }).then(() => {
                    // Reset the form if success
                    if (response.type === 'success') {
                        $('#applicantForm')[0].reset();
                    }
                });
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'AJAX request failed. Please try again.'
                });
            }
        });
    });
});
