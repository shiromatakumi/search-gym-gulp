<?php 

$entries_adsense = get_option( 'adsense-entries' );
$count = 1;
 ?>
<?php if( have_posts() ): ?>
  <?php if( is_archive() ): ?>
    <h3 class="top-section__heading"><?php the_archive_title(); ?></h3>
  <?php endif; ?>
  <div class="entries">
    <?php while (have_posts()) : the_post(); ?>
      <?php 

        $post_id = $post->ID;

        // ベースのジム情報のidを取得
        $meta_values = get_post_meta($post_id, 'base_gym', true);
        $post_base_obj = get_page_by_path( $meta_values, OBJECT, 'gym' );
        $post_base_id = $post_base_obj->ID;

        $post_thumb_url_array = wp_get_attachment_image_src(get_post_thumbnail_id( $post_base_id ), 'large');
        $post_thumb = $post_thumb_url_array[0];

        if( empty( $post_thumb ) ) {
          if( has_post_thumbnail() ) {
            $post_thumb_url_array = wp_get_attachment_image_src(get_post_thumbnail_id( $post_id ), 'large');
            $post_thumb = $post_thumb_url_array[0];
          } else {
            $post_thumb = get_template_directory_uri() . '/image/' . 'no-image.jpg';
          }
        }
      ?>
      <article class="entries-article">
        <a class="entries-article__link" href="<?php the_permalink() ?>">
          <div class="entries-article__thumb" style="background-image: url(<?php echo $post_thumb; ?>)"></div>
          <div class="entries-article__info">
            <h2 class="entries-article__title"><?php the_title(); ?></h2>
          </div>
        </a>
      </article>
      <?php if ( !empty( $entries_adsense ) ): ?>
        <?php if ( $count === 4 || $count === 7 ): ?>
      <div class="entries-article">
        <?php echo $entries_adsense; ?>
      </div>
        <?php endif; ?>
      <?php endif; ?>
      <?php $count++; ?>
    <?php endwhile; ?>
  </div>
  <div class="pagination pagination-top">
    <?php echo paginate_links(array(
      'type' => 'list',
      'prev_text' => '&laquo;',
      'next_text' => '&raquo;',
    )); ?>
  </div>
<?php else: ?>
  <?php get_template_part( 'template/no-entries' ); ?>
<?php endif; ?>