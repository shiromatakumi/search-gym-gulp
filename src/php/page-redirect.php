<?php
/*
Template Name: リダイレクト用のテンプレート
*/
?>
<?php $entry_post_type = get_post_type(); ?>
<?php get_header(); ?>
<style>
.header {
  display: none;
}
.nav-footer-wrap {
  display: none;
}
.footer {
  display: none;
}
</style>
  <div class="wrapper">
    <div class="wrapper-inner wrapper-inner--page">
      <main id="main" class="main">
        <div class="main-inner">
          
          <article id="entry" <?php post_class(); ?>>
          <?php if (have_posts()) : while (have_posts()) :  the_post(); ?>
            <h1 class="entry-title single-title">
              <?php the_title();?>
            </h1>
            <div class="entry-content">
              <?php the_content(); ?>
            </div>
          <?php endwhile;endif; ?>
          </article>
        </div>
      </main>
    </div>
  </div>
<?php get_footer(); ?>