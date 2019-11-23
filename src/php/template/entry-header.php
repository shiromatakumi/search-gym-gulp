<header class="article-header entry-header">
  <?php $post_type = get_post_type( get_the_ID() ); ?>
  <?php if( $post_type === 'post' ): ?>
    <?php get_template_part('template/breadcrumbs'); ?>
  <?php endif; ?>
  <h1 class="entry-title single-title">
    <?php the_title(); //タイトル?><?php if( $post_type === 'studio' ) echo 'のコース・料金・アクセス情報'; ?>  
  </h1>
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

    $post_thumb = '';
    $post_id = $post->ID;
    if ( $post_type === 'studio' ) {
      // ベースのジム情報のidを取得
      $meta_values = get_post_meta($post_id, 'base_gym', true);
      $post_base_obj = get_page_by_path( $meta_values, OBJECT, 'gym' );
      $post_base_id = $post_base_obj->ID;

      $post_thumb_url_array = wp_get_attachment_image_src( get_post_thumbnail_id( $post_base_id ), 'large' );
      $post_thumb = $post_thumb_url_array[0];
    } elseif ( has_post_thumbnail() ) {
      $post_thumb = get_the_post_thumbnail_url( $post_id, 'full' );
    }

    if( empty( $post_thumb ) ) $post_thumb = get_template_directory_uri() . '/image/' . 'no-image.jpg';

  ?>
  <?php if( $post_thumb ): ?>
    <div class="entry-eyecache"><img src="<?php echo $post_thumb; ?>" alt="「<?php the_title(); ?>」のアイキャッチ画像"></div>
  <?php endif; ?>

    <?php

    if( $post_type === 'studio' ) {
      $meta_values = get_post_meta($post_id, 'base_gym', true);
      $post_base_obj = get_page_by_path( $meta_values, OBJECT, 'gym' );
      $post_base_id = $post_base_obj->ID;
      $display_ad = get_post_meta( $post_base_id, 'display_ad', true );
    } else {
      $display_ad = get_post_meta( $post_id, 'display_ad', true );
    }
    
     ?>
    <?php if ( is_active_sidebar( 'entry-top-ad' ) /*&& !empty( $display_ad )*/ ) : ?>
      <?php dynamic_sidebar( 'entry-top-ad' ); ?>
    <?php endif; ?>
</header>