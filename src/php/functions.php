<?php
require_once( 'lib/functions-shortcode.php' );
require_once( 'lib/functions-widget.php' );
require_once( 'lib/functions-edit.php' );

add_theme_support( 'post-thumbnails' );


/**
 * メニューの設定
 */
if ( ! function_exists( 'sg_setup_nav' ) ) {
  function sg_setup_nav() {
    register_nav_menus( array(
      'home'    => 'トップページ',
      'global'  => 'グローバルナビ',
      'header'  => 'ヘッダーナビ',
      'footer'  => 'フッターナビ',
      'tokyo'   => '東京',
      'kanto'   => '関東',
      'hokkaido'   => '北海道',
      'tohoku'   => '東北',
      'hokuriku'   => '北陸',
      'koushinetsu'   => '甲信越',
      'tokai'   => '東海',
      'osaka'   => '大阪',
      'kinki'   => '近畿',
      'chugoku'   => '中国',
      'shikoku'   => '四国',
      'kyusyu'   => '九州',
      'okinawa'   => '沖縄',
    ) );
  }
}
add_action( 'after_setup_theme', 'sg_setup_nav' );

// メニューが設定されているか判定
function is_active_nav_menu($location){
 if(has_nav_menu($location)){
   $locations = get_nav_menu_locations();
   $menu = wp_get_nav_menu_items($locations[$location]);
   if(!empty($menu)){
       return true;
   }
 }
 return false;
}

// カスタム投稿タイプの追加
function create_post_type() {
  register_post_type( "gym", // 投稿タイプ名の定義
    array(
      "labels" => array(
          "name" => __( "ジム情報" ), // 表示する投稿タイプ名
          "singular_name" => __( "ジム情報" )
        ),
      "public" => true,
      "menu_position" =>5,
      'supports' => array(
        'title',
        'editor',
        'excerpt',
        'thumbnail',
        'custom-fields',
        'post-formats',
        'comments'
      ), //編集画面で使用するフィールド
    )
  );
  register_post_type( "studio", // 投稿タイプ名の定義
    array(
      "labels" => array(
          "name" => __( "店舗情報" ), // 表示する投稿タイプ名
          "singular_name" => __( "店舗情報" )
        ),
      "public" => true,
      "menu_position" =>4,
      'supports' => array(
        'title',
        'editor',
        'excerpt',
        'thumbnail',
        'custom-fields',
        'post-formats',
        'comments'
      ), //編集画面で使用するフィールド
    )
  );
}
add_action( "init", "create_post_type" );

// 検索でカスタム投稿タイプの投稿を検索対象から除きます。
function search_exclude_custom_post_type( $query ) {
  if ( $query->is_search() && $query->is_main_query() && ! is_admin() ) {
    $query->set( 'post_type', array( 'post', 'page' , 'studio' ) );
  }
}
add_filter( 'pre_get_posts', 'search_exclude_custom_post_type' );

/** ===================================================
 * コメント欄を口コミ風にカスタマイズ
 ======================================================*/
// 入力項目欄。
function change_comment_form_kuchikomi($default) {
  $commenter = wp_get_current_commenter();
    $default['title_reply'] = 'クチコミを投稿する';
    $default['fields']['cookies'] = '';
    $default['fields']['url'] = "";
    $default['fields']['email'] = '<p class="comment-form-author">' .
    '<label for="sex">'. __('性別') . '</label>
    <select id="sex" class="sex" name="sex">
      <option value="">--
      <option value="男性">男性
      <option value="女性">女性
    </select>
    </p>
    <div class="comment-form-star"><p class="comment-form-star__text">満足度</p>
    <div class="evaluation">
    <input id="star1" type="radio" name="star" value="5" />
    <label for="star1">★</label>
    <input id="star2" type="radio" name="star" value="4" />
    <label for="star2">★</label>
    <input id="star3" type="radio" name="star" value="3" />
    <label for="star3">★</label>
    <input id="star4" type="radio" name="star" value="2" />
    <label for="star4">★</label>
    <input id="star5" type="radio" name="star" value="1" />
    <label for="star5">★</label>
  </div></div>';
  return $default;
}
add_filter( 'comment_form_defaults','change_comment_form_kuchikomi');

// 次に男女選択項目欄を入力必須にするためのコード
function verify_comment_meta_data_kuchikomi($commentdata) {
  if ( ! isset( $_POST['sex'] ) || ! isset( $_POST['star'] ) )
        wp_die( __('Error: please fill the required field.') );
    return $commentdata;
}
add_filter( 'preprocess_comment', 'verify_comment_meta_data_kuchikomi' );

// 入力（選択）された情報をコメント情報と一緒にデータベースに登録するためのコード
function save_comment_meta_data_kuchikomi( $comment_id ) {
  $sexies = explode(',', $_POST['sex']);
  foreach ($sexies as $sex)
    echo update_comment_meta( $comment_id, 'sex', $sex, true);

  $stars = explode(',', $_POST['star']);
  foreach ($stars as $star)
    echo update_comment_meta( $comment_id, 'star', $star, true);
}
add_action( 'comment_post', 'save_comment_meta_data_kuchikomi' );
/** ===================================================
 * コメント欄を口コミ風にカスタマイズここまで
 ======================================================*/

/** ===================================================
 * 口コミ情報を表示する
 ======================================================*/
// 表示するためのコード
function attach_sex_to_author( $author ) {
  /*
    get_comment_meta(コメントID,コメントメタ情報のキー名,bool)
    bool=> 同じキー名がある場合、最初に見つかった値を取得する場合はtrue、すべての値を配列で取得する場合はfalseを指定
  */
  $sexies = get_comment_meta( get_comment_ID(), 'sex', false );
  $stars = get_comment_meta( get_comment_ID(), 'star', false );
  $author = '<p class="author-name">' . $author . '</p>';
  /* false なので配列 */
  if ( $sexies ) {
    foreach ($sexies as $sex)
      $author .= '<p class="author-sex">' . $sex . '</p> ';
  }
  if ( $stars ) {
    foreach ($stars as $star)
      $author .= '<p class="author-star star-num-' . $star . '"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></p> ';
  }
  return $author;
}
add_filter( 'get_comment_author_link', 'attach_sex_to_author' );


function get_average_star() {
  $star_num_sum = 0;
  $comment_array = get_comments( array(
    'post_id' => get_the_ID(),
    'meta_key'=> 'star'
  ) );
  if ( $comment_array ) {
    foreach ($comment_array as $star) {
      $stars = get_comment_meta( $star->comment_ID, 'star', false );
      $star_num_sum += intval($stars[0]);
    }
  }
  if( $star_num_sum || $comment_array ) {
    return $star_num_sum/count($comment_array); 
  }
}
/** ===================================================
 * 口コミ情報を表示するここまで
 ======================================================*/

function get_recommend_gym($num) {
  /**
   * the_post()でグローバル変数$postが上書きされてしまうので、
   * 一旦変数に格納して代入し直す
   */
  global $post;
  $temp_post = $post;

  $content = '';
  $count = 0;
  $args = array(
    'post_type'        => 'gym',
    'posts_per_page'   => $num,
    'order'           => 'ASC',
    'meta_key' => 'recommend',
    'meta_value' => '1',
    'relation' => 'AND'
  );
  $my_query = new WP_Query($args);

  if ( $my_query->have_posts() ) {
    while ( $my_query->have_posts() ) {
      $my_query->the_post();
      $post_id = $my_query->posts[$count]->ID;
      $post_thumbnail_url = get_the_post_thumbnail_url( $post_id, 'large' );
      $link = get_the_permalink();
      $title = $my_query->posts[$count]->post_title;
      $content .= '<div class="recommend-gym">';
      $content .= '<h3 class="recommend-gym__title"><a href="' . $link . '">' .  $title . '</a></h3>';
      $content .= '<div class="recommend-gym__thumb"><a href="' . $link . '"><img src="' . $post_thumbnail_url . '" alt="' . $title . '"></a></div>';
      $content .= '<p class="recommend-gym__detail"><a href="' . $link . '">このジムの詳細を見る</a></p>';
      $content .= '</div>';
      $count++;
    }
  }
  // 上書きされた$postを元に戻す
  $post = $temp_post;
  return $content;
}

function get_gym_region() {

  if( get_post_type() !== 'gym' ) {
    return;
  }

  /**
   * the_post()でグローバル変数$postが上書きされてしまうので、
   * 一旦変数に格納して代入し直す
   */
  global $post;
  $temp_post = $post;

  $slug_name = get_post_field( 'post_name', get_the_ID() ); // base-○○
  $args = array(
    'post_type' => 'studio',
    'posts_per_page' => -1,
    'order'           => 'ASC',
    'meta_key' => 'base_gym',
    'meta_value' => $slug_name
  );
  $my_query = new WP_Query($args);
  $count = 0;
  $content = '';

  if ( $my_query->have_posts() ) {
    $content .= '<div class="gym-region"><h3 class="gym-region__title">店舗一覧</h3>';
    $content .= '<ul class="gym-region__list">';
    while ( $my_query->have_posts() ) {
      $my_query->the_post();
      $post_id = $my_query->posts[$count]->ID;
      $meta_values = get_post_meta($post_id, 'region', false);
      foreach( $meta_values as $region ) {
        $content .=  '<li class="gym-region__item"><a href="' . get_the_permalink(). '">' . $region . '</a></li>';
      }
      $count++;
    }
    $content .= '</ul></div>';
  }
  // 上書きされた$postを元に戻す
  $post = $temp_post;
  echo $content;
}

/**
 * 外観→カスタマイズの設定
 */
function theme_customizer_extension($wp_customize) {
  //セクション
  $wp_customize->add_section( 'access-tag', array (
   'title' => 'アクセス解析タグ',
   'priority' => 100,
  ));
    //テーマ設定
    $wp_customize->add_setting( 'analytics', array (
      'type' => 'option'
    ));
     //コントロールの追加
    $wp_customize->add_control( 'analytics', array(
      'section' => 'access-tag',
      'settings' => 'analytics',
      'label' =>'アクセス解析タグの挿入',
      'description' => 'googleアナリティクスのIDを入力してください。<br>UA-xxxxxxxxxx-xxの部分のみ',
      'type' => 'textarea',
      'priority' => 20,
    ));
    //テーマ設定
    $wp_customize->add_setting( 'search-console', array (
      'type' => 'option'
    ));
     //コントロールの追加
    $wp_customize->add_control( 'search-console', array(
      'section' => 'access-tag',
      'settings' => 'search-console',
      'label' =>'アクセス解析タグの挿入',
      'description' => 'search consoleのタグを入力してください。<br>content="ここの部分"',
      'type' => 'textarea',
      'priority' => 20,
    ));
}
add_action('customize_register', 'theme_customizer_extension');
