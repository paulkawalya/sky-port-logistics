document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('quotationForm');
    if (form) {
        // Initialize Chosen.js for select elements
        $('.chosen-select').chosen({
            width: '100%',
            no_results_text: 'No results match',
            placeholder_text_single: 'Select an option'
        });

        // Handle form submission
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const submitBtn = document.getElementById('submitBtn');
            const submitText = document.getElementById('submitText');
            const spinner = document.getElementById('spinner');
            const submitButtonText = document.getElementById('submitButtonText');
            const originalButtonText = submitButtonText.textContent;

            // Show loading state
            submitBtn.disabled = true;
            submitButtonText.textContent = 'Saving...';
            spinner.classList.remove('d-none');

            fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Close the modal first
                    $('#add_new_quotation').modal('hide');
                    $('.modal-backdrop').remove();
                    $('body').removeClass('modal-open').css('padding-right', '');

                    // Redirect immediately to the show page
                    window.location.href = data.redirect;
                } else {
                    // Show error message
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Error saving document',
                        confirmButtonColor: '#0d6efd'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while saving',
                    confirmButtonColor: '#0d6efd'
                });
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitButtonText.textContent = originalButtonText;
                spinner.classList.add('d-none');
            });
        });
    }
});
