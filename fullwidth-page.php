<h1>fullwidth page</h1>

<?php
/**
 * Template Name: Full Width, No Sidebar
 * The template for displaying a full-width page.
 
*/

get_header() ?>
<?php echo do_shortcode('[site_breadcrumb]'); ?>
<?php
if (have_posts()) {
    while (have_posts()) {
        the_post(); ?>
        <div class="page">
            <h2><?php the_title(); ?></h2>
            <p><?php the_content(); ?></p>
        </div>
    <?php }
}
?>
<?php get_footer() ?>