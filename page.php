<h1>page</h1>

    <?php get_header() ?>
<main>

<div class="content">
    <?php echo do_shortcode('[site_breadcrumb]'); ?>
</div>


<div class="content">

    <div class="quick-launch-container">
        <?php echo do_shortcode('[combined_navigation]'); ?>
    </div>

    <div class="entry-container">
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

    </div>
</div>

    <?php echo do_shortcode('[modified_date]'); ?>



</main>
<?php get_footer() ?>