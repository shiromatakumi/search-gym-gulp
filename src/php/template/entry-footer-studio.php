<?php 
  /**
   * 店舗情報ページに表示させる記事下
   */
 ?>
    <?php 
      $post_id = get_the_ID();
      $region = get_post_meta( $post_id, 'region', false );
      $get_num = 0;

      if( $region ) {
        echo '<h3>このジムが掲載されている記事</h3>';
        echo '<div class="area-article">';
        foreach($region as $item) {
          $count = 0;
          $args = array(
            'post_type'       => 'post',
            'posts_per_page'  => -1,
            'orderby'        => 'date',
            'meta_key'        => 'area',
            'meta_value'      => $item,
          );
          $my_query = new WP_Query($args);

          if ( $my_query->have_posts() ) {

            $get_num += $my_query->post_count; //件数を足していく

            while ( $my_query->have_posts() ) {
              $my_query->the_post();
              $post_id = $my_query->posts[$count]->ID;
              $content = '<div class="area-article__item">';
              $content .= '<p class="area-article__title"><a href="' . get_the_permalink() . '">' . $my_query->posts[$count]->post_title .'</a></p>';
              $content .= '</div>';
              echo $content;
              $count++;
            }
          }
        }
        if( $get_num === 0 ) echo '<p>掲載記事はまだありません。</p>';
        echo '</div>';
      }

      // 上書きされた$postを元に戻す
      wp_reset_postdata();
    ?>
    <h3 class="near-studio__heading">同じエリアのジム一覧</h3>
    <div class="near-studio">
      <?php // 近くのジムを検索
      
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
      // 上書きされた$postを元に戻す
      wp_reset_postdata();
      ?>
    </div>
    <?php $avarage_star = get_average_star(); ?>
    <?php if( $avarage_star ): ?>
    <div class="gym-rating-area">
      <h3 class="gym-rating">評価</h3>
      <div class="star-rating">
        <div class="star-rating-front" style="width: <?php echo ($avarage_star/5)*100; ?>%">★★★★★</div>
        <div class="star-rating-back">★★★★★</div>
      </div>
      <p>平均: <?php echo $avarage_star; ?>点</p>
    </div>
    <?php endif; ?>
    <div class="reviewer-comments">
      <h3 class="reviewer-comments-title">口コミ一覧</h3>
      <?php get_review_comment(); ?>
    </div>
    <?php // get_average_star(); ?>
    <div class="studio-kuchikomi">
      <?php comments_template(); ?>
    </div>