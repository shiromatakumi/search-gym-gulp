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
    return apply_filters( 'the_content', $content );
  }
}
add_shortcode('base', 'getBaseGymData');

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

  /**
   * the_post()でグローバル変数$postが上書きされてしまうので、
   * 一旦変数に格納して代入し直す
   */
  global $post;
  $temp_post = $post;

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
      $content .= '<p class="gym-content__detail"><a href="' . get_the_permalink() . '">このジムの詳細を見る</a></p>';
      $content .= '</div>';
      $count++;
    }
  }

  // 上書きされた$postを元に戻す
  $post = $temp_post;
  return do_shortcode( $content );
}
add_shortcode('region', 'getGymByRegion');

//　Google Mapの埋め込み用
function embedGoogleMap($atts, $content='') {

  if( $content !== '' ) {
    $embed_position = '<div class="js-studio-map"></div>';
    $content = str_replace(array("\r\n", "\r", "\n"), '', $content);
    $content = rtrim($content, "<br />");
    $content = rtrim($content, "<br>");
    $map_js = "<script> var iframeGoogleMap = '" . $content . "'; </script>";

    return $embed_position . $map_js;
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
