<?php 

/* Setup theme defaults and register support for WordPress services.
 * since: wc_core 1.0
 * modified: centreforge 3.0.0
 */
if(!function_exists( 'cf3_setup' ))
{
    function cf3_setup()
    {
        add_theme_support('automatic-feed-links');
        add_theme_support('title-tag');
        add_theme_support('post-thumbnails');
        add_theme_support('custom-background');
        add_theme_support('html5', array(
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
        ));
        add_theme_support('post-formats', array(
            'aside',
            'image',
            'video',
            'quote',
            'link',
            'gallery',
            'audio',
        ));
        add_theme_support('custom-logo', array(
            'width'       => 250,
            'height'      => 250,
            'flex-width'  => true,
        ));
        add_theme_support('customize-selective-refresh-widgets');
    }
    add_action('after_setup_theme', 'cf3_setup');
}

/* Remove Welcome Panel, and add Centreforge Welcome Panel
 * since: centreforge 2.0.2
 * modified: centreforge 3.0.0
 */
if(!function_exists( 'cf3_remove_welcome_panel' ))
{
    function cf3_welcome_panel(){
        file_exists(STYLESHEETPATH.'/welcome-panel.xml')?
            $welcome = simplexml_load_file(STYLESHEETPATH.'/welcome-panel.xml'):
            $welcome = simplexml_load_file(TEMPLATEPATH.'/welcome-panel.xml');
        
        echo '<div class="welcome-panel-content"><h3>'.$welcome->greeting.'</h3>
        <p class="about-description">'.$welcome->subgreeting.'</p>
        <div class="welcome-panel-column-container">
        <div class="welcome-panel-column">
        <h4><img src="'.$welcome->image.'" /></h4>
        <p>'.$welcome->address->line1.'<br/>
        '.$welcome->address->line2.'<br/>
        '.$welcome->address->line3.'<br/>
        <a href="'.$welcome->address->url.'" traget="_new">'.$welcome->address->url.'</a>		
        </p>
        </div><div class="welcome-panel-column">
        <h4>Get Started</h4>
        <ul><li><a href="http://dev.erparts.com/wp-admin/post-new.php?post_type=page" class="welcome-icon welcome-add-page">Add additional pages</a></li>
        <li><a href="'.network_site_url('/').'" class="welcome-icon welcome-view-site" target="_new">View your site</a></li>
        <li><a href="http://codex.wordpress.org/First_Steps_With_WordPress" class="welcome-icon welcome-learn-more" target="_new">Learn more about getting started</a></li></ul>
        </div><div class="welcome-panel-column welcome-panel-last">
        <h4>More Information</h4>
        <p>'.$welcome->info.'</p>
        </div></div></div>';
    }
    function cf3_remove_welcome_panel(){
        remove_action( 'welcome_panel', 'wp_welcome_panel' );
        add_action( 'welcome_panel', 'cf3_welcome_panel' );
    }
    add_action( 'load-index.php', 'cf3_remove_welcome_panel' );
}

/* Replaces "[...]" (appended to automatically generated excerpts) with ... and a 'Continue reading' link.
 * since: centreforge 3.0.0
 * modified: centreforge 3.0.0
 */

function cf3_excerpt_more( $link ) {
	if ( is_admin() ) {
		return $link;
	}

	$link = sprintf('<p class="link-more"><a href="%1$s" class="more-link">%2$s</a></p>',
		esc_url(get_permalink(get_the_ID())),
		/* translators: %s: Name of current post */
		sprintf('Continue reading<span class="screen-reader-text"> "%s"</span>', get_the_title( get_the_ID()))
	);
	return ' &hellip; ' . $link;
}
add_filter( 'excerpt_more', 'cf3_excerpt_more' );

/* Handles JavaScript detection. Adds a `js` class to the root `<html>` element when JavaScript is detected.
 * since: centreforge 3.0.0
 * modified: centreforge 3.0.0
 */

function cf3_javascript_detection() {
	echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n";
}
add_action( 'wp_head', 'cf3_javascript_detection', 0 );

/* Add a pingback url auto-discovery header for singularly identifiable articles.
 * since: centreforge 3.0.0
 * modified: centreforge 3.0.0
 */
function cf3_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">' . "\n", get_bloginfo( 'pingback_url' ) );
	}
}
add_action( 'wp_head', 'cf3_pingback_header' );

/**
 * Implement the Custom Header feature.
 * since: centreforge 3.0.0
 * modified: centreforge 3.0.0
 */
require get_parent_theme_file_path( '/inc/custom-header.php' );