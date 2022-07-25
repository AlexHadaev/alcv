<?php
/**
 * Theme functions and definitions
 *
 * @package Aasta Light
 */

/**
 * After setup theme hook
 */
function aasta_light_theme_setup(){
    /*
     * Make child theme available for translation.
     * Translations can be filed in the /languages/ directory.
     */
    load_child_theme_textdomain( 'aasta-light', get_stylesheet_directory() . '/languages' );	
	require get_stylesheet_directory() . '/inc/customizer/aasta-light-customizer-options.php';	
}
add_action( 'after_setup_theme', 'aasta_light_theme_setup' );

/**
 * Load assets.
 */

function aasta_light_theme_css() {
	wp_enqueue_style( 'aasta-light-parent-style', get_template_directory_uri() . '/style.css' );
	wp_enqueue_style('aasta-light-child-style', get_stylesheet_directory_uri() . '/style.css');
	wp_enqueue_style('aasta-light-default-css', get_stylesheet_directory_uri() . "/assets/css/theme-default.css" );
}
add_action( 'wp_enqueue_scripts', 'aasta_light_theme_css', 99);



/**
 * Import Options From Parent Theme
 *
 */
function aasta_light_parent_theme_options() {
	$aasta_mods = get_option( 'theme_mods_aasta' );
	if ( ! empty( $aasta_mods ) ) {
		foreach ( $aasta_mods as $aasta_mod_k => $aasta_mod_v ) {
			set_theme_mod( $aasta_mod_k, $aasta_mod_v );
		}
	}
}
add_action( 'after_switch_theme', 'aasta_light_parent_theme_options' );

/**
 * Fresh site activate
 *
 */
$fresh_site_activate = get_option( 'fresh_aasta_light_site_activate' );
if ( (bool) $fresh_site_activate === false ) {
	set_theme_mod( 'aasta_page_header_background_color', 'rgba(0,0,0,0.5)' );
	set_theme_mod( 'aasta_testomonial_background_image', get_stylesheet_directory_uri().'/assets/img/theme-bg.jpg' );
	set_theme_mod( 'aasta_theme_color_skin', 'theme-light' );
	set_theme_mod( 'aasta_theme_color', 'theme-orange' );

	update_option( 'fresh_aasta_light_site_activate', true );
}

/**
 * Custom Theme Script
*/
function aasta_light_custom_theme_css() {
	$aasta_light_testomonial_background_image = get_theme_mod('aasta_testomonial_background_image');
	?>
    <style type="text/css">
		<?php if($aasta_light_testomonial_background_image != null) : ?>
		.theme-testimonial { 
		        background-image: url(<?php echo esc_url( $aasta_light_testomonial_background_image ); ?>); 
                background-size: cover;
				background-position: center center;
		}
        <?php endif; ?>
    </style>
 
<?php }
add_action('wp_footer','aasta_light_custom_theme_css');

/**
 * Page header
 *
 */
function aasta_light_custom_header_setup() {
	add_theme_support( 'custom-header', apply_filters( 'aasta_light_custom_header_args', array(
		'default-image'      => get_stylesheet_directory_uri().'/assets/img/aasta-light-page-header.jpg',
		'default-text-color' => 'fff',
		'width'              => 1920,
		'height'             => 500,
		'flex-height'        => true,
		'flex-width'         => true,
		'wp-head-callback'   => 'aasta_light_header_style',
	) ) );
}

add_action( 'after_setup_theme', 'aasta_light_custom_header_setup' );

/**
 * Custom background
 *
 */
function aasta_light_custom_background_setup() {
	add_theme_support( 'custom-background', apply_filters( 'aasta_light_custom_background_args', array(
		'default-color' => '202020',
		'default-image' => '',
	) ) );
}
add_action( 'after_setup_theme', 'aasta_light_custom_background_setup' );


if ( ! function_exists( 'aasta_light_header_style' ) ) :
	/**
	 * Styles the header image and text displayed on the blog.
	 *
	 * @see aasta_light_custom_header_setup().
	 */
	function aasta_light_header_style() {
		$header_text_color = get_header_textcolor();

		/*
		 * If no custom options for text are set, let's bail.
		 * get_header_textcolor() options: Any hex value, 'blank' to hide text. Default: add_theme_support( 'custom-header' ).
		 */
		if ( get_theme_support( 'custom-header', 'default-text-color' ) === $header_text_color ) {
			return;
		}

		// If we get this far, we have custom styles. Let's do this.
		?>
		<style type="text/css">
			<?php
			// Has the text been hidden?
			if ( ! display_header_text() ) :
				?>
			.site-title,
			.site-description {
				position: absolute;
				clip: rect(1px, 1px, 1px, 1px);
			}

			<?php
			// If the user has set a custom color for the text use that.
			else :
				?>
			.site-title a,
			.site-description {
				color: #<?php echo esc_attr( $header_text_color ); ?> !important;
			}

			<?php endif; ?>
		</style>
		<?php
	}
endif;