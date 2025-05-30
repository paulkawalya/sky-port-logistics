/**
 * Modal fixes to ensure overlays cover the entire screen
 */
$(document).ready(function() {
    // Fix for modal backdrop not covering the entire screen
    $(document).on('show.bs.modal', '.modal', function() {
        // Remove any existing backdrops to prevent stacking
        $('.modal-backdrop').remove();
        
        // Create a new backdrop manually if needed
        if ($('.modal-backdrop').length === 0) {
            $('body').append('<div class="modal-backdrop"></div>');
        }
        
        // Ensure body has the modal-open class
        $('body').addClass('modal-open');
        
        // Fix for scrollbar disappearing
        $('body').css('overflow', 'hidden');
    });
    
    // Clean up when modal is hidden
    $(document).on('hidden.bs.modal', '.modal', function() {
        // Only remove backdrop and classes if no other modals are open
        if ($('.modal.show').length === 0) {
            $('.modal-backdrop').remove();
            $('body').removeClass('modal-open');
            $('body').css('overflow', 'auto');
            $('body').css('padding-right', '');
        }
    });
    
    // Fix for modals that don't close properly
    window.forceCloseAllModals = function() {
        $('.modal').modal('hide');
        $('.modal').hide();
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open');
        $('body').css('overflow', 'auto');
        $('body').css('padding-right', '');
    };
    
    // Fix for modals that don't close with ESC key
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape' && $('.modal.show').length > 0) {
            window.forceCloseAllModals();
        }
    });
});
