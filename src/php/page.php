<?php $entry_post_type = get_post_type(); ?>
<?php get_header(); ?>
  <div class="wrapper">
    <div class="wrapper-inner wrapper-inner--page">
      <main id="main" class="main">
        <div class="main-inner">
          <article id="entry" <?php post_class(); ?>>
          <?php if (have_posts()) : while (have_posts()) :  the_post(); ?>
            <?php get_template_part('template/entry-header'); ?>
            <div class="entry-content">
              <?php the_content(); ?>
            </div>
            <?php get_template_part('template/entry-footer'); ?>
          <?php endwhile;endif; ?>
          </article>
        </div>
      </main>
      <?php get_sidebar(); ?>
    </div>
  </div>
<?php get_footer(); ?>