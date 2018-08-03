<?php 
/*
Template Name: サイトマップ用ののテンプレート
*/
?>
<?php get_header(); ?>
  <div class="wrapper">
    <div class="wrapper-inner wrapper-inner--article　wrapper-inner--lines">
      <main id="main" class="main">
        <div class="main-inner">
          <article id="entry" <?php post_class('entry-content'); ?>>
          <?php if (have_posts()) : while (have_posts()) :  the_post(); ?>
            <header class="article-header entry-header">
              <h1 class="entry-title single-title"><?php the_title(); //タイトル?></h1>
            </header>
            <?php get_template_part( 'template/sitemap' ); ?>
          <?php endwhile;endif; ?>
          </article>
        </div>
      </main>
      <?php get_sidebar(); ?>
    </div>
  </div>
<?php get_footer(); ?>