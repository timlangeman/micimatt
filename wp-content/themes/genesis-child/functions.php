<?php
//* Start the engine
include_once( get_template_directory() . '/lib/init.php' );

//* Child theme (do not remove)
define( 'CHILD_THEME_NAME', 'Genesis: openpolitics child theme' );
define( 'CHILD_THEME_URL', 'http://www.openpolitics.com/tim' );
define( 'CHILD_THEME_VERSION', '2.2.2' );

//add_filter('wp_recaptcha_required','__return_false');

add_filter( 'jetpack_sharing_counts', '__return_false', 99 );
add_filter( 'jetpack_implode_frontend_css', '__return_false', 99 );

add_action( 'wp_enqueue_scripts', 'my_child_theme_scripts' );
function my_child_theme_scripts() {
    wp_enqueue_style( 'parent-theme-css', get_template_directory_uri() . '/style.css' );
    // wp_enqueue_style( 'bootstrap-css', get_stylesheet_directory_uri() . '/bootstrap.min.css');
    wp_enqueue_style( 'child-theme-css', get_stylesheet_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-theme-custom-css', get_stylesheet_directory_uri() . '/custom.css' );
}


add_filter( 'jetpack_subscriptions_exclude_these_categories', 'exclude_these' );
function exclude_these( $categories ) {
$categories = array( 'links', 'category-links');
return $categories;
}


/********************** Link **************************/
function cptui_register_my_cpts_links() {

	/**
	 * Post Type: links.
	 */

	$labels = [
		"name" => esc_html__( "links", "custom-post-type-ui" ),
		"singular_name" => esc_html__( "reference", "custom-post-type-ui" ),
	];

	$args = [
		"label" => esc_html__( "links", "custom-post-type-ui" ),
		"labels" => $labels,
		"description" => "a link with related material",
		"public" => true,
		"publicly_queryable" => false,
		"show_ui" => true,
		"show_in_rest" => false,
		"rest_base" => "",
		"rest_controller_class" => "WP_REST_Posts_Controller",
		"rest_namespace" => "wp/v2",
		"has_archive" => false,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"delete_with_user" => false,
		"exclude_from_search" => false,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"can_export" => true,
		"rewrite" => true,
		"query_var" => true,
		"supports" => [ "title", "editor", "comments", "author", "page-attributes" ],
		"taxonomies" => [ "category", "post_tag" ],
		"show_in_graphql" => false,
	];

	register_post_type( "links", $args );
}

add_action( 'init', 'cptui_register_my_cpts_links' );

/********************** End: Link *************************/

function query_post_type($query) {
  //Credit:  https://gist.github.com/boyvanamstel/1055880
  if(is_category() || is_tag()) {
    $post_type = get_query_var('post_type');
    if($post_type) {
      $post_type = $post_type;
    } else {
      $post_type = array('nav_menu_item','post','links','interviews');
    }
    $query->set('post_type',$post_type);
    return $query;
  }
}
add_filter('pre_get_posts', 'query_post_type');


function get_link_source($post) {
	$link = array();
	foreach ( (array) get_object_taxonomies($post->post_type) as $taxonomy ) {
	  $object_terms = wp_get_object_terms($post->ID, $taxonomy, array('fields' => 'all'));
	  if ($object_terms) {
		if ($taxonomy == 'url') {
	    	foreach ($object_terms as $term) {
				$url = $term->name;
				$parse_url = parse_url($url);
				$link['host'] = $parse_url['host'];
				$link['url'] = $url;
				return $link;
	      		//echo '<p><a href="' . esc_attr(get_term_link($term, $taxonomy)) . '" title="' . sprintf( __( "View all posts in %s" ), $term->name ) . '" ' . '>' . $term->name.'</a><p> ';
	    	}
		}
	  }
	}
}

// Numbered Pagination
if ( !function_exists( 'wpex_pagination' ) ) {

	function wpex_pagination() {

		$prev_arrow = is_rtl() ? '&rarr;' : '&larr;';
		$next_arrow = is_rtl() ? '&larr;' : '&rarr;';

		global $wp_query;
		$total = $wp_query->max_num_pages;
		$big = 999999999; // need an unlikely integer
		if( $total > 1 )  {
			 if( !$current_page = get_query_var('paged') )
				 $current_page = 1;
			 if( get_option('permalink_structure') ) {
				 $format = 'page/%#%/';
			 } else {
				 $format = '&paged=%#%';
			 }
			echo paginate_links(array(
				'base'			=> str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
				'format'		=> $format,
				'current'		=> max( 1, get_query_var('paged') ),
				'total' 		=> $total,
				'mid_size'		=> 3,
				'type' 			=> 'list',
				'prev_text'		=> $prev_arrow,
				'next_text'		=> $next_arrow,
			 ) );
		}
	}

}




//* Add HTML5 markup structure from Genesis
add_theme_support( 'html5' );

add_theme_support( 'post-thumbnails' );

//* Add HTML5 responsive recognition
add_theme_support( 'genesis-responsive-viewport' );


if ( ! function_exists( 'theme_special_nav' ) ) {
    function theme_special_nav() {
        //  Do something.
    }
}



add_filter( 'wp_update_attachment_metadata', 'rips_unlink_tempfix' );

function rips_unlink_tempfix( $data ) {
    if( isset($data['thumb']) ) {
        $data['thumb'] = basename($data['thumb']);
    }

    return $data;
}


/* HTTP Security Headers
 *
 * Add 6 security headers
 *  *
 * Credit: https://digital.com/blog/wordpress-security-tips/
 *
 * */
header('Content-Security-Policy: default-src \'self\' \'unsafe-inline\' \'unsafe-eval\' https: data:');
header('X-Frame-Options: SAMEORIGIN');
header('X-XSS-Protection: 1; mode=block');
header('Strict-Transport-Security: max-age=315360000; includeSubdomains; preload');
header('X-Content-Type-Options: nosniff');
@ini_set('session.cookie_secure', true);
@ini_set('session.use_only_cookies', true);



add_action( 'rest_api_init', function () {
    $namespace = 'aroma_api';
    register_rest_route( $namespace, '/get_signin', array (
        'methods'       => 'POST',
        'callback'      => 'get_singnin_data',
    ));
} );
function get_singnin_data($data)
{
    global $wpdb;
    $resultdata = array();
    $resultdata['message'] = 'Login SuccessFull';
    return $resultdata;
}


// ACF Block: CiteIt.net Testimonial
add_action('acf/init', 'my_register_blocks');
function my_register_blocks() {

    // check function exists.
    if( function_exists('acf_register_block_type') ) {

        // register a testimonial block.
        acf_register_block_type(array(
            'name'              => 'testimonial',
            'title'             => __('Testimonial'),
            'description'       => __('A custom testimonial block.'),
            'render_callback'   => 'my_acf_block_render_callback',
            'category'          => 'formatting',
        ));
    }
}

/**
 * Testimonial Block Callback Function.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */
function my_acf_block_render_callback( $block, $content = '', $is_preview = false, $post_id = 0 ) {

    // Create id attribute allowing for custom "anchor" value.
    $id = 'testimonial-' . $block['id'];
    if( !empty($block['anchor']) ) {
        $id = $block['anchor'];
    }

    // Create class attribute allowing for custom "className" and "align" values.
    $className = 'testimonial';
    if( !empty($block['className']) ) {
        $className .= ' ' . $block['className'];
    }
    if( !empty($block['align']) ) {
        $className .= ' align' . $block['align'];
    }

    // Load values and assing defaults.
    $text = get_field('testimonial') ?: 'Your testimonial here...';
    $author = get_field('author') ?: 'Author name';
    $role = get_field('role') ?: 'Author role';
    $image = get_field('image') ?: 295;
    $background_color = get_field('background_color');
    $text_color = get_field('text_color');

    ?>
    <div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
        <blockquote class="testimonial-blockquote">
            <span class="testimonial-text"><?php echo $text; ?></span>
            <span class="testimonial-author"><?php echo $author; ?></span>
            <span class="testimonial-role"><?php echo $role; ?></span>
        </blockquote>
        <div class="testimonial-image">
            <?php echo wp_get_attachment_image( $image, 'full' ); ?>
        </div>
        <style type="text/css">
            #<?php echo $id; ?> {
                background: <?php echo $background_color; ?>;
                color: <?php echo $text_color; ?>;
            }
        </style>
    </div>
    <?php
}


?>
