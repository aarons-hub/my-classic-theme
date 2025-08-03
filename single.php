<h1>single</h1>
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