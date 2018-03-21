<?php
//* Start the engine
include_once( get_template_directory() . '/lib/init.php' );

//* Setup Theme
include_once( get_stylesheet_directory() . '/lib/theme-defaults.php' );

//* Set Localization (do not remove)
load_child_theme_textdomain( 'news', apply_filters( 'child_theme_textdomain', get_stylesheet_directory() . '/languages', 'news' ) );

//* Child theme (do not remove)
define( 'CHILD_THEME_NAME', __( 'News Pro Theme', 'news' ) );
define( 'CHILD_THEME_URL', 'http://my.studiopress.com/themes/news/' );
define( 'CHILD_THEME_VERSION', '3.0.2' );

//* Add HTML5 markup structure
add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list' ) );

//* Add viewport meta tag for mobile browsers
add_theme_support( 'genesis-responsive-viewport' );





//* Enqueue Scripts
add_action( 'wp_enqueue_scripts', 'news_load_scripts' );
function news_load_scripts() {

	wp_enqueue_script( 'news-responsive-menu', get_bloginfo( 'stylesheet_directory' ) . '/js/responsive-menu.js', array( 'jquery' ), '1.0.0' );

	wp_enqueue_style( 'dashicons' );

	wp_enqueue_style( 'google-fonts', '//fonts.googleapis.com/css?family=Raleway:400,700|Pathway+Gothic+One', array(), CHILD_THEME_VERSION );

}

//* Add new image sizes
add_image_size( 'home-bottom', 150, 150, TRUE );
add_image_size( 'home-middle', 348, 180, TRUE );
add_image_size( 'home-top', 740, 400, TRUE );

//* Add support for custom background
add_theme_support( 'custom-background' );

//* Add support for custom header
add_theme_support( 'custom-header', array(
	'header_image'    => '',
	'header-selector' => '.site-title a',
	'header-text'     => false,
	'height'          => 90,
	'width'           => 260,
) );

//* Add support for additional color style options
add_theme_support( 'genesis-style-selector', array(
	'news-pro-blue'   => __( 'News Pro Blue', 'news' ),
	'news-pro-green'  => __( 'News Pro Green', 'news' ),
	'news-pro-pink'   => __( 'News Pro Pink', 'news' ),
	'news-pro-orange' => __( 'News Pro Orange', 'news' ),
) );

//* Add support for 6-column footer widgets
add_theme_support( 'genesis-footer-widgets', 6 );

//* Reposition the secondary navigation
remove_action( 'genesis_after_header', 'genesis_do_subnav' );
add_action( 'genesis_before_header', 'genesis_do_subnav' );

//* Hook after entry widget after the entry content
add_action( 'genesis_after_entry', 'news_after_entry', 5 );
function news_after_entry() {

	if ( is_singular( 'post' ) )
		genesis_widget_area( 'after-entry', array(
			'before' => '<div class="after-entry" class="widget-area">',
			'after'  => '</div>',
		) );

}


add_theme_support( 'genesis-footer-widgets', 3 );


//* Remove comment form allowed tags
add_filter( 'comment_form_defaults', 'news_remove_comment_form_allowed_tags' );
function news_remove_comment_form_allowed_tags( $defaults ) {

	$defaults['comment_notes_after'] = '';
	return $defaults;

}

//* Register widget areas
genesis_register_sidebar( array(
	'id'          => 'home-top',
	'name'        => __( 'Home - Top', 'news' ),
	'description' => __( 'This is the top section of the homepage.', 'news' ),
) );
genesis_register_sidebar( array(
	'id'          => 'home-middle-left',
	'name'        => __( 'Home - Middle Left', 'news' ),
	'description' => __( 'This is the middle left section of the homepage.', 'news' ),
) );
genesis_register_sidebar( array(
	'id'          => 'home-middle-right',
	'name'        => __( 'Home - Middle Right', 'news' ),
	'description' => __( 'This is the middle right section of the homepage.', 'news' ),
) );
genesis_register_sidebar( array(
	'id'          => 'home-bottom',
	'name'        => __( 'Home - Bottom', 'news' ),
	'description' => __( 'This is the bottom section of the homepage.', 'news' ),
) );
genesis_register_sidebar( array(
	'id'          => 'after-entry',
	'name'        => __( 'After Entry', 'news' ),
	'description' => __( 'This is the after entry section.', 'news' ),
) );



genesis_register_sidebar( array(

	'id'			=>	'schools',

	'name'			=>	__( 'Schools', 'education' ),

	'description'	=>	__( 'This is the school facts section.', 'education' ),

) );





// Remove the post meta function
remove_action( 'genesis_after_post_content', 'genesis_post_meta' );


//Remove comments
//remove_action( 'genesis_after_post', 'genesis_get_comments_template' );


class Fast_Facts extends WP_Widget {
	/** constructor */
	function __construct() {
		parent::WP_Widget( /* Base ID */'school-fast-facts', /* Name */'School Fast Facts', array( 'description' => 'A School Fast Facts Widget' ) );
	}

	/** @see WP_Widget::widget */
	function widget( $args, $instance ) {

		global $post;

		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget; ?>

       	<?php if ( $title ) echo $before_title . $title . $after_title; ?>


          <div id="school-facts">

                <?php

                $shool = new ArrayObject;
                $taxonomies = get_object_taxonomies('schools','names');
                foreach ($taxonomies as $taxonomy ) {
                    $school[$taxonomy] = strip_tags(get_the_term_list( $post->ID, $taxonomy, '', ', ', '' ));
                }

				$state = get_post_meta( $post->ID, 'state', true );

                ?>

            	<table>
                <tr><td>Location:</td><td><?php echo $school['city']; ?>, <?php echo $state; ?></td></tr>
                <tr><td>Year Founded:</td><td><?php echo $school['founded']; ?></td></tr>
                <tr><td>Number of Students:</td><td><?php echo $school['size']; ?></td></tr>
                <tr><td>Cost:</td><td><?php echo $school['cost']; ?></td></tr>
                <tr><td>Total Fees:</td><td><?php echo '$ '.number_format($school['total_fees']); ?></td></tr>
                <tr><td>Retention Rate (%):</td><td><?php echo $school['retention']; ?></td></tr>
                <tr><td>Reported Test Type:</td><td><?php echo $school['test_type']; ?></td></tr>
                <tr><td>Test Scores (75% percentile):</td><td><?php echo $school['test_75']; ?></td></tr>
                <tr><td>Test Scores (25% percentile):</td><td><?php echo $school['test_25']; ?></td></tr>
                <tr><td>Freshman in Top 10% of HS Class (%):</td><td><?php echo $school['top_10_hs']; ?></td></tr>
                <tr><td>Admit Rate (%):</td><td><?php echo $school['acceptance']; ?></td></tr>
                <tr><td>School Selectivity:</td><td><?php echo $school['selectivity']; ?></td></tr>
								<tr><td>4 Year Graduation Rate:</td><td><?php echo $school['graduation']; ?></td></tr>


                </table>

            </div>


		<?php echo $after_widget;
	}

	/** @see WP_Widget::update */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		return $instance;
	}

	/** @see WP_Widget::form */
	function form( $instance ) {
		if ( $instance ) {
			$title = esc_attr( $instance[ 'title' ] );
		}
		else {
			$title = __( 'Fast Facts', 'text_domain' );
		}
		?>
		<p>
		<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		<?php
	}

} // class Fast_Facts

add_action( 'widgets_init', create_function( '', 'register_widget("Fast_Facts");' ) );


function update_state() {

	global $post;

	if ($_POST['post_type'] == 'schools') {

		$state = get_post_meta( $post->ID, 'state', true );

		wp_set_post_terms( $post->ID, $state, 'state');

	}

}
add_action('save_post', 'update_state');








// Remove the post info function
remove_action( 'genesis_before_post_content', 'genesis_post_info' );
add_action( 'genesis_before_post_content', 'child_info' );
/**
 * Custom Post Meta with shortcodes.
 *
 * @author Greg Rickaby
 * @since 1.0.0
 */
function child_info() {

	global $post;

	if ( is_page( $post->ID ) )
		return;

	$post_type = get_post_type( $post->ID );

	if ($post_type != 'schools') {

		//$post_info = '[post_date] ' . __( 'By', 'genesis' ) . ' [post_author_posts_link] [post_comments] [post_edit]';
		//printf( '<div class="post-info">%s</div>', apply_filters( 'genesis_post_info', $post_info ) );

		$city = strip_tags(get_the_term_list( $post->ID, 'city', '', ', ', '' ));
		$state = strip_tags(get_the_term_list( $post->ID, 'state', '', ', ', '' ));

		$post_info = $city.', '.$state;
		printf( '<div class="post-info">%s</div>', apply_filters( 'child_info', $post_info ) );

	} else {

		$city = strip_tags(get_the_term_list( $post->ID, 'city', '', ', ', '' ));
		$state = strip_tags(get_the_term_list( $post->ID, 'state', '', ', ', '' ));

		$post_info = $city.', '.$state;
		printf( '<div class="post-info">%s</div>', apply_filters( 'child_info', $post_info ) );

	}

}

// Remove the post meta function
remove_action( 'genesis_after_post_content', 'genesis_post_meta' );


//Remove comments
//remove_action( 'genesis_after_post', 'genesis_get_comments_template' );





function post_info(){
	$city = strip_tags(get_the_term_list( $post->ID, 'city', '', ', ', '' ));
	$state = strip_tags(get_the_term_list( $post->ID, 'state', '', ', ', '' ));

	$post_info = $city . ', ' . $state;
	printf( '<div class="post-info">%s</div>', apply_filters( 'child_info', $post_info ) );

	//return $post_info;
}
add_shortcode('post_info', 'post_info');






function after_title_text() {
	if(is_tax( 'size' ) ) {

		$city = strip_tags(get_the_term_list( get_the_ID(), 'city', '', ', ', '' ));
		$state = strip_tags(get_the_term_list( get_the_ID(), 'state', '', ', ', '' ));

		$post_info = $city . ', ' . $state;



	    echo '<h3 class="single-title">' . $post_info . '</h3>';
	}
}
add_action('genesis_entry_header', 'after_title_text', 11 );



add_action( 'genesis_footer', 'sp_custom_footer' );
function sp_custom_footer() {

	if ( is_user_logged_in() ) return false;

	$pages = [
		4652, // My scholarships
		4636, // Quiz intro
		4603, // Quiz results
		4578, // Quiz test
		4656, // Home Free Member
		4585, // Other Quiz intro
	];
	$page_id = get_the_ID();
	// echo 'Current ID:' . $page_id;
	// echo '<pre>';
	// print_R( $pages );
	// echo '</pre>';

	if ( in_array( $page_id, $pages ) ) return false;

	echo do_shortcode( '[dm-quiz-modal]' );
}
