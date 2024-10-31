<?php 
@ob_start();
/*
	Plugin Name: Remove Gutenberg
	Description: Remove Gutenberg Editor and get back to old version of editor. This provides Original Classic Editor and more.
	Tags: editor, disable gutenberg, gutenberg manager, remove gutenberg, classic editor, block editor, remove gutenberg, gutenberg, disable, blocks, posts, post types
	Author: Ravi A. Vadhel
	Author URI: https://digitalcave.uk
	Donate link: Paypal @vadhel.ravi@gmail.com
	Contributors: Ravi A. Vadhel
	Requires at least: 4.9
	Tested up to: 6.5.5
	Stable tag: 2.2.2
	Version: 2.2.2
	Requires PHP: 7.4
	Text Domain: remove-gutenberg
	Domain Path: /languages
	License: GPL v2 or later
*/
if (!class_exists('removeGutenberg')) {
	
	class removeGutenberg {
		function __construct() {
			if(empty(get_option( 'reenable-gutenberg' ))){
				// disable for posts
				add_filter('use_block_editor_for_post', '__return_false', 10);

				// disable for post types
				add_filter('use_block_editor_for_post_type', '__return_false', 10);
			}
		}
	}
	register_activation_hook( __FILE__, 'activate_gutenberg' );
	function activate_gutenberg(){
		update_option( 'reenable-gutenberg', '' );
	}

	/* Admin init */
	add_action( 'admin_init', 'my_settings_init' );
	function my_settings_init(){
	    register_setting('reading', 'reenable-gutenberg', 'my_settings_sanitize');
	    add_settings_section('gutenberg_section', 'Gutenberg Settings', 'gutenberg_settings', 'reading');
	 	add_settings_field('gutenberg_section-field-id', 'Disable Gutenberg?', 'my_settings_field_callback', 'reading', 'gutenberg_section');
	}
	 
	function my_settings_sanitize( $input ){
	    return isset( $input ) ? true : false;
	}
	 
	function gutenberg_settings(){
	    echo wpautop( "By Installing this plugin, gutenberg will automatically disabled. Check this button if you want to re-enable Gutenberg to your theme." );
	}

	function my_settings_field_callback(){
	    ?>
	    <label for="guternfield"> <input id="guternfield" type="checkbox" value="1" name="reenable-gutenberg" <?php checked( get_option( 'reenable-gutenberg', true ) ); ?>> "Yes"</label>
	    <?php
	    /*add_rm_notice( __('<h4>Hello,</h4>
			<p>my name is <strong>Ravi Vadhel</strong>. I am the developer of <strong>Remove Gutenberg</strong> plugin.<br>If you like this plugin, please write a few words about it at wordpress.org. Your opinion will help other people.</p>
			<p>Thank you!</p>
		

		<p class="removeguternberg-actions">
			<a class="button button-primary" target="_blank" href="https://wordpress.org/support/plugin/restore-classic-editor/reviews/?rate=5&amp;filter=5#new-post">Rate plugin</a>
		</p>'), "success", true );*/
	}
	
	add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'rmgutenberg_settings' );
	function rmgutenberg_settings( $links ) {
	   $links[] = '<a href="'. esc_url( get_admin_url(null, 'options-reading.php#guternfield') ) .'">Settings</a>';
	   $links[] = '<a href="https://wordpress.org/support/plugin/restore-classic-editor/reviews/?rate=5&amp;filter=5#new-post" target="_blank">Review Plugin</a>';
	   return $links;
	}

	function add_rm_notice( $notice = "", $type = "warning", $dismissible = true ) {
	    $notices = get_option( "rm_notices", array() ); 
	    $dismissible_text = ( $dismissible ) ? "is-dismissible" : "";
	 
	    // We add our new notice.
	    array_push( $notices, array( 
	            "notice" => $notice, 
	            "type" => $type, 
	            "dismissible" => $dismissible_text
	        ) );
	    update_option("rm_notices", $notices );
	}
	 
	function display_rm_notices() {
	    $notices = get_option( "rm_notices", array() );
	    foreach ( $notices as $notice ) {
	        printf('<div class="notice notice-%1$s %2$s"><p>%3$s</p></div>',
	            $notice['type'],
	            $notice['dismissible'],
	            $notice['notice']
	        );
	    }
	 	if( ! empty( $notices ) ) {
	        delete_option( "rm_notices", array() );
	    }
	}
	add_action( 'admin_notices', 'display_rm_notices', 12 );
	
	return $removeGutenberg = new removeGutenberg(); 
}
?>