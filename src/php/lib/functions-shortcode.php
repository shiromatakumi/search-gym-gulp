<?php

/**
 * ショートコード
 */

/**
 * ジム情報を出力するショートコード
 */
function getBaseGymData($atts) {
  $gym_slug = $atts['slug'];

  global $entry_post_type;

  if( isset( $gym_slug ) ) {
    
    $base_post_data = get_page_by_path( $gym_slug, "OBJECT", "gym" );
    $base_post_id = $base_post_data->ID;
    $content = get_post_field( 'post_content', $base_post_id );
    $content = '<div class="gym-common-data">' . $content . '</div>';
    $content = do_shortcode($content);

    if( $entry_post_type === 'studio' ) {
      $post_id = get_the_ID();
      $afilink = get_post_meta( $post_id, 'aficode', true );

      if( empty( $afilink ) ) {
        $afilink = get_post_meta( $base_post_id, 'aficode', true );
      }
      
      $afilink = '<p class="gym-content__btn">' . $afilink . '</p>';

      if( get_option( 'diet-concierge' ) && !empty( get_post_meta( $base_post_id, 'cash-back', true ) ) ) {
        $cash_back_link = '<p class="cash-back-link">' . get_option( 'diet-concierge' ) . "</p>";
        $afilink = $cash_back_link . $afilink;
      }
      $content .= $afilink;
    }
    return apply_filters( 'the_content', $content );
  }
}
add_shortcode('base', 'getBaseGymData');

/**
 * テンプレート文章を引っ張てくるショートコード
 */
function get_text_from_template($atts) {
  $gym_slug = $atts['slug'];

  if( isset( $gym_slug ) ) {
    $post_id = get_page_by_path($gym_slug, "OBJECT", "template");
    if( !$post_id ) return;
    $post_id = $post_id->ID;
    $content = get_post_field( 'post_content', $post_id );
    $content = do_shortcode($content);
    return apply_filters( 'the_content', $content );
  }
}
add_shortcode('temp', 'get_text_from_template');

/**
 * 地名からジムを取得して表示させるショートコード
 */
function getGymByRegion($atts) {
  $content = '';
  $key = $atts["region"];
  $args = array(
    'post_type'        => 'studio',
    'posts_per_page'   => -1,
    'orderby'          => 'meta_value_num',
    'order'            => 'ASC',
    'meta_key' => 'price_per',
    'meta_query' => array(
      array(
        'key' => 'region',
        'value' => $key,
        'compare'=>'=',
      ),
      'relation' => 'AND'
    )
  );
  $inner_adsense = get_option( 'adsense-inner' );
  $diet_concierge = get_option( 'diet-concierge' );

  $my_query = new WP_Query($args);
  $count = 0;
  $hit_count = 0;
  $pickup_contents = "";

  $prev_meta_value = "";

  if ( $my_query->have_posts() ) {
    while ( $my_query->have_posts() ) {
      $my_query->the_post();
      $post_id = $my_query->posts[$count]->ID;
      
      $content_text = apply_filters('the_content',$my_query->posts[$count]->post_content);
      // タイトル
      $title = $my_query->posts[$count]->post_title;
      // リンク
      $details_link = get_the_permalink();
      // ベースのジム情報のidを取得
      $meta_values = get_post_meta($post_id, 'base_gym', true);
      $post_base_obj = get_page_by_path( $meta_values, OBJECT, 'gym' );
      $post_base_id = $post_base_obj->ID;
      // ベースとなるジムのサムネイル
      $post_thumbnail_url = get_the_post_thumbnail_url( $post_base_id, 'full' );
      // ベースとなるジムのアフィコードを取得
      $aficode = get_post_meta( $post_id, 'aficode', true );

      if( empty( $aficode ) ) {
        $aficode = get_post_meta( $post_base_id, 'aficode', true );
      }
      // 前回と同じジムかの判定
      $duplication = $meta_values === $prev_meta_value; // bool

      //おすすめジムかの判定
      $content .= !$duplication ? '<div class="gym-content">' : '<div class="gym-content gym-content--duplication">' ; // 前回と同じジムだったらクラスを付与
      $content .= '<h2 class="gym-content__title">' .  $title . '</h2>';
      $content .= '<div class="gym-content__thumb"><img src="' . $post_thumbnail_url . '" alt="' . $title . '"></div>';
      if( $duplication ) $content .= '<p class="gym-content__text-same">※ジムの内容は上の店舗と同じ</p>';
      $content .= $content_text;

      // ダイエットコンシェルジュへのリンクを入れる
      if( !empty( $diet_concierge ) && !empty( get_post_meta( $post_base_id, 'cash-back', true ) ) ) {
        $cash_back_link = '<p class="cash-back-link">' . get_option( 'diet-concierge' ) . "</p>";
        $content .= $cash_back_link;
      }

      if( $aficode ) $content .= '<p class="gym-content__btn">' . $aficode . '</p>';
      $content .= '<p class="gym-content__detail"><a href="' . $details_link . '">ジム情報を見る</a></p>';
      $content .= '</div>';

      if( !empty( $inner_adsense ) && $count % 3 === 0 ) {
        $content .= '<div class="inner-adsense">' . $inner_adsense . '</div>';
      }
      $count++;
      $hit_count++;

      $prev_meta_value = $meta_values;
    }

    $hit_count_text = '<p class="hit-count">' . $hit_count .'件のジムがヒットしました。</p>';
    $content = $hit_count_text . $content;
  }

  // 上書きされた$postを元に戻す
  wp_reset_postdata();
  return do_shortcode( $content );
}
add_shortcode('region', 'getGymByRegion');

/**
 * 都道府県のジムを取得
 */
function getGymBytodofuken($atts) {

  $content = '';
  $key = $atts["name"];

  $args = array(
    'post_type'        => 'todofuken',
    'posts_per_page'   => -1,
    'orderby'          => 'meta_value_num',
    'order'            => 'ASC',
    'meta_key' => 'price_per',
    'meta_query' => array(
      array(
        'key' => 'todofuken',
        'value' => $key,
        'compare'=>'=',
      ),
      'relation' => 'AND'
    )
  );
  $my_query = new WP_Query($args);
  $count = 0;
  $hit_count = 0;
  
  if ( $my_query->have_posts() ) {
    while ( $my_query->have_posts() ) {
      $my_query->the_post();
      $post_id = $my_query->posts[$count]->ID;

      $content_text = apply_filters('the_content',$my_query->posts[$count]->post_content);
      $title = $my_query->posts[$count]->post_title;
      
      // ベースのジム情報のidを取得
      $meta_values = get_post_meta($post_id, 'base_gym', true);
      $post_base_obj = get_page_by_path( $meta_values, OBJECT, 'gym' );
      $post_base_id = $post_base_obj->ID;

      // ベースとなるジムのアフィコードを取得
      $aficode = get_post_meta($post_base_id, 'aficode', true);
      // ベースとなるジムのサムネイル
      $post_thumbnail_url = get_the_post_thumbnail_url( $post_base_id, 'full' );

      $content .= '<div class="gym-content">';
      $content .= '<h2 class="gym-content__title">' .  $title . '</h2>';
      $content .= '<div class="gym-content__thumb"><img src="' . $post_thumbnail_url . '" alt="' . $title . '"></div>';
      $content .= $content_text;
      if( $aficode ) $content .= '<p class="gym-content__btn">' . $aficode . '</p>';
      $content .= '</div>';
      $count++;
      $hit_count++;
    }
    $hit_count_text = '<p class="hit-count">' . $hit_count .'件のジムがヒットしました。</p>';
    $content = $hit_count_text . $content;
  }
  // 上書きされた$postを元に戻す
  wp_reset_postdata();
  return do_shortcode( $content );

}
add_shortcode('todofuken', 'getGymBytodofuken');

/**
 * 都道府県のジムを取得
 */
function getGymByPrefecture($atts) {

  $content = '';
  $key = $atts["place"];

  $tag = get_term_by('name', '重複', 'gym_tag');
  $tag_id = $tag->term_id;


  $args = array(
    'post_type'        => 'gym',
    'posts_per_page'   => -1,
    'orderby'          => 'meta_value_num',
    'order'            => 'ASC',
    'tax_query' => array(
    array(
      'taxonomy'  => 'gym_tag',
      'field'     => 'term_id',
      'terms'     => $tag_id,
      'operator'  => 'NOT IN'
      )
    ),
    'meta_key' => 'price_per',
    'meta_query' => array(
      array(
        'key' => 'prefecture',
        'value' => $key,
        'compare'=>'=',
      ),
      'relation' => 'AND'
    )
  );
  $my_query = new WP_Query($args);
  $count = 0;
  
  if ( $my_query->have_posts() ) {
    while ( $my_query->have_posts() ) {
      $my_query->the_post();
      $post_id = $my_query->posts[$count]->ID;
      $post_thumbnail_url = get_the_post_thumbnail_url( $post_id, 'full' );
      $content_text = apply_filters('the_content',$my_query->posts[$count]->post_content);
      $title = $my_query->posts[$count]->post_title;
      // ベースとなるジムのアフィコードを取得
      $aficode = get_post_meta($post_id, 'aficode', true);

      $content .= '<div class="gym-content">';
      $content .= '<h2 class="gym-content__title">' .  $title . '</h2>';
      $content .= '<div class="gym-content__thumb"><img src="' . $post_thumbnail_url . '" alt="' . $title . '"></div>';
      $content .= do_shortcode( $content_text );
      if( $aficode ) $content .= '<p class="gym-content__btn">' . $aficode . '</p>';
      $content .= '</div>';
      $count++;
    }
  }

  // 上書きされた$postを元に戻す
  wp_reset_postdata();
  return do_shortcode( $content );

}
add_shortcode('prefecture', 'getGymByPrefecture');

/**
 * （新）都道府県と女性限定・おすすめのジムを取得
 */
function getGymForWoman($atts) {

  $content = '';
  $todofuken = $atts["todofuken"];

  $tag = get_term_by('name', '重複', 'gym_tag');
  $tag_id = $tag->term_id;

  $meta_query_args = array(
    array(
      'key' => 'todofuken',
      'value' => $todofuken,
      'compare'=>'=',
    ),
    array(
      array(
        'key' => 'woman-only',
        'value' => '1',
        'compare'=>'='
      ),
      array(
        'key' => 'woman-osusume',
        'value' => '1',
        'compare'=>'=',
      ),
      array(
        'key' => 'woman-only',
        'value' => '2',
        'compare'=>'='
      ),
      array(
        'key' => 'woman-osusume',
        'value' => '2',
        'compare'=>'=',
      ),
      'relation' => 'OR'
    ),
    'relation' => 'AND'
  );

  $args = array(
    'post_type'        => 'todofuken',
    'posts_per_page'   => -1,
    'orderby'          => 'meta_value_num',
    'order'            => 'ASC',
    'meta_key' => 'price_per',
    'meta_query' => $meta_query_args
  );
  $my_query = new WP_Query($args);
  $count = 0;

  if ( $my_query->have_posts() ) {
    while ( $my_query->have_posts() ) {
      $my_query->the_post();
      $post_id = $my_query->posts[$count]->ID;

      // ベースのジム情報のidを取得
      $meta_values = get_post_meta($post_id, 'base_gym', true);
      $post_base_obj = get_page_by_path( $meta_values, OBJECT, 'gym' );
      $post_base_id = $post_base_obj->ID;

      $post_thumbnail_url = get_the_post_thumbnail_url( $post_id, 'full' );
      if ( empty( $post_thumbnail_url ) ) $post_thumbnail_url = get_the_post_thumbnail_url( $post_base_id, 'full' );
      
      $content_text = apply_filters('the_content',$my_query->posts[$count]->post_content);
      $title = $my_query->posts[$count]->post_title;
      // ベースとなるジムのアフィコードを取得
      $aficode = get_post_meta($post_id, 'aficode', true);

      $content .= '<div class="gym-content">';
      $content .= '<h2 class="gym-content__title">' .  $title . '</h2>';
      $content .= '<div class="gym-content__thumb"><img src="' . $post_thumbnail_url . '" alt="' . $title . '"></div>';
      $content .= $content_text;
      if( $aficode ) $content .= '<p class="gym-content__btn">' . $aficode . '</p>';
      $content .= '</div>';
      $count++;
    }
  }
  // 上書きされた$postを元に戻す
  wp_reset_postdata();
  return do_shortcode( $content );

}
add_shortcode('woman', 'getGymForWoman');

/**
 * 分割・クレジット支払いのジムを取得
 */
function get_gym_available_credit($atts) {

  $content = '';
  $place = $atts["place"];

  $tag = get_term_by('name', '重複', 'gym_tag');
  $tag_id = $tag->term_id;

  $meta_query_args = array(
    array(
      'key' => 'prefecture',
      'value' => $place,
      'compare'=>'=',
    ),
    array(
      array(
        'key' => 'credit-card',
        'value' => '1',
        'compare'=>'='
      ),
      array(
        'key' => 'credit-card',
        'value' => '2',
        'compare'=>'='
      ),
      array(
        'key' => 'installment-payment',
        'value' => '1',
        'compare'=>'=',
      ),
      array(
        'key' => 'installment-payment',
        'value' => '2',
        'compare'=>'=',
      ),
      'relation' => 'OR'
    ),
    'relation' => 'AND'
  );

  $args = array(
    'post_type'        => 'gym',
    'posts_per_page'   => -1,
    'orderby'          => 'meta_value_num',
    'order'            => 'ASC',
    'tax_query' => array(
    array(
      'taxonomy'  => 'gym_tag',
      'field'     => 'term_id',
      'terms'     => $tag_id,
      'operator'  => 'NOT IN'
      )
    ),
    'meta_key' => 'price_per',
    'meta_query' => $meta_query_args
  );
  $my_query = new WP_Query($args);
  $count = 0;

  if ( $my_query->have_posts() ) {
    while ( $my_query->have_posts() ) {
      $my_query->the_post();
      $post_id = $my_query->posts[$count]->ID;
      $post_thumbnail_url = get_the_post_thumbnail_url( $post_id, 'full' );
      $content_text = apply_filters('the_content',$my_query->posts[$count]->post_content);
      $title = $my_query->posts[$count]->post_title;
      // ベースとなるジムのアフィコードを取得
      $aficode = get_post_meta($post_id, 'aficode', true);

      $content .= '<div class="gym-content">';
      $content .= '<h2 class="gym-content__title">' .  $title . '</h2>';
      $content .= '<div class="gym-content__thumb"><img src="' . $post_thumbnail_url . '" alt="' . $title . '"></div>';
      $content .= $content_text;
      if( $aficode ) $content .= '<p class="gym-content__btn">' . $aficode . '</p>';
      $content .= '</div>';
      $count++;
    }
  }
  // 上書きされた$postを元に戻す
  wp_reset_postdata();
  return do_shortcode( $content );

}
add_shortcode('credit', 'get_gym_available_credit');


/**
 * エリアからクレジット、分割払いができるジムを検索するショートコード
 */
function get_gym_available_credit_by_area($atts) {
  $content = '';

  $region = $atts["region"];

  if( !isset($region) ) return;

  $args = array(
    'post_type'        => 'studio',
    'posts_per_page'   => -1,
    'orderby'          => 'meta_value_num',
    'order'            => 'ASC',
    'meta_key' => 'price_per',
    'meta_query' => array(
      array(
        'key' => 'region',
        'value' => $region,
        'compare'=>'=',
      )
    )
  );
  // いったんエリアのジムを全部取得する
  $my_query = new WP_Query($args);
  $count = 0;
  $prev_meta_value = '';

  if ( $my_query->have_posts() ) {
    while ( $my_query->have_posts() ) {
      $my_query->the_post();
      $post_id = $my_query->posts[$count]->ID;

      // 店舗からジムのIDを取得する
      $meta_values = get_post_meta($post_id, 'base_gym', true);
      $post_base_obj = get_page_by_path( $meta_values, OBJECT, 'gym' );
      $post_base_id = $post_base_obj->ID;

      $credit_value = get_post_meta($post_base_id, 'credit-card', true);
      $installment_payment_value = get_post_meta($post_base_id, 'installment-payment', true);

      if( empty( $credit_value ) && empty( $installment_payment_value ) ) {
        $count++;
        continue; //特徴が該当しなければ表示しない
      }
      $duplication = $meta_values === $prev_meta_value; // bool
      

      $post_thumbnail_url = get_the_post_thumbnail_url( $post_id, 'full' );

      if ( !$post_thumbnail_url ) $post_thumbnail_url = get_the_post_thumbnail_url( $post_base_id, 'full' );

      $content_text = apply_filters('the_content',$my_query->posts[$count]->post_content);
      $title = $my_query->posts[$count]->post_title;

      // ベースとなるジムのアフィコードを取得
      $aficode = get_post_meta( $post_id, 'aficode', true );

      if( empty( $aficode ) ) {
        $aficode = get_post_meta( $post_base_id, 'aficode', true );
      }
      
      $content .= !$duplication ? '<div class="gym-content">' : '<div class="gym-content gym-content--duplication">' ;
      $content .= '<h2 class="gym-content__title">' .  $title . '</h2>';
      $content .= '<div class="gym-content__thumb"><img src="' . $post_thumbnail_url . '" alt="' . $title . '"></div>';
      if( $duplication ) $content .= '<p class="gym-content__text-same">※ジムの内容は上の店舗と同じ</p>';
      $content .= $content_text;
      if( $aficode ) $content .= '<p class="gym-content__btn">' . $aficode . '</p>';
      $content .= '<p class="gym-content__detail"><a href="' . get_the_permalink() . '">ジム情報を見る</a></p>';
      $content .= '</div>';
      $count++;

      $prev_meta_value = $meta_values;
    }
  }
  // 上書きされた$postを元に戻す
  wp_reset_postdata();
  return do_shortcode( $content );
}
add_shortcode('credit2', 'get_gym_available_credit_by_area');

/**
 * 特徴と地域からジムを取得するショートコード
 */
function get_gym_by_feature($atts) {
  $content = '';

  $feature = $atts["feature"];
  $prefecture = $atts["prefecture"];

  if( !isset($feature) && !isset($prefecture) ) return;

  $tag = get_term_by('name', '重複', 'gym_tag');
  $tag_id = $tag->term_id;

  $meta_query_args = array(
    array(
      'key' => 'prefecture',
      'value' => $prefecture,
      'compare'=>'=',
    ),
    array(
      array(
        'key' => $feature,
        'value' => '1',
        'compare'=>'='
      ),
      array(
        'key' => $feature,
        'value' => '2',
        'compare'=>'='
      ),
      'relation' => 'OR',
    ),
    'relation' => 'AND'
  );

  $args = array(
    'post_type'        => 'gym',
    'posts_per_page'   => -1,
    'orderby'          => 'meta_value_num',
    'order'            => 'ASC',
    'tax_query' => array(
      // 重複タグがある場合、除外する
      array(
        'taxonomy'  => 'gym_tag',
        'field'     => 'term_id',
        'terms'     => $tag_id,
        'operator'  => 'NOT IN'
      )
    ),
    'meta_key' => 'price_per',
    'meta_query' => $meta_query_args
  );
  $my_query = new WP_Query($args);
  $count = 0;

  if ( $my_query->have_posts() ) {
    while ( $my_query->have_posts() ) {
      $my_query->the_post();
      $post_id = $my_query->posts[$count]->ID;
      $post_thumbnail_url = get_the_post_thumbnail_url( $post_id, 'full' );
      $content_text = apply_filters('the_content',$my_query->posts[$count]->post_content);
      $title = $my_query->posts[$count]->post_title;
      // ベースとなるジムのアフィコードを取得
      $aficode = get_post_meta($post_id, 'aficode', true);
      
      $content .= '<div class="gym-content">';
      $content .= '<h2 class="gym-content__title">' .  $title . '</h2>';
      $content .= '<div class="gym-content__thumb"><img src="' . $post_thumbnail_url . '" alt="' . $title . '"></div>';
      $content .= $content_text;
      if( $aficode ) $content .= '<p class="gym-content__btn">' . $aficode . '</p>';
      $content .= '</div>';
      $count++;
    }
  }
  // 上書きされた$postを元に戻す
  wp_reset_postdata();
  return do_shortcode( $content );
}
add_shortcode('feature', 'get_gym_by_feature');

/**
 * エリアと特徴からジムを検索するショートコード
 */
function get_gym_by_feature2($atts) {
  $content = '';

  $feature = $atts["feature"];
  $region = $atts["region"];
  $todofuken = $atts["todofuken"];

  if( !empty( $region ) ) {
    $post_type = 'studio';
    $key = 'region';
    $value = $region;
  } else if ( !empty( $todofuken ) ) {
    $post_type = 'todofuken';
    $key = 'todofuken';
    $value = $todofuken;
  } else {
    return;
  }

  if( empty($feature) ) return;

  $args = array(
    'post_type'        => $post_type,
    'posts_per_page'   => -1,
    'orderby'          => 'meta_value_num',
    'order'            => 'ASC',
    'meta_key' => 'price_per',
    'meta_query' => array(
      array(
        'key' => $key,
        'value' => $value,
        'compare'=>'=',
      )
    )
  );
  // いったんエリアのジムを全部取得する
  $my_query = new WP_Query($args);
  $count = 0;
  $prev_meta_value = '';

  if ( $my_query->have_posts() ) {
    while ( $my_query->have_posts() ) {
      $my_query->the_post();
      $post_id = $my_query->posts[$count]->ID;

      // 店舗からジムのIDを取得する
      $meta_values = get_post_meta($post_id, 'base_gym', true);
      $post_base_obj = get_page_by_path( $meta_values, OBJECT, 'gym' );
      $post_base_id = $post_base_obj->ID;

      $feature_value = get_post_meta($post_id, $feature, true);

      if( empty( $feature_value ) ) {
        $count++;
        continue; //特徴が該当しなければ表示しない
      }
      $duplication = $meta_values === $prev_meta_value; // bool

      $post_thumbnail_url = get_the_post_thumbnail_url( $post_id, 'full' );

      if ( !$post_thumbnail_url ) $post_thumbnail_url = get_the_post_thumbnail_url( $post_base_id, 'full' );

      $content_text = apply_filters('the_content',$my_query->posts[$count]->post_content);
      $title = $my_query->posts[$count]->post_title;

      // ベースとなるジムのアフィコードを取得
      $aficode = get_post_meta( $post_id, 'aficode', true );

      if( empty( $aficode ) ) {
        $aficode = get_post_meta( $post_base_id, 'aficode', true );
      }
      
      $content .= !$duplication ? '<div class="gym-content">' : '<div class="gym-content gym-content--duplication">' ;
      $content .= '<h2 class="gym-content__title">' .  $title . '</h2>';
      $content .= '<div class="gym-content__thumb"><img src="' . $post_thumbnail_url . '" alt="' . $title . '"></div>';
      if( $duplication ) $content .= '<p class="gym-content__text-same">※ジムの内容は上の店舗と同じ</p>';
      $content .= $content_text;
      if( $aficode ) $content .= '<p class="gym-content__btn">' . $aficode . '</p>';
      $content .= '<p class="gym-content__detail"><a href="' . get_the_permalink() . '">ジム情報を見る</a></p>';
      $content .= '</div>';
      $count++;

      $prev_meta_value = $meta_values;
    }
  }
  // 上書きされた$postを元に戻す
  wp_reset_postdata();
  return do_shortcode( $content );
}
add_shortcode('feature2', 'get_gym_by_feature2');

/**
 *  Google Mapの埋め込み用
 */
function embedGoogleMap($atts, $content='') {

  global $entry_post_type;

  if( $content !== '' &&  $entry_post_type === "studio" ) {
    return '<h4 class="gmap-title">Map</h4><div class="gmap-wrap">' . $content . '</div>';
  } else {
    return '';
  }
}
add_shortcode('map', 'embedGoogleMap');

function calculationText() {
  $calc_text = '<p>計算方法は2ヵ月のコースを選択した場合の1回あたりの料金です。</p>';
  $calc_text .= '<p>2ヵ月コースがない場合はそれに近いコースで計算しています。</p>';
  $calc_text .= '<p>料金などがの情報は変更になっている可能性があります。正確な情報は必ず公式サイトで確認して下さい。</p>';
  return $calc_text;
}
add_shortcode('calc', 'calculationText');

function returnPriceOnce() {
  $price_text = '<p class="price-per-once">一回当たり：' . '円</p>';
  return $price_text;
}
add_shortcode('price', 'returnPriceOnce');

// 指定の投稿タイプでのみ出力する
function only_show_post_type( $atts, $content='' ) {
  global $entry_post_type;
  $key = $atts["type"];
  if( $content && $key === $entry_post_type ) {
    return do_shortcode($content);
  }
}
add_shortcode('posttype', 'only_show_post_type');

/**
 * アフィリエイトコードを取得する(店舗情報)
 */
function get_aficode_studio_page() {
  global $entry_post_type;

  if( $entry_post_type === 'studio' ) {
    // 店舗からジムのIDを取得する
    $post_id = get_the_ID();
    $meta_values = get_post_meta( $post_id, 'base_gym', true );
    $post_base_obj = get_page_by_path( $meta_values, OBJECT, 'gym' );
    $post_base_id = $post_base_obj->ID;

    $afilink = get_post_meta( $post_id, 'aficode', true );

    if( empty( $afilink ) ) {
      $afilink = get_post_meta( $post_base_id, 'aficode', true );
    }
  } elseif ( $entry_post_type === 'gym' ) {
    $afilink = get_post_meta( get_the_ID(), 'aficode', true );
  } else {
    return;
  }

  return '<p class="gym-content__btn">' . $afilink . '</p>';
}
add_shortcode('afilink', 'get_aficode_studio_page');


function gym_service_list() {

  $post_id = get_the_ID();

  $content = '<ul class="service__list">';
  $custom_fields = get_post_custom( $post_id );
  if( isset( $custom_fields['pickup'] )              && !empty( $custom_fields['pickup'][0] ) ) $content .= '<li class="service__item service__item--osusume">おすすめジム</li>';
  if( isset( $custom_fields['woman-only'] )          && !empty( $custom_fields['woman-only'][0] ) ) $content .= '<li class="service__item service__item--woman">女性限定</li>';
  if( isset( $custom_fields['woman-osusume'] )       && !empty( $custom_fields['woman-osusume'][0] ) ) $content .= '<li class="service__item service__item--woman-osusume">女性におすすめ</li>';
  if( isset( $custom_fields['teaching-meals'] )      && !empty( $custom_fields['teaching-meals'][0] ) ) $content .= '<li class="service__item">食事指導</li>';
  if( isset( $custom_fields['private-room'] )        && !empty( $custom_fields['private-room'][0] ) ) $content .= '<li class="service__item">完全個室</li>';
  if( isset( $custom_fields['credit-card'] )         && !empty( $custom_fields['credit-card'][0] ) ) $content .= '<li class="service__item">クレジットOK</li>';
  if( isset( $custom_fields['installment-payment'] ) && !empty( $custom_fields['installment-payment'][0] ) ) $content .= '<li class="service__item">分割支払い</li>';
  if( isset( $custom_fields['repayment'] )           && !empty( $custom_fields['repayment'][0] ) ) $content .= '<li class="service__item">返金保証あり</li>';

  $content .= '</ul>';

  if($content !== '<ul class="service__list"></ul>') {
    return $content;
  }
}
add_shortcode( 'service', 'gym_service_list' );

/**
 * 関連記事を表示するショートコード
 */
function get_kanren_article ($atts) {
  if( !empty( $atts['slug'] ) ) {
    $article_slug = $atts['slug'];
  } else {
    return '<p>記事が見つかりませんでした。</p>';
  }
  $post_object = get_page_by_path($article_slug, "OBJECT", "post");
  $post_id = $post_object->ID;
  $post_title = get_the_title( $post_id );
  $post_url = get_permalink( $post_id );

  return '<p><a href="' . $post_url . '">' . $post_title . '</a></p>';
}
add_shortcode( 'kanren', 'get_kanren_article' );


//ショートコード追加
function display_my_menu_shorcode( $atts ) {
  $nav_id = $atts["id"];
  $nav_obj = wp_get_nav_menu_items( $nav_id );

  if( !$nav_obj ) return;

  $html = '';
  $html .= '<ul class="area-nav-child__list area-nav-child__list--post">';

  foreach( $nav_obj as $nav_item ) {
    $nav_url = $nav_item->url;
    $nav_title = $nav_item->title;
    $html .= '<li class="menu-item"><a href="' . $nav_url . '">' . $nav_title . '</a></li>';
  }
  $html .= '</ul>';

  return $html;
}
add_shortcode('nav', 'display_my_menu_shorcode');

