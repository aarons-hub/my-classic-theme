<h1>index</h1>
<?php get_header() ?>
<?php
$args = array(
    'post_type' => array('post', 'page'),
    'posts_per_page' => 10 // Adjust as needed
);
$query = new WP_Query($args);

if ($query->have_posts()) {
    while ($query->have_posts()) {
        $query->the_post(); ?>
        <div class="post">
            <a href="<?php the_permalink(); ?>"><h2><?php the_title(); ?></h2></a>
            <p><?php the_content(); ?></p>
        </div>
    <?php }
    wp_reset_postdata();
}
?>
<?php get_footer() ?>