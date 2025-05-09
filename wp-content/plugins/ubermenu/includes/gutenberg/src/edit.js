/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-block-editor/#useBlockProps
 */
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { SelectControl, PanelBody } from '@wordpress/components';

import { Fragment } from '@wordpress/element';
import ServerSideRender from '@wordpress/server-side-render';

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
// import './editor.scss';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#edit
 *
 * @return {WPElement} Element to render.
 */
export default function Edit({ attributes, setAttributes }) {

	const menuOptions = Object.keys(
		window.ubermenu_block.menu_options
	).map((menuId) => {
		return {
			value: menuId,
			label: window.ubermenu_block.menu_options[menuId],
		};
	});

	const configOptions = Object.keys(
		window.ubermenu_block.config_options
	).map((configId) => {
		return {
			value: configId,
			label: window.ubermenu_block.config_options[configId],
		};
	});

	return (
		<Fragment>
			<InspectorControls>
				<PanelBody>
					<SelectControl
						label={__('Menu')}
						value={attributes.menuId}
						onChange={(value) =>
							setAttributes({ menuId: +value })
						}
						options={menuOptions}
					/>

					<SelectControl
						label={__('Configuration')}
						value={attributes.configId}
						onChange={(value) =>
							setAttributes({ configId: value })
						}
						options={configOptions}
					/>
				</PanelBody>
			</InspectorControls>
			<div {...useBlockProps()} >
				<ServerSideRender
					block="ubermenu/ubermenu-block"
					attributes={attributes}
				/>
			</div>
		</Fragment>
	);
}
