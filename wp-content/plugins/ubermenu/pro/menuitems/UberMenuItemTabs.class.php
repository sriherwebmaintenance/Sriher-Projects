<?php

class UberMenuItemTabs extends UberMenuItem
{

	protected $type = 'tabs';
	protected $auto_child = 'tab';
	protected $alter_structure = true;
	protected $manual_content = '';


	function init()
	{
		/*
										if( $this->depth < 1 ){
											$this->walker->set_ignore( $this->ID );
											return;
										}
								*/
		// uberp_details('Tabs ID: ' . $this->ID . ' Source ID: ' . $this->source_id, $this->settings);

		$this->submenu_type = 'tabs-group';
		$this->submenu_classes[] = 'ubermenu-tabs-group';

		$tabs_trigger = $this->getSetting('tabs_trigger');
		$this->submenu_classes[] = "ubermenu-tabs-group--trigger-$tabs_trigger";
		if ($tabs_trigger === 'auto') {
			$tabs_trigger = $this->get_menu_op('trigger');
			$this->submenu_classes[] = "ubermenu-tabs-group--trigger-$tabs_trigger";
		}

		$tabs_group_layout = $this->getSetting('tabs_group_layout');
		if ($tabs_group_layout == 'auto') {
			$tab_layout = $this->getSetting('tab_layout');
			switch ($tab_layout) {
				case 'top':
				case 'bottom':
					$tabs_group_layout = 'full';
					break;
				case 'right':
				case 'left':
					$tabs_group_layout = '1-4';
					break;
			}
		}
		//$panels_group_layout = $this->getSetting( 'panels_group_layout' );

		$this->submenu_classes[] = 'ubermenu-column';
		$this->submenu_classes[] = 'ubermenu-column-' . $tabs_group_layout;


	}

	function alter(&$children)
	{

		//Reset the children each time
		$reference_index = $this->create_reference($this->source_id, $children);


		//No children?  Uh oh
		if (!isset($children[$reference_index])) {
			$notice = __('To create tabs, add child items to the Tab Item.', 'ubermenu');
			$this->manual_content = ubermenu_admin_notice($notice, false);
			;
			return;
		}

		$children[$this->source_id] = $children[$reference_index];

	}

	function add_class_layout_columns()
	{

		if ($this->depth > 0) {
			$parent_submenu_type = $this->walker->parent_item()->getSetting('submenu_type_calc');
			if ($parent_submenu_type == 'flyout')
				return;	//no columns in flyouts
		}

		//if( $this->depth > 1 ){

		//Special columns setting ID for tabs
		$cols = $this->getSetting('tab_block_columns');
		if ($this->depth > 0 && $cols == 'auto') {
			$cols = $this->walker->parent_item()->getSetting('submenu_column_default');
		}

		//Change specific for Tabs, so that by default we're full width
		if ($cols == 'auto')
			$cols = 'full';

		$this->item_classes[] = 'ubermenu-column ubermenu-column-' . $cols;
	}

	function user_classes()
	{
		// uberp( $this->item->classes );
		if ($this->item->classes[0] === '')
			return;

		$user_classes = [];
		foreach ($this->item->classes as $class) {
			if ($class === 'menu-item')
				break;
			$user_classes[] = $class;
		}
		// uberp( $user_classes );
		$this->item_classes = array_merge($this->item_classes, $user_classes);
	}

	//Full width of Tabs
	function get_start_el()
	{
		//$this->setupAutoChild();
		$this->settings['submenu_type_calc'] = 'toggles-group';


		//Variable Initialization
		$item_output = '';
		$class_names = '';

		//Tabs class
		$this->item_classes[] = 'menu-item';
		$this->item_classes[] = 'tabs';


		//Setup Classes
		//$this->add_class_item_defaults();
		$this->add_class_id();
		$this->prefix_classes();
		$this->user_classes();
		//$this->add_class_item_display();
		$this->add_class_level();
		$this->add_class_layout_columns();
		$this->add_class_responsive();
		//$this->add_class_submenu();	//Tabs won't have submenus, add manually

		//$this->item_classes[] = 'ubermenu-has-submenu-drop';
//echo '>> ' . $this->item->title . ' : ' . implode(',' , $this->item_classes ) .'<br/>';



		//Tabs Layout
		$tab_layout = $this->getSetting('tab_layout');
		$tab_layout_class = 'ubermenu-tab-layout-' . $tab_layout;

		$tabs_group_layout = $this->getSetting('tabs_group_layout');
		$panels_group_layout = $this->getSetting('panels_group_layout');

		if ($tab_layout == 'right' || $tab_layout == 'left') {
			if ($tabs_group_layout == 'auto')
				$tabs_group_layout = '1-4';
			if ($panels_group_layout == 'auto')
				$panels_group_layout = '3-4';	//TODO
		} else {
			if ($tabs_group_layout == 'auto')
				$tabs_group_layout = 'full';
			if ($panels_group_layout == 'auto')
				$panels_group_layout = 'full';
		}

		$this->item_classes[] = $tab_layout_class;



		//Show Default
		if ($this->getSetting('show_default_panel') == 'on') {
			$this->item_classes[] = 'ubermenu-tabs-show-default';
		}

		//Show Current
		if ($this->getSetting('show_current_panel') == 'on') {
			$this->item_classes[] = 'ubermenu-tabs-show-current';
		}


		//Dynamic Sizing & Animation
		if ($this->getSetting('dynamic_panel_sizing') == 'on') {
			$this->item_classes[] = 'ubermenu-tabs-dynamic-sizing';
			if ($this->getSetting('dynamic_panel_animation') == 'on') {
				$this->item_classes[] = 'ubermenu-tabs-dynamic-sizing-animate';
			}
		}

		$class_names = $this->filter_item_classes();

		//Setup ID
		$id = $this->filter_item_id();

		//Data target
		//$data_target = ' data-ubermenu-toggle-target="#ubermenu-panel-'.$this->ID.'"';


		//Comment
		$item_output .= "<!-- begin Tabs: " . $this->item->title . " $this->ID -->";

		//Item LI
		$item_output .= '<li' . $id . $class_names . '>';

		//Manual Content (for admin notices)
		$item_output .= $this->manual_content;

		//Anchor
		//$atts = $this->anchor_atts(); //Attributes
		//$atts['data-ubermenu-toggle-target'] = '#ubermenu-panel-'.$this->ID;
		//$item_output .= $this->get_anchor( $atts );

		//Custom Content
		$item_output .= $this->get_custom_content();

		return $item_output;


		//$html.= '<li class="ubermenu-tabs">';
		//$html.= parent::get_start_el();
		//return $html;
	}
	function get_end_el()
	{
		//$this->resetAutoChild();
		$html = '</li>';
		$html .= "<!-- end Tabs: " . $this->item->title . " $this->ID -->";
		return $html;
	}

	/* No submenus for the Tabs Item */
	/*function get_submenu_wrap_start(){
					   return '';
				   }
				   function get_submenu_wrap_end(){
					   return '';
				   }*/


	function setup_tab($umitem)
	{

		$panels_group_layout = $this->getSetting('panels_group_layout');

		if ($panels_group_layout == 'auto') {
			$tab_layout = $this->getSetting('tab_layout');

			switch ($tab_layout) {

				case 'top':
				case 'bottom':
					$panels_group_layout = 'full';
					break;

				case 'left':
				case 'right':

					$tabs_group_layout = $this->getSetting('tabs_group_layout');
					if ($tabs_group_layout == 'auto') {
						$panels_group_layout = '3-4';
					} else {
						$panels_group_layout = ubermenu_get_column_complement($tabs_group_layout);
					}
					break;

			}

		}

		//Force submenu type to tab content panel, regardless of how it was set
		$umitem->submenu_type = $umitem->settings['submenu_type'] = 'tab-content-panel';
		$umitem->submenu_classes[] = "ubermenu-tab-content-panel";
		$umitem->submenu_classes[] = "ubermenu-column";
		$umitem->submenu_classes[] = "ubermenu-column-" . $panels_group_layout;

		//Padding
		// if( $this->getSetting( 'panels_padded' ) == 'on' ){
		// 	$umitem->submenu_classes[] = 'ubermenu-submenu-padded';
		// }

		//Grid Panel
		if ($this->getSetting('panels_grid') == 'on') {
			$umitem->submenu_classes[] = 'ubermenu-submenu-grid';
		}
	}


}


/* An individual toggle item (regular menu item) */ /* ItemDefault vs Item ? */
class UberMenuItemTab extends UberMenuItemDefault
{

	protected $type = 'tab';
	//protected $alter_structure = true;	//sort of
	//protected $manual_content;
	//protected $auto_child = 'default'; // 'tab-content-panel';

	function init()
	{
		if ($this->walker->current_item() && $this->walker->current_item()->getType() == 'tabs') {
			$this->walker->current_item()->setup_tab($this);
		}
	}
	/*
					function alter( &$children ){

						//No children?  Uh oh
						if( !isset( $children[$this->source_id] ) ){
							$notice = __( 'To create tabs, add child items to the Tab Item.' , 'ubermenu' );
							$this->manual_content = ubermenu_admin_notice( $notice , false );;
							return;
						}

					}
				*/

	function add_class_layout_columns()
	{

		if ($this->depth > 0) {
			$parent_submenu_type = $this->walker->parent_item()->getSetting('submenu_type_calc');
			if ($parent_submenu_type == 'flyout')
				return;	//no columns in flyouts
		}

		//if( $this->depth > 1 ){

		$cols = $this->getSetting('columns');

		//Change specific for Left/Right Tab layouts, so that by default we're full width
		if ($cols == 'auto') {
			$tab_layout = $this->walker->parent_item()->getSetting('tab_layout');
			if ($tab_layout == 'right' || $tab_layout == 'left') {
				$cols = 'full';
			} else if ($this->depth > 0 && $cols == 'auto') {
				$cols = $this->walker->parent_item()->getSetting('submenu_column_default');
			}
		}

		$this->item_classes[] = 'ubermenu-column ubermenu-column-' . $cols;

		if ($this->getSetting('submenu_column_default') == 'auto') {
			$this->settings['submenu_column_default'] = $this->walker->parent_item()->getSetting('submenu_column_default');
		}
	}

	// function get_submenu_type( $submenu_type = false ){
	// 	return 'tab-content-panel';
	// }


	function get_start_el()
	{

		//$this->setupAutoChild();



		//Variable Initialization
		$item_output = '';
		$class_names = $value = '';

		//Tab
		$this->item_classes[] = 'ubermenu-tab';

		//Setup Classes
		$this->add_class_item_defaults();
		$this->add_class_id();
		$this->prefix_classes();
		$this->add_class_item_display();
		//$this->add_class_level();
		$this->add_class_layout_columns();
		$this->add_class_responsive();
		//$this->add_class_submenu();	//Tabs won't have submenus, add manually

		if (
			!($this->getSetting('disable_submenu_on_mobile') == 'on' &&
				ubermenu_is_mobile('disable_submenu_on_mobile')) &&
			$this->has_children
		) {
			$this->item_classes[] = 'ubermenu-has-submenu-drop';
		}


		//echo '>> ' . $this->item->title . ' : ' . implode(',' , $this->item_classes ) .'<br/>';



		$class_names = $this->filter_item_classes();

		//Setup ID
		$id = $this->filter_item_id();

		//Data target
		//$data_target = ' data-ubermenu-toggle-target="#ubermenu-panel-'.$this->ID.'"';

		//Setup Trigger
		$this->setup_trigger();


		//Atts
		$atts = ' ';
		foreach ($this->item_atts as $att => $val) {
			$atts .= $att . '="' . $val . '" ';
		}


		//Item LI
		$item_output .= '<li' . $id . $class_names . $atts . '>';

		//Anchor
		$atts = $this->anchor_atts(); //Attributes
		//If a tab needs to be toggleable, then we need to give it a tabindex so that disabled links (spans) become accessible
		if ($this->has_children && $this->getSetting('disable_link') == 'on') {
			$atts['tabindex'] = 0;
		}
		$item_output .= $this->get_anchor($atts);

		//Custom Content
		$item_output .= $this->get_custom_content();

		return $item_output;

	}

	function get_end_el()
	{
		//$this->resetAutoChild();
		$item_output = "</li>";
		return $item_output;
	}

	function setup_trigger()
	{

		//Item Trigger
		$trigger = $this->getSetting('item_trigger');

		//If auto, get trigger from Tabs Group
		if (!$trigger || $trigger == 'auto') {
			$trigger = $this->walker->parent_item()->getSetting('tabs_trigger');
		}

		if ($trigger && $trigger != 'auto' && $this->has_children) {
			$this->item_atts['data-ubermenu-trigger'] = $trigger;
		}
	}

}
