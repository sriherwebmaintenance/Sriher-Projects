<?php

/** TERMS **/

//// The way this works on multiple levels is that when we expand the terms
//// for dynamic item #55, the new item will have an ID #{item_id}-term-{term_id}, e.g.
//// #55-term-21.  Each item is cloned with a new ID.  However, the clone retains
//// a reference to the original ID (55) in the `object_id` property.  This ID is
//// used to retrieve the proper children to clone for the newly expand dynamic terms


class UberMenuItemDynamicTerms extends UberMenuItemDynamic{
	protected $type = 'dynamic_terms';
	protected $alter_structure = true;
	protected $term_count;
	protected $notice;
	protected $term_args;
	//protected $term_map = array();
	//protected $term_map_index = 0;



	//Because this item isn't actually taking up and space, its children
	//are effectively one level up
	function getVirtualDepth(){
		//return $this->walker->parent_item()->depth;
		return $this->depth-1;
	}


	function init(){
		$this->source_id = $this->item->db_id;
		// uberp( 'Dynamic Terms ' . $this->item->ID );
	}


	function alter( &$children ){

//echo '<h3>dynamic_terms ' . $this->ID. ' || '. $this->source_id . ' || ' .  $this->item->object_id . '</h3><br/>';

		//Dynamic Items are only good on submenus
		if( UBERMENU_ALLOW_TOP_LEVEL_DYNAMIC_ITEMS || $this->depth > 0 ){

			//Set the reference index during the first pass
			$reference_index = $this->create_reference( $this->source_id , $children );



			//Get the Terms Settings
			$term_args = array();
			$settings_map = array(
				//'taxonomy'	=> 'dt_taxonomy',
				'number'	=> 'dt_number',
				'parent'	=> 'dt_parent',
				'child_of'	=> 'dt_child_of',
				'orderby'	=> 'dt_orderby',
				'order'		=> 'dt_order',
				'hide_empty'=> 'dt_hide_empty',
				'hierarchical' => 'dt_hierarchical',
				'exclude'	=> 'dt_exclude',
				//'exclude_tree' => 'dt_exclude_tree',
			);
			//Setup terms args based on settings
			foreach( $settings_map as $t_arg => $s_key ){
				$v = $this->getSetting( $s_key ); //isset( $settings[$s_key] ) ? $settings[$s_key] : $defaults[$s_key];

				if( $v === 'on' ) $v = true;
				else if( $v === 'off' ) $v = false;

				$term_args[$t_arg] = $v;
			}

			// Meta value of 'order'
			if( $term_args['orderby'] === 'meta_term_order' ){
				$term_args['meta_query'] = array(
		      		'meta_term_order' => array(
		        		'key' => 'order',
		        		'type' => 'NUMERIC',
		      		)
		    	);
			}


			//Inherit parent term ID
			if( $term_args['parent'] == -1 ){
				$term_args['parent'] = $this->walker->find_parent_term();
			}

			//Inherit ancestor term ID
			if( $term_args['child_of'] == -1 ){
				$term_args['child_of'] = $this->walker->find_parent_term();
			}

			//Get the taxonomies to search
			$taxonomies = $this->getSetting( 'dt_taxonomy' ); //isset( $settings['dt_taxonomy'] ) ? $settings['dt_taxonomy'] : $defaults['dt_taxonomy'];
			if( empty( $taxonomies ) ){
				$this->notice = '<strong>'.__( 'Please select at least one taxonomy in the Dynamic Terms settings' , 'ubermenu' ).'</strong>';
			}
			$term_args['taxonomy'] = $taxonomies;


			//Allow filtering
			$term_args = apply_filters( 'ubermenu_dynamic_terms_args' , $term_args , $this->ID );

			$this->term_args = $term_args;

			///////////////////////////////
			//Retrieve the Terms
			///////////////////////////////

			// $terms = get_terms( $taxonomies , $term_args );
			$terms = get_terms( $term_args );

			if( empty( $terms ) && $this->getSetting('dt_ignore_empty_results') !== 'on' ){
				$this->notice = '<strong>'.$this->item->title.' ('.$this->ID.')</strong>: '.__( 'No results found' , 'ubermenu' );

				if( empty( $taxonomies ) ){
					$this->notice.= '<br/><strong>'.__( 'Please select at least one taxonomy in the Dynamic Terms settings' , 'ubermenu' ).'</strong>';
				}
				else if( count( $taxonomies ) > 1 ){
					$this->notice.= '<br/><strong>'.__( 'Make sure you only select the taxonomies that you want to display terms from' , 'ubermenu' ).'</strong>';
				}

				$this->notice.= '<br/><em>'.__( 'Taxonomies' , 'ubermenu' ).':</em>';
				$this->notice.= '<pre>';
				$this->notice.= print_r( $taxonomies , true );
				$this->notice.= '</pre>';
				$this->notice.= '<br/><em>'.__( 'Query Arguments' , 'ubermenu' ).':</em>';
				$this->notice.= '<pre>';
				$this->notice.= print_r( $term_args , true );
				$this->notice.= '</pre>';
			}

			if( is_wp_error( $terms ) ){
				// $notice = ubermenu_admin_notice( $terms->get_error_message() );
				//Invalid
				return;
			}

			// Enforce limit - if using parent or child_of, WordPress ignores the number value
			// Therefore we will make sure to enforce this manually:
			// If number is set, and is greater than 0, and is less than the total terms returned, only return
			// the first n terms
			if( isset( $term_args['number'] ) && $term_args['number'] > 0 && $term_args['number'] < count( $terms ) ){
				$terms = array_slice( $terms , 0 , $term_args['number'] );
			}

			

			//Autocolumns setup
			$autocolumns = intval( $this->getSetting( 'dt_autocolumns' ) );
			// uberp( "autocolumns $autocolumns" );
			$this->term_count = $term_count = count( $terms );
			$items_per_column;
			$column_id;
			$column_children = array();

			$column_map = array();

			if( $autocolumns && $autocolumns > 0 && $autocolumns != 'disabled' ){
				// echo "autocolumns $term_count / $autocolumns = " . ( $term_count / $autocolumns );

				$remainder = $term_count % $autocolumns;

				if( $remainder ){

					$items_per_column = floor( $term_count / $autocolumns );
					$items_per_column++;
					for( $_k = 0; $_k < $autocolumns; $_k++ ){
						$column_map[$_k] = $items_per_column;
						if( $_k+1 == $remainder ){
							$items_per_column--;
						}
					}
				}
				else{
					$items_per_column = ceil( $term_count / $autocolumns );
					for( $_k = 0; $_k < $autocolumns; $_k++ ){
						$column_map[$_k] = $items_per_column;
					}
				}
			}


			
			//Loop through each term, get its info and create a Dummy Item to
			//stash in the children array.  The $_i keeps track of the index as
			//this is how child Dynamic Terms can map back
			$term_children = array();
			$_i = 0;
			$_col = 0;
			$cleanup_sources = [];
			foreach( $terms as $term ){

				//Find the URL for this term
				$url = get_term_link( $term );
				if( is_wp_error( $url ) ) $url = '#_term';

				$term_item_id = $this->ID . '-term-' . $term->term_id;

				$term_item = new UberMenu_dynamic_term_item(
							$term_item_id,
							$this->item,
							array(
								//'title'	=> '['.$term->name.']',
								'term_id' => $term->term_id,
								'taxonomy_slug' => $term->taxonomy,
								//'attr_title' => $term->name,
								'url'		=> $url,
							),
							array( 'dynamic-term' , 'item-'.$this->ID ),	//classes
							$this->get_settings()
						);


				if( $autocolumns > 0 ){

					if( $_i == 0 || ( isset( $column_map[$_col] ) && $column_map[$_col] == 0 ) ){

						//move on to the next column
						if( $column_map[$_col] == 0 ){
							$_col++;
							//Record items in previous column
							$children[$column_id] = $column_children;
						}

						$column_children = array();
						$column_id = $this->ID . '-col-' . $_col;
						$term_children[] = new UberMenu_dummy_item(
							$column_id ,
							'column' ,
							'Auto Column' ,
							$this->ID,
							array( 'columns' => '1-'.$autocolumns ),
							array( 'ubermenu-autocolumn' )
						);
					}

					//Decrement the number remaining in this column
					$column_map[$_col]--;
					$column_children[] = $term_item;
				}
				else{
					$term_children[] = $term_item;
				}


				//Find the children of this item and remove them, but keep a
				//reference.  They will later be appended to the generated terms instead

				$mykids = false;
				if( isset( $children[$reference_index] ) ){
					$cleanup_sources = $this->replicate_children( $term_item_id, $reference_index, $children, $term_item_id );
				}

				$_i++;
			}

			// Cleanup the items that were duplicated so that they no longer appear in the menu
			//TODO - do this later - now done in replicate_children by sending to trash collector
			// Removed as it broke Tabs > Dynamic > Tabs > Dynamic setup
			// foreach( $cleanup_sources as $source_id ){
			// 	unset($children[$source_id]);
			// }
			// uberp_childmap( $children );

			//If we had an incomplete row (uneven division), tack on the remainder
			if( is_array( $column_children ) && !empty( $column_children ) ){
				$children[$column_id] = $column_children;
			}

			$children[$this->ID] = $term_children;

		}

	}

	function get_start_el(){
		//$this->setupAutoChild();
		//$this->settings['submenu_type_calc'] = 'dynamic-terms';

		//Setup the submenu type
		$submenu_type = 'mega';
		if( $this->depth > 0 ){
			$submenu_type = $this->walker->parent_item()->getSetting( 'submenu_type_calc' );
		}
		$this->settings['submenu_type_calc'] = $submenu_type; // 'dynamic-terms';


		$html = "<!-- begin Dynamic Terms: ".$this->item->title." $this->ID count[$this->term_count] ";
		if( $this->getSetting('dt_ignore_empty_results') === 'on' && $this->term_count === 0 ) $html.= "[ignore empty]";
		$html.= " -->";

		if( $this->term_count == 0 ){
			$empty_results_message = $this->getSetting( 'empty_results_message' );
			if( $empty_results_message ){
				$html.= '<li class="ubermenu-item ubermenu-item-normal"><span class="ubermenu-target ubermenu-target-empty-terms">'.
								$empty_results_message .
								'</span></li>';
			}
		}

		if( $this->notice ){
			$html.= '<li class="ubermenu-item">'.ubermenu_admin_notice( $this->notice , false ).'</li>';
		}

		return $html;
	}
	function get_end_el(){
		//$this->resetAutoChild();

		$html = '';

		//$view_all_taxonomy = 'location'; //
		$view_all_taxonomy = $this->getSetting( 'dt_view_all' );	//'menu-cats'; //

		if( $view_all_taxonomy && $view_all_taxonomy != 'none' ){

			$term_id = 0;

			//Use Parent
			$parent = $this->getSetting( 'dt_parent' );

			if( $parent ){
				if( $parent == -1 ){
					$parent = $this->walker->find_parent_term();
				}
				if( $parent ){
					$term_id = $parent;
				}
			}
			//Use Child Of
			else{
				$child_of = $this->getSetting( 'dt_child_of' );
				if( $child_of == -1 ){
					$child_of = $this->walker->find_parent_term();
				}
				if( $child_of ){
					$term_id = $child_of;
				}
			}


			if( $term_id ){

				$term_url = get_term_link( intval( $term_id ) , $view_all_taxonomy );
				if( !is_wp_error( $term_url ) ){

					$icon_tag = $this->get_menu_op( 'icon_tag' );
					if( !$icon_tag ) $icon_tag = 'i';

					$view_all_link_text = 'View all <'.$icon_tag.' class="fas fa-angle-double-right"></'.$icon_tag.'>';
					$view_all_link_text_setting = $this->getSetting( 'dt_view_all_text' );
					if( $view_all_link_text_setting != '' ){
						$view_all_link_text = $view_all_link_text_setting;
					}
					$html.= '<li class="ubermenu-item ubermenu-item-normal ubermenu-item-view-all"><a href="'.$term_url.'" class="ubermenu-target">'.$view_all_link_text.'</a></li>';
				}
			}
		}

		$html = apply_filters( 'ubermenu_dt_after' , $html , $this->ID , $this->term_args );

		$html.= "<!-- end Dynamic Terms: ".$this->item->title." $this->ID -->";

		return $html;
	}
}







class UberMenuItemDynamicTerm extends UberMenuItemDefault{

	protected $type = 'dynamic_term';
	protected $is_tab = false;

	var $term;

	function get_term_id(){
		return $this->term->term_id;
	}

	function init(){

		//Set Source ID to the original Dynamic Terms Item
		//$this->source_id = $this->item->object_id;


		//Act like one level up, since we've been pushed down 1
		//by the Dynamic Terms Item
		$this->depth--;


		//Term
		$this->term = get_term( $this->item->term_id , $this->item->taxonomy_slug );

		//Branch Prefix
		//$this->branch_prefix = 'term-'.$this->term->term_id.'_';

		// uberp( $this->ID );
		// if( $this->ID === '1025-term-23' ) uberp( $this->walker->parent_item()->getSetting('submenu_column_default') );
		
		if( $this->walker->parent_item() ){
			
			//If this Dynamic Item is a child of a "Tabs" item, it becomes a toggle
			if( $this->walker->parent_item()->getType() == 'tabs' ){

				$this->is_tab = true;

				//Ask the tab to set it up
				$this->walker->parent_item()->setup_tab( $this );


				$this->item_classes[] = 'ubermenu-tab';
				$this->item_classes[] = 'ubermenu-has-submenu-drop';


				$cols = $this->getSetting( 'columns' );
				// if( $this->depth > 0 && $cols == 'auto' ){
				//  	$cols = $this->walker->parent_item()->getSetting( 'submenu_column_default' );
				// }

				//Setup tab content panel columns

				if( $this->depth > 0 && ( !isset( $this->settings['submenu_column_default'] ) || $this->settings['submenu_column_default'] == 'auto' ) ){
					$this->settings['submenu_column_default'] = $this->walker->parent_item()->getSetting( 'submenu_column_default' );
					//if( $this->term->name == 'China' ) uberp( $this->settings );
				}

				//Change specific for Left/Right Tab layouts, so that by default we're full width
				if( $cols == 'auto' ){
					$tab_layout = $this->walker->parent_item()->getSetting( 'tab_layout' );
					if( $tab_layout == 'right' || $tab_layout == 'left' ){
						//$cols = 'full';
						$this->settings['columns'] = 'full';
					}
				}
			}
		}


		//If item is current
		global $wp_query;
		$queried_object_id = (int) $wp_query->queried_object_id;
		$queried_object = $wp_query->get_queried_object();
		if( $this->item->term_id == $queried_object_id && ( $wp_query->is_category || $wp_query->is_tag || $wp_query->is_tax ) && $queried_object->taxonomy == $this->item->taxonomy_slug ){
			$this->item_classes[] = 'ubermenu-current-menu-item';	//hasn't been prefixed yet
		}
	}


	/**
	 * Get the Anchor and its contents
	 * @param  array $atts An array of attributes to add to the anchor
	 * @return string       The HTML for the anchor
	 */
	function get_anchor( $atts ){

		$term = $this->term;
		//up( $term );

		$a = '';
		$tag = 'a';

		//The parent item is the Dynamic Term item, so the grandparent item is what we seek
		$parent_item = false;
		if( $this->depth >= 2 ) $parent_item = $this->walker->grandparent_item();

		//Image
		//$image = $this->get_image();
		$this->settings['item_image'] = apply_filters( 'ubermenu_dt_image' , '' , $this->ID , $term );
		$image = $this->get_image();
		if( $image ) $atts['class'] .= ' ubermenu-target-with-image';


		//Icon
		$icon = $this->getSetting( 'icon' );
		if( $icon ){
			$atts['class'] .= ' ubermenu-target-with-icon';

			$icon_tag = $this->get_menu_op( 'icon_tag' );
			if( !$icon_tag ) $icon_tag = 'i';
			$icon = '<'.$icon_tag.' class="ubermenu-icon '.$icon.'"></'.$icon_tag.'>';
		}


		//Layout
		$layout = $this->getSetting( 'item_layout' );
		$atts['class'].= ' ubermenu-item-layout-'.$layout;



		//Content Align
		$content_align = $this->getSetting( 'content_alignment' );
		//Check inherit from parent
		if( $content_align == 'default' ){

			if( $this->depth >= 2 ){
				$submenu_item_content_alignment = 'default';

				//If this is within a menu segment and we need to inherit back further, pass true
				if( $parent_item->getType() == 'menu_segment' ){
					$submenu_item_content_alignment = $parent_item->getSetting( 'submenu_item_content_alignment' , true );
				}
				else{
					$submenu_item_content_alignment = $parent_item->getSetting( 'submenu_item_content_alignment' );
				}

				if( $submenu_item_content_alignment !== 'default' ){
					$content_align = $submenu_item_content_alignment;
				}
			}
		}
		//If a content alignment has been set on this item or the parent, set the class
		if( $content_align != 'default' ){
			$atts['class'].= ' ubermenu-content-align-'.$content_align;
		}



		//ITEM LAYOUT
		if( $layout == 'default' ){

			//If the layout for the individual item is set to default, and this is a child item, check the parent item's setting
			if( $this->depth >= 2 ){
				$submenu_item_layout = 'default';
				if( $parent_item->getType() == 'menu_segment' ){
					$submenu_item_layout = $parent_item->getSetting( 'submenu_item_layout' , true );
				}
				else{
					$submenu_item_layout = $parent_item->getSetting( 'submenu_item_layout' );
				}

				if( $submenu_item_layout !== 'default' ){
					$layout = $submenu_item_layout;
				}
			}

			//If the layout hasn't been determined yet, and we're using an image, check to see if there's a default image layout
			if( $layout == 'default' && $image ){
				$layout = $this->get_menu_op( 'image_layout_default' );
				if( !$layout ) $layout = 'image_left';
			}
			//If there's an icon, use the default icon layout
			else if( $icon ){
				if( function_exists( 'ubermenu_icon_layout_default' ) ){
					$layout = ubermenu_icon_layout_default( $this );
				}
				else $layout = 'icon_left';
			}
			//If nothing else has claimed it, we default to text_only
			else if( $layout == 'default' ){
				$layout = 'text_only';
			}

			$atts['class'].= ' ubermenu-item-layout-'.$layout;
		}





		$layout_order = ubermenu_get_item_layouts( $layout );
		if( !$layout_order ){
			ubermenu_admin_notice( __( 'Unknown layout order:', 'ubermenu' ).' '.$layout.' ['.$this->item->title.'] ('.$this->ID.')' );
		}

		//No wrap
		if( $this->getSetting( 'no_wrap' ) == 'on' ){
			$atts['class'].= ' ubermenu-target-nowrap';
		}

		//Disable Submenu Indicator
		$disable_submenu_indicator = false;
		if( $this->getSetting( 'disable_submenu_indicator' ) == 'on' ){
			$atts['class'].= ' ubermenu-noindicator';
		}

		//Global Submenu Indicators
		$display_submenu_indicators = $this->get_menu_op('display_submenu_indicators') === 'on' ? true : false;


		//Target ID
		$target_id = $this->getSetting( 'target_id' );
		if( $target_id ){
			$atts['id'] = $target_id;
		}

		//Target Class
		$target_class = $this->getSetting( 'target_class' );
		if( $target_class ){
			$atts['class'].= ' '.$target_class;
		}



		//Disabled Link (change tag)
		$disable_link = false;
		if( $this->getSetting( 'disable_link' ) == 'on' ){
			$tag = 'span';
			$disable_link = true;
			unset( $atts['href'] );
		}




		//Title
		$title = '';
		if( $this->getSetting( 'disable_text' ) == 'off' ){
			$title .= '<span class="ubermenu-target-title ubermenu-target-text">';
			$title .= $term->name; //apply_filters( 'the_title', $term->name, $this->item->ID );

			if( $this->getSetting( 'dt_display_term_counts' ) == 'on' ){
				$title .= ' <span class="ubermenu-term-count">'. UBERMENU_TERM_COUNT_WRAP_START .$term->count. UBERMENU_TERM_COUNT_WRAP_END.'</span>';
			}
			//$title .= ' ['. $term->term_id .'] ['.$this->ID.']';
			$title .= '</span>';
		}
		else{
			//Flag items with disabled text
			$atts['class'].= ' ubermenu-item-notext';
		}


		//Description
		$description = '';
		if( $this->getSetting( 'dt_display_term_description' ) == 'on' ){
			$this->item->description = $term->description;
		}
		if( $this->item->description ){
			$description.= '<span class="ubermenu-target-description ubermenu-target-text">';
			$description.= $this->item->description;
			$description.= '</span>';
		}


		//Dynamic Subcontent
		$description = apply_filters( 'ubermenu_dt_subcontent' , $description , $term , $this->ID );


		//ARIA controls
		if( ubermenu_op( 'aria_controls' , 'general' ) == 'on' ){
			$atts['aria-controls'] = $this->get_submenu_id();
		}


		$atts = apply_filters( 'ubermenu_anchor_attributes' , $atts , 'ubermenu-dynamic-term' , $this->source_id /* Menu Item ID */ , 'term' /* object type */ , $term->term_id /* Post ID */ );

		//Anchor Attributes
		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}



		//Check if we still have something to print
		if( !$title && !$description && !$image && !$icon ){
			return '';
		}


		//Build the Layout

		//Get custom pieces
		$custom_pieces = array();
		extract( apply_filters( 'ubermenu_custom_item_layout_data' , $custom_pieces , $layout , $this->ID , $term->term_id ) );

		//Gather all the pieces in the layout order into an array
		$layout_pieces = compact( $layout_order );

		//Output the anchor
		if( isset( $this->args->before ) ) $a .= $this->args->before;
		$a .= '<'.$tag. $attributes .'>';
		if( isset( $this->args->link_before ) ) $a .= $this->args->link_before;

		//Add pieces based on layout order
		foreach( $layout_pieces as $piece ){
			$a.= $piece;
		}

		// Badge
		$badge = $this->get_badge();
		if( $badge ){
			$a.= $badge;
		}

		//Submenu indicator
		$a.= $this->get_submenu_indicator( $disable_submenu_indicator );
		// $submenu_type = $this->get_submenu_type();
		// if( $display_submenu_indicators && !$disable_submenu_indicator && $submenu_type && in_array( $submenu_type , array( 'mega' , 'flyout' , 'tab-content-panel' ) ) ){
		// 	$indicator_tag = $this->get_menu_op( 'icon_tag' );
		// 	if( !$indicator_tag ) $indicator_tag = 'i';
		// 	$a.= "<$indicator_tag class='ubermenu-sub-indicator fas fa-angle-down'></$indicator_tag>";
		// }

		if( isset( $this->args->link_after ) ) $a .= $this->args->link_after;
		$a .= '</'.$tag.'>';
		if( isset( $this->args->after ) ) $a .= $this->args->after;

		return $a;
	}

	// function get_dynamic_term_image_id(){
	// 	return ubermenu_get_woocommerce_product_category_image_id( $this->term->term_id );
	// }

	function setup_trigger(){

		$trigger = $this->getSetting( 'item_trigger' );

		if( $this->is_tab ){
			//If auto, get trigger from Tabs Group
			if( !$trigger || $trigger == 'auto' ){
				$trigger = $this->walker->grandparent_item()->getSetting( 'tabs_trigger' );
			}
		}

		if( $trigger && $trigger != 'auto' ){
			$this->item_atts['data-ubermenu-trigger'] = $trigger;
		}
	}

	function get_settings(){
		if( isset( $this->settings ) ) return $this->settings;

		$settings = $this->item->parent_settings;
		$settings = apply_filters( 'ubermenu_item_settings' , $settings , $this->ID );

		return $settings;
	}

	function initialize_submenu(){
		parent::initialize_submenu();
		// Add in the "ubermenu-submenu-id-122" in addition to "ubermenu-submenu-id-122-term-21" so that bkg images will work
		$this->submenu_classes[] = 'ubermenu-submenu-id-'.$this->item->object_id;
	}


	function get_inherited_submenu_column_default(){
		// uberp( $this->item->title . ' :: ' . $this->walker->grandparent_item()->getType() . ' :: ' . $this->walker->grandparent_item()->getSetting( 'submenu_column_default' ));
		
		// The parent is still the base Dynamic Terms item, the grandparent is what we would inherit from
		return $this->walker->grandparent_item()->getSetting( 'submenu_column_default' );
	}

}
