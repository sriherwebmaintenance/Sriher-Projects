<?php
/**
 * Loads View for List of all the addons.
 *
 * @package miniorange-otp-verification
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

echo '	
			<div id="addOnsTable">
				<form name="f" method="post" action="" id="mo_add_on_settings">
					<input type="hidden" name="option" value="mo_add_on_settings" />
					<div class="mo-header">
						<p class="mo-heading flex-1">' . esc_html( mo_( 'OTP Verification Addons' ) ) . '</p>
					</div>
					<div class="mo-header font-semibold">	
						' . esc_html( mo_( 'The add-ons serve as extensions to our plugin, enabling access to advanced features. These add-ons are compatible with your purchased plan and provide additional functionalities.' ) ) . '
					</div>
					<div id="addons-grid" class="mo-addon-section-container">';
						show_addon_list();
echo '      		</div>
				</form>
			</div>';

