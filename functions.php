<?php

function my_classic_theme_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
}
add_action('after_setup_theme', 'my_classic_theme_setup');


function custom_enqueue_style() {
    wp_enqueue_style('custom-style', get_stylesheet_uri() ); 
}
add_action( 'wp_enqueue_scripts', 'custom_enqueue_style' );


function custom_enquene_scripts() {
    wp_enqueue_script( 'jquery' , get_stylesheet_directory_uri() . '/js/jquery-3.7.0.min.js', [], '1.0', true);
    wp_enqueue_script( 'owl-carousel' , get_stylesheet_directory_uri() . '/js/owl-carousel.js', [], '1.0', true);
    wp_enqueue_script( 'jquery-basic-table' , get_stylesheet_directory_uri() . '/js/jquery.basictable.min.js', [], '1.0', true);
    wp_enqueue_script( 'customjs' , get_stylesheet_directory_uri() . '/js/custom.js', [], '1.0', true);
}
add_action( 'wp_enqueue_scripts', 'custom_enquene_scripts' );




// Register a shortcode for site breadcrumb
// This will create a breadcrumb navigation based on the current page hierarchy
function site_breadcrumb_shortcode() {
    $breadcrumb = '<ul class="breadcrumb">';
    
    // Add the home link
    $breadcrumb .= '<li class="home"><a href="' . home_url() . '">Home</a></li>';
    
    // Get the current post or page
    $post = get_queried_object();
    
    if ($post) {
        // Get the post ancestors
        $ancestors = get_post_ancestors($post);
        
        // Reverse the order to start from the root ancestor
        $ancestors = array_reverse($ancestors);
        
        // Add each ancestor to the breadcrumb
        foreach ($ancestors as $ancestor) {
            $ancestor_title = get_the_title($ancestor);
            $ancestor_link = get_permalink($ancestor);
            $breadcrumb .= '<li><a href="' . $ancestor_link . '">' . $ancestor_title . '</a></li>';
        }
        
        // Add the current post or page to the breadcrumb
        $current_title = get_the_title($post);
        $breadcrumb .= '<li>' . $current_title . '</li>';
    }
    
    $breadcrumb .= '</ul>';
    
    return $breadcrumb;
}
add_shortcode('site_breadcrumb', 'site_breadcrumb_shortcode');



// Front page carousel posttype
function front_page_carousel_post_type() {
    register_post_type( 'front-page-carousel',
        array(
            'labels' => array(
                'name' => __( 'Front page carousel' ),
                'singular_name' => __( 'Front page carousel' )
            ),
            'has_archive' => true,
            'public' => true,
            'rewrite' => array('slug' => 'front-page-carousel'),
            'menu_icon' => 'dashicons-embed-photo',
            'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'page-attributes' ),
        )
    );
    }
add_action( 'init', 'front_page_carousel_post_type' );

function front_page_carousel_shortcode($atts) {
    $atts = shortcode_atts(array(
        'count' => 10, // Number of items to display (default: 10)
        'link_text' => 'Read more', // Default link text if not specified in the shortcode
    ), $atts);

    $args = array(
        'post_type' => 'front-page-carousel',
        'posts_per_page' => $atts['count'],
        'orderby' => 'menu_order',
        'order' => 'ASC',
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        $output = '<div id="front-page-carousel" class="front-page-carousel owl-theme owl-carousel">';
        while ($query->have_posts()) {
            $query->the_post();
            $carousel_title = get_the_title();
            $carousel_link = ''; // Remove ACF dependency
            $link_text = $atts['link_text'];

            if ($carousel_title) {
                $output .= '<div class="item">';
                if (has_post_thumbnail()) {
                    $output .= '<img src="' . esc_url(get_the_post_thumbnail_url(null, 'full')) . '" alt="' . esc_attr($carousel_title) . '">';
                }
                $output .= '<div class="carousel-content">';
                $output .= '<h1>' . $carousel_title . '</h1>';
                // If you want to link to the post itself, uncomment below:
                // $output .= '<a href="' . esc_url(get_permalink()) . '" target="_self">' . esc_html($link_text) . '</a>';
                $output .= '</div>'; // Close the carousel-content div
                $output .= '</div>'; // Close the item div
            }
        }
        $output .= '</div>';
        wp_reset_postdata();
        return $output;
    }

    return ''; // Return empty string if no carousel items found
}
add_shortcode('front_page_carousel', 'front_page_carousel_shortcode');




// Front page carousel - company logos posttype
function front_page_logos_post_type() {
    register_post_type( 'front-page-logos',
        // WordPress CPT Options Start
        array(
            'labels' => array(
                'name' => __( 'Front page logos' ),
                'singular_name' => __( 'Front page logos' )
            ),
            'has_archive' => true,
            'public' => true,
            'rewrite' => array('slug' => 'front-page-logos'),
            'menu_icon' => 'dashicons-embed-photo',
            // 'show_in_rest' => true, Only use this if you want the block editor
            'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'page-attributes' ),
        )
    );
}
add_action( 'init', 'front_page_logos_post_type' );


function front_page_logos_shortcode($atts) {
    $atts = shortcode_atts( array(
        'count' => 30, // Number of logos to display (default: 30)
    ), $atts );

    $args = array(
        'post_type' => 'front-page-logos',
        'posts_per_page' => $atts['count'],
        'orderby' => 'menu_order',
        'order' => 'ASC',
    );

    $query = new WP_Query( $args );

    if ( $query->have_posts() ) {
        $output = '<ul id="front-page-logos" class="owl-carousel owl-theme front-page-logos">';
        while ( $query->have_posts() ) {
            $query->the_post();
            $company_logo = get_field( 'company_logo' );
            $company_url = get_field( 'company_url' );
            if ( $company_logo && $company_url ) {
                $output .= '<li class="item"><a href="' . esc_url( $company_url ) . '" target="_blank"><img src="' . esc_url( $company_logo['url'] ) . '" alt="' . esc_attr( get_the_title() ) . '"></a></li>';
            } elseif ( $company_logo ) {
                $output .= '<li class="item"><img src="' . esc_url( $company_logo['url'] ) . '" alt="' . esc_attr( get_the_title() ) . '"></li>';
            }
        }
        $output .= '</ul>';
        wp_reset_postdata();
        return $output;
    }

    return ''; // Return empty string if no logos found
}
add_shortcode( 'front_page_logos', 'front_page_logos_shortcode' );








// Register Header Links post type
// This post type will be used to manage links in the header
function register_header_links_post_type() {
    register_post_type('header-links',
        array(
            'labels' => array(
                'name' => __('Header Links'),
                'singular_name' => __('Header Link')
            ),
            'public' => true,
            'has_archive' => false,
            'menu_icon' => 'dashicons-admin-links',
            'supports' => array('title', 'page-attributes'),
            'show_in_rest' => true,
        )
    );
}
add_action('init', 'register_header_links_post_type');

// Add meta box for URL
function header_link_url_metabox() {
    add_meta_box(
        'header_link_url',
        'Header Link URL',
        'header_link_url_metabox_callback',
        'header-links',
        'normal',
        'default'
    );
}
add_action('add_meta_boxes', 'header_link_url_metabox');

function header_link_url_metabox_callback($post) {
    $value = get_post_meta($post->ID, '_header_link_url', true);
    echo '<label for="header_link_url_field">URL: </label>';
    echo '<input type="url" id="header_link_url_field" name="header_link_url_field" value="' . esc_attr($value) . '" style="width:100%">';
}

// Save the URL meta field
function save_header_link_url($post_id) {
    if (array_key_exists('header_link_url_field', $_POST)) {
        update_post_meta(
            $post_id,
            '_header_link_url',
            esc_url_raw($_POST['header_link_url_field'])
        );
    }
}
add_action('save_post', 'save_header_link_url');

// Shortcode function to display header links
function get_header_links_shortcode() {
    $args = array(
        'post_type' => 'header-links',
        'posts_per_page' => -1,
        'orderby' => 'menu_order',
        'order' => 'ASC',
        'post_status' => 'publish'
    );
    $query = new WP_Query($args);
    if ($query->have_posts()) {
        echo '<ul class="header-links">';
        while ($query->have_posts()) {
            $query->the_post();
            $url = get_post_meta(get_the_ID(), '_header_link_url', true);
            echo '<li><a href="' . esc_url($url) . '">' . esc_html(get_the_title()) . '</a></li>';
        }
        echo '</ul>';
        wp_reset_postdata();
    }
}
add_shortcode( 'header_links', 'get_header_links_shortcode' );







// Quick Launch Navigation Shortcode
// This shortcode will display child pages of the current page in a list format

function combined_navigation_shortcode() {
    global $post;

    if ( is_page() ) {
        // Get child pages of the current page
        $childpages = wp_list_pages( array(
            'sort_column' => 'menu_order',
            'title_li'    => '',
            'child_of'    => $post->ID,
            'echo'        => 0
        ) );

        // Start the list
        $string = '<ul class="quicklaunch">';

        // Add the current page as the first item
        $string .= '<li class="current-page"><a href="' . get_permalink($post->ID) . '">' . esc_html(get_the_title($post->ID)) . '</a></li>';

        // Add the child pages if any
        if ( $childpages ) {
            $string .= $childpages;
        }

        $string .= '</ul>';
        return $string;
    }

    return '';
}
add_shortcode('combined_navigation', 'combined_navigation_shortcode');




// Shortcode to display the modified date of the current post or page

function modified_date_shortcode( $atts ) {
    global $post;
    if ( ! is_singular() || ! isset( $post->ID ) ) {
        return '';
    }
    $atts = shortcode_atts( array(
        'format' => get_option( 'date_format' ),
        'prefix' => 'Last updated: ',
    ), $atts );

    $modified = get_the_modified_date( $atts['format'], $post->ID );
    if ( $modified ) {
        return esc_html( $atts['prefix'] . $modified );
    }
    return '';
}
add_shortcode( 'modified_date', 'modified_date_shortcode' );

