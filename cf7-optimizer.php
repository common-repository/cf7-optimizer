<?php
/**
 * Plugin Name: CF7 Optimizer
 * Plugin URI: https://github.com/gianlucagaspari/contact-form-7-optimizer
 * Description: Removes js, css and ajax behaviors from Contact Form 7 that may slow the site
 * Version: 1.0.1
 * Author: Five Studio
 * Author URI: https://www.fivestudio.it
 * Tested up to: 5.8
 * Requires: Contact Form 7
 * License: GPLv3 or later
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * Requires PHP: 5.6
 */
function wpcf7_custom_enqueue_scripts() {
	if ( is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) ) {
		wp_dequeue_script( 'wpcf7-recaptcha' );
		wp_dequeue_script( 'google-recaptcha' );
		wp_dequeue_style( 'contact-form-7' );
		global $post;
		if ( isset( $post->post_content ) && has_shortcode( $post->post_content, 'contact-form-7' ) ) {
			if ( function_exists( 'wpcf7_enqueue_scripts' ) ) {
				wpcf7_enqueue_scripts();
				wp_enqueue_script( 'wpcf7-recaptcha' );
				wp_enqueue_script( 'google-recaptcha' );
			}
			if ( function_exists( 'wpcf7_enqueue_styles' ) ) {
				wpcf7_enqueue_styles();
			}
		}
		$service = WPCF7_RECAPTCHA::get_instance();
		if ( ! $service->is_active() ) {
			return;
		}
		wp_add_inline_script( 'contact-form-7', 'wpcf7.cached = 0;', 'before' );
	}
}
add_filter( 'wpcf7_load_js', '__return_false' );
add_filter( 'wpcf7_load_css', '__return_false' );
add_action( 'wp_enqueue_scripts', 'wpcf7_custom_enqueue_scripts', 10000, 0 );
