<?php
/**
 * Custom functions that act independently of the theme templates.
 *
 * @package RED_Starter_Theme
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function red_starter_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	return $classes;
}
add_filter( 'body_class', 'red_starter_body_classes' );


// Remove "Editor" links from sub-menus
function inhabitent_remove_submenus() {
    remove_submenu_page( 'themes.php', 'theme-editor.php' );
    remove_submenu_page( 'plugins.php', 'plugin-editor.php' );
}
add_action( 'admin_menu', 'inhabitent_remove_submenus', 110 );


// Custom login logo
function inhabitent_custom_login_logo() {
	echo '<style type="text/css">
		#login h1 a { 
			background-image: url('. get_stylesheet_directory_uri() . '/images/inhabitent-logo-text-dark.svg) !important; 
			height: 120px !important; 
			background-position: center !important; 
			background-size: contain !important; 
			width: 100% !important; 
		}
		</style>';
}
add_action( 'login_head', 'inhabitent_custom_login_logo' );


// Custom login logo url
function inhabitent_custom_login_logo_url( $url ) {
    return home_url();
}
add_filter( 'login_headerurl', 'inhabitent_custom_login_logo_url' );


// Custom login logo url title
function inhabitent_login_logo_url_title() {
    return 'Inhabitent Camping Supply Co.';
}
add_filter( 'login_headertitle', 'inhabitent_login_logo_url_title' );

// Return 16 items on Product Archive Page and Order by Ascending Title Order
function inhabitent_modify_archive_query( $query ) {
    if ( is_post_type_archive ( 'product' ) || $query->is_tax( 'product-type' ) && !is_admin() && $query->is_main_query() ) {
        $query->set( 'posts_per_page', '16' );
				$query->set( 'orderby', 'title' );
        $query->set( 'order', 'ASC' );
    }
}
add_action( 'pre_get_posts', 'inhabitent_modify_archive_query' );

// Filter Archive Titles
function inhabitent_archive_title_filter($title)
{
    if (is_post_type_archive('product')) {
        $title = 'Shop Stuff';
	} elseif (is_post_type_archive('adventures')) {
		$title = 'Latest Adventures';
    } elseif (is_tax('product-type')) {
        $title = single_term_title( '', false );
    }
    return $title;
}
add_filter( 'get_the_archive_title', 'inhabitent_archive_title_filter' );

// Custom Header Upload for About Page
function inhabitent_custom_header_upload() {
	wp_enqueue_style(
		'custom-style',
	get_template_directory_uri() . '/about.php'
	);

	$about_hero_url = CFS()->get( 'hero_image' );
	if ( ! $about_hero_url ) {
		$about_hero_style = ".about-header {
			background-color: grey;
			height: 100vh;
		}";
	} else {
		$about_hero_style = ".about-header {
        background: linear-gradient( to bottom, rgba(0,0,0,0.4) 0%, rgba(0,0,0,0.4) 100% ), url({$about_hero_url}) no-repeat center bottom;
        background-size: cover, cover;
		height: 100vh;
		display: flex;
    	}";
	}
wp_add_inline_style('custom-style', $about_hero_style);
}
add_action( 'wp_enqueue_scripts', 'inhabitent_custom_header_upload');
