<?php get_header(); ?>
  <div class="wrapper">
    <div class="wrapper-inner">
      <main id="main" class="main">
        <div class="main-inner">
          <?php get_template_part( 'template/entries' ); ?>
        </div>
      </main>
      <?php get_sidebar(); ?>
    </div>
  </div>
<?php get_footer(); ?>