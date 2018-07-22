<?php 
  /**
   * 店舗情報ページに表示させる記事下
   */
 ?>
    <h3 class="near-studio__heading">同じエリアのジム一覧</h3>
    <div class="near-studio">
      <?php // 近くのジムを検索
      $post_id = get_the_ID();
      $region = get_post_meta( $post_id, 'region', true );
      $count = 0;
      $args = array(
        'post_type'       => 'studio',
        'posts_per_page'  => 8,
        'orderby'        => 'rand',
        'meta_key'        => 'region',
        'meta_value'      => $region,
      );

      $my_query = new WP_Query($args);

      if ( $my_query->have_posts() ) {
        while ( $my_query->have_posts() ) {
          $my_query->the_post();
          $post_id = $my_query->posts[$count]->ID;
          if( !$post_thumbnail_url = get_the_post_thumbnail_url( $post_id, 'full' ) ) {
            $post_thumbnail_url = get_template_directory_uri() . '/image/no-image.jpg';
          }
          $content = '<div class="near-studio__item"><a href="' . get_the_permalink() . '">';
          $content .= '<div class="near-studio__thumb"><img src="' . $post_thumbnail_url . '" alt=""></div>';
          $content .= '<p class="near-sudio__title">' . $my_query->posts[$count]->post_title .'</p>';
          $content .= '</a></div>';
          echo $content;
          $count++;
        }
      }
      ?>
    </div>
    <?php $avarage_star = get_average_star(); ?>
    <?php if( $avarage_star ): ?>
      <h3 class="gym-rating">評価</h3>
    <div class="star-rating">
      <div class="star-rating-front" style="width: <?php echo ($avarage_star/5)*100; ?>%">★★★★★</div>
      <div class="star-rating-back">★★★★★</div>
    </div>
    <p>点数: <?php echo $avarage_star; ?>点</p>
    <?php endif; ?>
    <?php // get_average_star(); ?>
    <div class="studio-kuchikomi">
      <?php comments_template(); ?>
    </div>