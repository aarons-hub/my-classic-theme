<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_template_directory_uri(); ?>/images/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo get_template_directory_uri(); ?>/images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo get_template_directory_uri(); ?>/images/favicon-16x16.png">
    <link rel="manifest" href="<?php echo get_template_directory_uri(); ?>/images/site.webmanifest">
    <?php wp_head() ?>
</head>
<body>
    <header>
        <?php echo do_shortcode('[header_links]'); ?>
        <a href="<?php echo esc_url(home_url('/')); ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/header-logo.png" alt="header logo" class="header-logo"></a>
        <?php get_search_form(); ?>
        <?php
        // Display the Navigation block if it exists (WordPress 5.9+)
        if ( function_exists( 'do_blocks' ) ) {
            $navigation = get_option( 'wp_navigation' );
            if ( $navigation ) {
                echo do_blocks( $navigation );
            }
        }
        ?>
        <?php
        echo '<ul class="main-menu">';
        echo wp_list_pages( array(
            'sort_column' => 'menu_order',
            'sort_order'  => 'ASC',
            'title_li'    => '',
            'echo'        => 0
        ) );
        echo '</ul>';
        ?>
    </header>
