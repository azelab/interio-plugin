<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.


/**
 * Register Post Type and Taxonomies
 *
 * @since 1.0
 */
function tt_portfolio_module_cpt() {

	$with_front = $portfolio_cpt_slug = $portfolio_cpt_cat_slug = $portfolio_cpt_single = $single_args = '';

	if( function_exists( 'interio_rs_get_option' ) ) {
		$with_front = interio_rs_get_option( 'cpt_with_front', '0' );
		$portfolio_cpt_slug = interio_rs_get_option( 'portfolio_cpt_slug', 'portfolio-item' );
		$portfolio_cpt_cat_slug = interio_rs_get_option( 'portfolio_cpt_cat_slug', 'portfolio-cats' );
		$portfolio_cpt_single = interio_rs_get_option( 'portfolio_cpt_single', true );
	}
	// With Front
	if ( ! empty ( $with_front )  ) $with_front = true; else $with_front = false;

	// Single page
	if ( ! empty ( $portfolio_cpt_single )  ) { // single item true
		$single_args =  array('show_ui' => true) ;
	} else {
		$single_args =  array(
						'exclude_from_search' => true,
						'show_in_admin_bar'   => false,
						'show_in_nav_menus'   => false,
						'publicly_queryable'  => false,
						'query_var'           => false,
		) ;
	}


	/**
	 * Register Post Type
	 */

	// Arguments
	$cpt_args = array(
		'labels' => array(
			'name' => esc_attr__( 'Portfolio', 'interio' ),
			'singular_name' => esc_attr__( 'Portfolio', 'interio' ),
			'add_new' => esc_attr__( 'Add Portfolio', 'interio' ),
			'add_new_item' => esc_attr__( 'Add Portfolio', 'interio' ),
			'edit' => esc_attr__( 'Edit', 'interio' ),
			'edit_item' => esc_attr__( 'Edit Portfolio', 'interio' ),
			'new_item' => esc_attr__( 'New Portfolio', 'interio' ),
			'view' => esc_attr__( 'View Portfolio', 'interio' ),
			'view_item' => esc_attr__( 'View Portfolio', 'interio' ),
			'search_items' => esc_attr__( 'Search Portfolio', 'interio' ),
			'not_found' => esc_attr__( 'No Portfolio found', 'interio' ),
			'not_found_in_trash' => esc_attr__( 'No Portfolio found in Trash', 'interio' ),
			'parent' => esc_attr__( 'Parent Portfolio', 'interio' ),
		),
		'public' => true,
		'rewrite' => array( 'slug' => $portfolio_cpt_slug, 'with_front' => $with_front ),
		'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
	);

	// Single pages to be shown or not.
	$cpt_args = array_merge( $cpt_args, $single_args );

	// Apply filters
	$cpt_args = apply_filters( 'tt_portfolio_cpt_args', $cpt_args );

	// Register Post Type
	register_post_type( 'tt_portfolio', $cpt_args );

	/**
	 * Register Taxonomy ( Category )
	 */

	// Arguments
	$tax_args = array(
		'labels' => array(
			'name' => esc_attr__( 'Portfolio Categories', 'interio' ),
			'singular_name' => esc_attr__( 'Category', 'interio' ),
			'search_items'  => esc_attr__( 'Search Categories', 'interio' ),
			'all_items' => esc_attr__( 'All Categories', 'interio' ),
			'parent_item' => esc_attr__( 'Parent Category', 'interio' ),
			'parent_item_colon' => esc_attr__( 'Parent Category:', 'interio' ),
			'edit_item' => esc_attr__( 'Edit Category', 'interio' ),
			'update_item' => esc_attr__( 'Update Category', 'interio' ),
			'add_new_item' => esc_attr__( 'Add New Category', 'interio' ),
			'new_item_name' => esc_attr__( 'New Category Name', 'interio' ),
			'menu_name' => esc_attr__( 'Categories', 'interio' ),
		),
		'hierarchical' => true,
		'public' => true,
		'rewrite' => array(
			'slug' => $portfolio_cpt_cat_slug,
			'with_front' => $with_front
		),
	);

	// Apply filters
	$tax_args = apply_filters( 'tt_portfolio_cats_args', $tax_args );

	// Register Taxonomy
	register_taxonomy( 'tt_portfolio_cats', 'tt_portfolio', $tax_args );



} add_action( 'init', 'tt_portfolio_module_cpt', 0 );



// Creating meta boxes for this CPT

/*-----------------------------------------------------------------------------------*/
/* CS meta boxes override                                                            */
/*-----------------------------------------------------------------------------------*/
// framework options filter example
if( !function_exists( 'interio_rs_portfolio_metas_opt' )) {
	function interio_rs_portfolio_metas_opt( $options ) {

// Lets create options that we will use mostly, in page, product, pages
	$interio_rs_portfolio_meta =  array(
				// begin: a section
				array(
					'name'   => 'section_1',
					'title'  => 'General Settings',
					'icon'   => 'fa fa-cog',
					// begin: fields
					'fields' => array(
						array(
						  'type'    => 'notice',
						  'class'   => 'info',
						  'content' => 'The main image for this item can be added from right side featured image block.',
						),
					/*	array(
						  'id'          => '_single_port_gallery',
						  'type'        => 'gallery',
						  'title'       => 'Gallery',
						  'add_title'   => 'Add Images',
						  'edit_title'  => 'Edit Images',
						  'clear_title' => 'Remove Images',
						  'desc'        => 'These images appears on single item page. These can be used to provide additional images for this item.',
						),*/
						array(
						  'id'      => '_single_port_url', // another unique id
						  'type'    => 'text',
						  'title'   => 'Custom URL',
						  'desc'    => 'By default links in the this item block will link to single item page. If you want them to go to a custom URL, enter it here',
						 //'help'    => 'Write something',
						 //'default' => 'do stuff',
						),
						array(
						  'id'        => '_single_item_layout',
						  'type'      => 'image_select',
						  'title'     => 'Item layout.',
						  'desc'      => 'Control the sidebar on this item single page. If you leave the first one selected, it will grab default setting for sidebar from Themeoptions/Layout.',
						  'options'   => array(
						    'layout-off'             => esc_url( trailingslashit( get_template_directory_uri() ) ) .'inc/images/layout-off.png',
						    'layout-right-content'   => esc_url( trailingslashit( get_template_directory_uri() ) ) .'inc/images/2cl.png',
						    'layout-left-content'    => esc_url( trailingslashit( get_template_directory_uri() ) ) .'inc/images/2cr.png',
						    'layout-full-content'    => esc_url( trailingslashit( get_template_directory_uri() ) ) .'inc/images/1col.png',
						  ),
						  'default'   => 'layout-off',
						),
						), // end: fields
				) // end: a section
	);

/* Start creating meta options. */

// -----------------------------------------
// Metabox Options                    -
// -----------------------------------------

		$options[] = array(
			'id'        => '_tt_meta_page_opt',
			'title'     => 'Portfolio Options',
			'post_type' => 'tt_portfolio',
			'context'   => 'normal',
			'priority'  => 'default',
			'sections'  => $interio_rs_portfolio_meta

		);

		return $options;

	}

	add_filter( 'cs_metabox_options', 'interio_rs_portfolio_metas_opt', 20 );

}


