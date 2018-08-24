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
    $post_id = get_page_by_path($gym_slug, "OBJECT", "gym");
    $post_id = $post_id->ID;
    $content = get_post_field( 'post_content', $post_id );
    $content = do_shortcode($content);
    if( $entry_post_type === 'studio' ) {
      $afilink = get_post_meta( $post_id, 'aficode', true );
      $afilink = '<p class="gym-content__btn">' . $afilink . '</p>';
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
// test
function getStationByLine() {
  
  include locate_template( 'lines.php' );

  $content = '';

  foreach( $lines_array as $line => $stations ) {

    if( empty($line) || empty($stations) ) return;

    $content .= '<div class="search-line" id="' . $line . '"><h3 class="search-line__title">' . $line . '</h3>';
    $content .= '<ul class="search-line__list">';

    foreach( $stations as $station ) {

      $count = 0;
      $args = array(
        'post_type'        => 'post',
        'posts_per_page'   => 1,
        'meta_key' => 'area',
        'meta_value' => $station,
      );
      $my_query = new WP_Query($args);

      if ( $my_query->have_posts() ) {
        
        while ( $my_query->have_posts() ) {
          $my_query->the_post();
          $post_id = $my_query->posts[$count]->ID;

          $link = get_the_permalink();

          $content .= '<li class="search-line__item"><a href="' . $link . '">' . $station . '</a></li>';

          $count++;
        }
        
      } else {
        // $content .= '<li class="search-line__item search-line__item--none">' . $station . '</li>';
      }
    }
    $content .= '</ul></div>';
  }
  // 上書きされた$postを元に戻す
  wp_reset_postdata();
  return $content;
}
add_shortcode('test', 'getStationByLine');


/**
 * 鉄道会社から駅を取得するショートコード
 */
function get_station_by_railway_co($atts) {
  
  //
  include locate_template( 'lines.php' );

  $content = '';

  $co_name = $atts['co'];
  $co_lines = $railway_co[$co_name];

  foreach( $co_lines as $line  ) {

    if( empty( $line ) ) return;

    $content .= '<div class="search-line" id="' . $line . '"><h3 class="search-line__title">' . $line . '</h3>';
      $content .= '<ul class="search-line__list">';

    $stations_array = $lines_array[$line];
    /*
     $co_linesは駅名が入った配列
     $lineは駅名
     $stations_arrayは駅の配列
     */
    foreach( $stations_array as $station) {
      if( empty( $station ) ) return;
      $count = 0;
      $args = array(
        'post_type'        => 'post',
        'posts_per_page'   => 1,
        'meta_key' => 'area',
        'meta_value' => $station,
      );
      $my_query = new WP_Query($args);

      if ( $my_query->have_posts() ) {
        
        while ( $my_query->have_posts() ) {
          $my_query->the_post();
          $post_id = $my_query->posts[$count]->ID;

          $link = get_the_permalink();

          $content .= '<li class="search-line__item"><a href="' . $link . '">' . $station . '</a></li>';

          $count++;
        } 
      }
    }
    $content .= '</ul></div>';
  }
  // 上書きされた$postを元に戻す
  wp_reset_postdata();

  return $content;
}
add_shortcode('railway', 'get_station_by_railway_co');

/**
 * 路線から店舗を取得するショートコード
 */
function get_studio_lines($atts){
  include locate_template( 'lines.php' );

  $content = '';
  $meta_query = array();
  $line = $atts['line'];
  $stations_array = $lines_array[$line];

  if( empty( $line ) || empty( $stations_array ) ) return;

  foreach( $stations_array as $station ) {
    $meta_query[] = array(
      'key' => 'region',
      'value' => $station,
      'compare' => '='
    );
  }
  $meta_query['relation'] = 'OR';

  $args = array(
    'post_type'        => 'studio',
    'posts_per_page'   => -1,
    'orderby'          => 'meta_value_num',
    'order'            => 'ASC',
    'meta_key' => 'price_per',
    'meta_query' => $meta_query
  );


  $my_query = new WP_Query($args);
  $count = 0;

  // var_dump($my_query);
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

  return $content;
}
add_shortcode('studio_by_line', 'get_studio_lines');


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
  $hit_count = 0;

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
      $hit_count++;
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
        'key' => 'installment-payment',
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

      if( $credit_value !== '1' && $installment_payment_value !== '1' ) {
        $count++;
        continue; //特徴が該当しなければ表示しない
      }
      $post_thumbnail_url = get_the_post_thumbnail_url( $post_id, 'full' );
      $content_text = apply_filters('the_content',$my_query->posts[$count]->post_content);
      $title = $my_query->posts[$count]->post_title;

      // ベースとなるジムのアフィコードを取得
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
      'key' => $feature,
      'value' => '1',
      'compare'=>'='
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

  if( !isset($feature) && !isset($region) ) return;

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

  if ( $my_query->have_posts() ) {
    while ( $my_query->have_posts() ) {
      $my_query->the_post();
      $post_id = $my_query->posts[$count]->ID;

      // 店舗からジムのIDを取得する
      $meta_values = get_post_meta($post_id, 'base_gym', true);
      $post_base_obj = get_page_by_path( $meta_values, OBJECT, 'gym' );
      $post_base_id = $post_base_obj->ID;

      $feature_value = get_post_meta($post_base_id, $feature, true);

      if( $feature_value !== '1') {
        $count++;
        continue; //特徴が該当しなければ表示しない
      }
      $post_thumbnail_url = get_the_post_thumbnail_url( $post_id, 'full' );
      $content_text = apply_filters('the_content',$my_query->posts[$count]->post_content);
      $title = $my_query->posts[$count]->post_title;

      // ベースとなるジムのアフィコードを取得
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
    return $content;
  }
}
add_shortcode('posttype', 'only_show_post_type');

/**
 * アフィリエイトコードを取得する(店舗情報)
 */
function get_aficode_studio_page() {
  global $entry_post_type;

  if( $entry_post_type !== 'studio' ) return;
  // 店舗からジムのIDを取得する
  $meta_values = get_post_meta( get_the_ID(), 'base_gym', true );
  $post_base_obj = get_page_by_path( $meta_values, OBJECT, 'gym' );
  $post_base_id = $post_base_obj->ID;
  $afilink = get_post_meta($post_base_id, 'aficode', true);

  return '<p class="gym-content__btn">' . $afilink . '</p>';
}
add_shortcode('afilink', 'get_aficode_studio_page');


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
  if( isset( $custom_fields['pickup'] ) && $custom_fields['pickup'][0] === '1' ) $content .= '<li class="service__item service__item--osusume">おすすめジム</li>';
  if( isset( $custom_fields['woman-only'] ) && $custom_fields['woman-only'][0] === '1' ) $content .= '<li class="service__item service__item--woman">女性限定</li>';
  if( isset( $custom_fields['woman-osusume'] ) && $custom_fields['woman-osusume'][0] === '1' ) $content .= '<li class="service__item service__item--woman-osusume">女性におすすめ</li>';
  if( isset( $custom_fields['teaching-meals'] ) && $custom_fields['teaching-meals'][0] === '1' ) $content .= '<li class="service__item">食事指導</li>';
  if( isset( $custom_fields['private-room'] ) && $custom_fields['private-room'][0] === '1' ) $content .= '<li class="service__item">完全個室</li>';
  if( isset( $custom_fields['credit-card'] ) && $custom_fields['credit-card'][0] === '1' ) $content .= '<li class="service__item">クレジットOK</li>';
  if( isset( $custom_fields['installment-payment'] ) && $custom_fields['installment-payment'][0] === '1' ) $content .= '<li class="service__item">分割支払い</li>';
  if( isset( $custom_fields['repayment'] ) && $custom_fields['repayment'][0] === '1' ) $content .= '<li class="service__item">返金保証あり</li>';

  $content .= '</ul>';

  if($content !== '<ul class="service__list"></ul>') {
    return $content;
  }
}
add_shortcode( 'service', 'gym_service_list' );
