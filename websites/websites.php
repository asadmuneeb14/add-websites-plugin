<?php
/**
* Plugin Name: websites
* Plugin URI: https://www.your-site.com/
* Description: Websites Form.
* Version: 0.1
* Author: Asad Muneeb
* Author URI: https://www.your-site.com/
**/

include plugin_dir_path( __FILE__ ) . 'functions.php';

function wp_website_css() {
    $plugin_url = plugin_dir_url( __FILE__ );
    wp_enqueue_style( 'style', $plugin_url . 'css/style.css' );
}
add_action( 'wp_enqueue_scripts', 'wp_website_css' );

register_activation_hook( __FILE__, 'create_page_on_activation' );
function create_page_on_activation() {
  
  if ( ! current_user_can( 'activate_plugins' ) ) return;
  
  global $wpdb;
  
  if ( null === $wpdb->get_row( "SELECT post_name FROM {$wpdb->prefix}posts WHERE post_name = 'add-websites'", 'ARRAY_A' ) ) {
     
    $current_user = wp_get_current_user();
    
    // create post object
    $page = array(
      'post_title'  => __( 'Add Websites' ),
      'post_content'   => '[Add_Website]',
      'post_status' => 'publish',
      'post_author' => $current_user->ID,
      'post_type'   => 'page',
    );
    
    // insert the post into the database
    wp_insert_post( $page );
  }
}

function post_type_sttings(){
	global $_wp_post_type_features;

    $post_type="websites";
    unset($_wp_post_type_features[$post_type]['editor']);
	
    remove_post_type_support($post_type, 'thumbnail');
}
add_action('init', 'post_type_sttings', 11);


//making the meta box (Note: meta box != custom meta field)
function wp_add_custom_meta_box() {
	if( current_user_can('administrator') ){
	   add_meta_box(
		   'website_meta_box',       // $id
		   'Source Code',                  // $title
		   'show_website_meta_box',  // $callback
		   'websites',                 // $page
		   'normal',                  // $context
		   'high'                     // $priority
	   );
	}
}
add_action('add_meta_boxes', 'wp_add_custom_meta_box');

function show_website_meta_box() {
    global $post;
	$content = get_the_content();
	$data = file_get_contents($content);
		?>
		<textarea rows="7" name="wp_source_code" readonly><?php echo htmlspecialchars($data);?></textarea>
		<?php
}