<?php

/**
 * ショートコード
 */
// ジム情報を出力するショートコード
function getBaseGymData($atts) {
  $gym_slug = $atts['slug'];

  if( isset( $gym_slug ) ) {
    $post_id = get_page_by_path($gym_slug, "OBJECT", "gym");
    $post_id = $post_id->ID;
    $content = get_post_field( 'post_content', $post_id );
    $content = do_shortcode($content);
    return apply_filters( 'the_content', $content );
  }
}
add_shortcode('base', 'getBaseGymData');

// test
function getStationByLine() {
  get_template_part( 'lines' );
}
add_shortcode('test', 'getStationByLine');

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
      $meta_values = get_post_meta($post_id, 'base_gym', true);
      $post_base_obj = get_page_by_path( $meta_values, OBJECT, 'gym' );
      $post_base_id = $post_base_obj->ID;
      $aficode = get_post_meta($post_base_id, 'aficode', true);

      $content .= '<div class="gym-content">';
      $content .= '<h2 class="gym-content__title">' .  $title . '</h2>';
      $content .= '<div class="gym-content__thumb"><img src="' . $post_thumbnail_url . '" alt="' . $title . '"></div>';
      $content .= $content_text;
      if( $aficode ) $content .= '<p class="gym-content__btn">' . $aficode . '</p>';
      $content .= '<p class="gym-content__detail"><a href="' . get_the_permalink() . '">詳細を見る</a></p>';
      $content .= '</div>';
      $count++;
    }
  }

  // 上書きされた$postを元に戻す
  wp_reset_postdata();
  return do_shortcode( $content );
}
add_shortcode('region', 'getGymByRegion');

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
add_shortcode('prefecture', 'getGymByPrefecture');

/**
 * 都道府県と女性限定・おすすめのジムを取得
 */
function getGymForWoman($atts) {

  $content = '';
  $key = $atts["place"];

  $tag = get_term_by('name', '重複', 'gym_tag');
  $tag_id = $tag->term_id;

  $meta_query_args = array(
    array(
      'key' => 'prefecture',
      'value' => $key,
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
add_shortcode('woman', 'getGymForWoman');

//　Google Mapの埋め込み用
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
    return $content;
  }
}
add_shortcode('posttype', 'only_show_post_type');

function gym_service_list() {

  $post_id = get_the_ID();

  if(get_post_type() === 'studio') {
    $custom_fields = get_post_custom( $post_id );
    $parent_slug = $custom_fields['base_gym'][0];
    $post_id = get_page_by_path($parent_slug, OBJECT, "gym");
    $post_id = $post_id->ID;
  }

  $content = '<ul class="service__list">';
  $custom_fields = get_post_custom( $post_id );
  if( $custom_fields['pickup'][0] === '1' ) $content .= '<li class="service__item service__item--osusume">おすすめジム</li>';
  if( $custom_fields['woman-only'][0] === '1' ) $content .= '<li class="service__item service__item--woman">女性限定</li>';
  if( $custom_fields['woman-osusume'][0] === '1' ) $content .= '<li class="service__item service__item--woman-osusume">女性におすすめ</li>';
  if( $custom_fields['teaching-meals'][0] === '1' ) $content .= '<li class="service__item">食事指導</li>';
  if( $custom_fields['private-room'][0] === '1' ) $content .= '<li class="service__item">完全個室</li>';
  if( $custom_fields['credit-card'][0] === '1' ) $content .= '<li class="service__item">クレジットOK</li>';
  if( $custom_fields['installment-payment'][0] === '1' ) $content .= '<li class="service__item">分割支払い</li>';
  if( $custom_fields['repayment'][0] === '1' ) $content .= '<li class="service__item">返金保証あり</li>';

  $content .= '</ul>';

  if($content !== '<ul class="service__list"></ul>') {
    return $content;
  }
}
add_shortcode( 'service', 'gym_service_list' );
