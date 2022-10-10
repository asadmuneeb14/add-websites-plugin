<?php
// Create websites CPT
function websites_post_type() {
	$user = wp_get_current_user();
	$allowed_roles = array( 'administrator', 'editor' );
	if ( array_intersect( $allowed_roles, $user->roles ) ) {
		register_post_type( 'websites',
			array(
				'labels' => array(
					'name' => __( 'Websites' ),
					'singular_name' => __( 'Website' )
				),
				'public' => true,
				'show_in_rest' => true,
				'supports' => array('title', 'editor', 'thumbnail'),
				'has_archive' => true,
				'rewrite'   => array( 'slug' => 'my-websites' ),
				'menu_position' => 5,
				'capability_type' => 'post',
				'capabilities' => array(
					'create_posts' => false,
				 ),
				'map_meta_cap' => true,
			)
		);
	}
}
add_action( 'init', 'websites_post_type' );




// Creating Form for front-end
function add_website_shortcode(  ) {
    $website_id = "";
    if( $_POST['action'] == "new_website") {
       	$title =  $_POST['title'];
        $description = $_POST['description'];
        
        // Add the content of the form to $post as an array
        $new_website = array(
            'post_title'    => $title,
            'post_content'  => $description,
            'post_status'   => 'publish',
            'post_type'     => 'websites'  // Use a custom post type if you want to
        );
        $website_id = wp_insert_post($new_website, true ); 
        if($website_id){
			echo "<div class='alert_web'>Website created successfully.</div>";
        }
    }
    ?>
    <div id="postbox">
        <p class="head">Add Website</p>
        
        <form id="new_website" name="new_website" method="post" action="">
            <p><label for="title">Your Name</label><br />
                <input type="text" required id="title" value="" tabindex="1" size="20" name="title" />
            </p>
            <p><label for="description">Website URL</label><br />
                <input type="url" required id="description" value="" tabindex="3" name="description" />
            </p>
            <p align="right"><input type="submit" value="Submit" tabindex="6" id="submit" name="submit" /></p>
            <input type="hidden" name="action" value="new_website" />
                <?php wp_nonce_field( 'new-post' ); ?>
        </form>
    </div>
    <?php
    //return "<div style='color:black'> foo = foo </div>";
}
add_shortcode( 'Add_Website', 'add_website_shortcode' );
