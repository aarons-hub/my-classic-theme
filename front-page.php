<?php get_header(); ?>

<h1>my front page</h1>

<?php echo do_shortcode('[front_page_carousel]'); ?>

<?php
if ( have_posts() ) {
    while ( have_posts() ) {
        the_post();
        the_content();
    }
}
?>

<?php get_footer(); ?>