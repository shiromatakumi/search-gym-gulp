<?php get_header(); ?>
  <div class="wrapper">
    <div class="wrapper-inner wrapper-inner--article">
      <main id="main" class="main">
        <div class="main-inner">
          <article id="entry" <?php post_class('entry-content'); ?>>
          <?php if (have_posts()) : while (have_posts()) :  the_post(); ?>
            <?php get_template_part('template/entry-header'); ?>
            <?php the_content(); ?>
          <?php endwhile;endif; ?>
          </article>
        </div>
      </main>
      <?php get_sidebar(); ?>
    </div>
  </div>
<?php get_footer(); ?>