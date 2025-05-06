
jQuery(document).ready(function($) {
    // Find the field with data-name="approval"
    var $approvalField = $('[data-name="approval"]');
    
    // Check if the field was found
    if ($approvalField.length > 0) {
        // Hide the field
        $approvalField.hide();
    }
});

