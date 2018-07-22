<header class="article-header entry-header">
  <?php $post_type = get_post_type( get_the_ID() ); ?>
  <?php if( $post_type === 'post' ): ?>
    <?php get_template_part('template/breadcrumbs'); ?>
  <?php endif; ?>
  <h1 class="entry-title single-title"><?php the_title(); //タイトル?></h1>
  <?php if( $post_type === 'post' ): ?>
    <div class="post-date">
    <?php if(get_the_modified_date('Ymd') > get_the_date('Ymd')): ?>
      投稿日：<time class="pubdate entry-time"><?php echo get_the_date('Y-m-d'); ?></time>
      更新日：<time class="updated entry-time" datetime="<?php echo get_the_modified_date('Y-m-d'); ?>"><?php echo get_the_modified_date('Y-m-d'); ?></time>
    <?php else: ?>
      投稿日：<time class="pubdate entry-time" datetime="<?php echo get_the_date('Y-m-d'); ?>"><?php echo get_the_date('Y-m-d'); ?></time>
    <?php endif; ?>
    </div>
  <?php endif; ?>
  <?php 
    if( has_post_thumbnail() ) :
      $post_thumb_url_array = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'large');
      $post_thumb = $post_thumb_url_array[0];
    else:
      $post_thumb = get_template_directory_uri() . '/image/' . 'no-image.jpg';
    endif;
  ?>
  <?php if( $post_thumb ): ?>
    <div class="entry-eyecache"><img src="<?php echo $post_thumb; ?>" alt="<?php the_title(); ?>"></div>
  <?php endif; ?>
</header>