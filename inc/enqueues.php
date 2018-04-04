<?php
add_action( 'wp_enqueue_scripts', 'FLChildTheme::enqueue_scripts' );

/**
 * Helper class for child theme functions.
 *
 * @class FLChildTheme
 */
final class FLChildTheme {

	/**
	 * Enqueues scripts and styles.
	 *
	 * @return void
	 */
	static public function enqueue_scripts() {
		wp_enqueue_style( 'beaver-builder', PARENT_BASE_URI . '/style.css' );

		wp_enqueue_script( 'unprocessed-js', THEME_BASE_URI . '/js/main.js', array( 'jquery' ), THEME_VERSION, true );

		wp_enqueue_style( 'unprocessed-css', THEME_BASE_URI . '/css/main.css', array(), THEME_VERSION, 'all' );

		wp_enqueue_script( 'gulpfile-js', THEME_BUILD_URI . PROJECT . '.min.js', array( 'jquery' ), THEME_VERSION, true );

		wp_enqueue_style( 'gulpfile-css', THEME_BUILD_URI . PROJECT . '.min.css', array(), THEME_VERSION, 'all' );

		if ( defined( 'GOOGLE_FONTS' ) ) {
			wp_enqueue_style( 'google-fonts', '//fonts.googleapis.com/css?family=' . GOOGLE_FONTS );
		}

		wp_enqueue_style( 'child-theme', get_stylesheet_uri() );
	}
}

function mti_enqueue_admin_scripts( $hook ) {
	if ( 'tools_page_mail-to-artist' != $hook ) {
		return;
	}

	wp_enqueue_style( 'fontawesome-css', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css' );
	wp_enqueue_style( 'bootstrap-min-css', get_stylesheet_directory_uri() . '/vendor/css/bootstrap.min.css' );
	wp_enqueue_style( 'bootstrap-theme-min-css', get_stylesheet_directory_uri() . '/vendor/css/bootstrap-theme.min.css' );
	wp_register_style( 'mti_admin_css', get_stylesheet_directory_uri() . '/css/mti-admin-style.css', false, THEME_VERSION );
	wp_enqueue_style( 'mti_admin_css' );
//	wp_enqueue_script( 'mti-google-script', 'https://apis.google.com/js/api.js', array(), THEME_VERSION, true  );
	wp_enqueue_script( 'mti_admin_custom_script', get_stylesheet_directory_uri() . '/js/mti-admin.js', array( 'jquery' ), THEME_VERSION, true );
	wp_enqueue_script( 'mti-google-script', 'https://apis.google.com/js/client.js?onload=handleClientLoad', array( 'mti_admin_custom_script' ), '', true  );


	wp_enqueue_script( 'bootstrap-js', get_stylesheet_directory_uri() . '/vendor/js/bootstrap.min.js' ,array( 'jquery' ) ,THEME_VERSION , true );
}
add_action( 'admin_enqueue_scripts', 'mti_enqueue_admin_scripts' );