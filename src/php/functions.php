<?php

require_once( 'lib/functions-shortcode.php' );
require_once( 'lib/functions-widget.php' );
require_once( 'lib/functions-edit.php' );
require_once( 'lib/functions-search-details.php' );

add_theme_support( 'post-thumbnails' );
add_theme_support( 'excerpt' );

add_image_size( 'sidebar-thumb', 380, 240, true );
add_image_size( 'sidebar-thumb-2x', 760, 480, true );

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
  register_post_type( "line", // 投稿タイプ名の定義
    array(
      "labels" => array(
          "name" => __( "路線" ), // 表示する投稿タイプ名
          "singular_name" => __( "路線" )
        ),
      "public" => true,
      "menu_position" =>8,
      'supports' => array(
        'title',
        'editor',
        'excerpt',
        'custom-fields',
        'post-formats'
      ), //編集画面で使用するフィールド
    )
  );
  register_post_type( "template", // 投稿タイプ名の定義
    array(
      "labels" => array(
          "name" => __( "テンプレート用" ), // 表示する投稿タイプ名
          "singular_name" => __( "テンプレート用" )
        ),
      "public" => true,
      "menu_position" =>8,
      'supports' => array(
        'title',
        'editor',
        'excerpt',
        'custom-fields',
        'post-formats'
      ), //編集画面で使用するフィールド
    )
  );
  //タグタイプの設定（カスタムタクソノミーの設定）
  register_taxonomy(
    'gym_tag', //タグ名（任意）
    'gym', //カスタム投稿名
    array(
      'hierarchical' => false, //タグタイプの指定（階層をもたない）
      'update_count_callback' => '_update_post_term_count',
      //ダッシュボードに表示させる名前
      'label' => 'タグ', 
      'public' => true,
      'show_ui' => true,
      'query_var' => true,
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

function get_review_comment() {
  $post_id =  get_the_ID();
  $comment_array = get_comments( array(
    'post_id' => $post_id,
    'number'  => 50,
    'status' => 'approve',
  ) );
  $content = '';
  
  if ( $comment_array ) {
    $content = '<ol class="review-comment__list">';
    foreach ($comment_array as $comment) {
      $stars = get_comment_meta( $comment->comment_ID, 'star', false );
      $star_num = $stars[0]; // 星の数
      $reviewer_name = $comment->comment_author; //コメントした人の名前
      $comment_text = nl2br( $comment->comment_content );
      $comment_date = $comment->comment_date;

      $content .= '<li class="review-comment__item">';
      $content .= '<p class="reviewer_name">お名前：' . $reviewer_name . '</p>';
      $content .= '<p class="comment_date">' . $comment_date . '</p>';
      if( $star_num ) {
        $content .= '<div class="star-rating"><div class="star-rating-front" style="width: ' . ($star_num/5)*100 . '%">★★★★★</div><div class="star-rating-back">★★★★★</div></div>';
      } 
      $content .= '<dl class="comment-text"><dt>コメント</dt>';
      $content .= '<dd>' . $comment_text . '</dd></dl>';
      $content .= '</li>';
    }
    $content .= '</ol>';
    echo $content;
  } else {
    echo 'コメントはまだありません。';
  }
}

function get_average_star() {
  $star_num_sum = 0;
  $comment_array = get_comments( array(
    'post_id' => get_the_ID(),
    'meta_key'=> 'star',
    'status' => 'approve',
  ) );
  
  if ( $comment_array ) {
    foreach ($comment_array as $star) {
      $stars = get_comment_meta( $star->comment_ID, 'star', false );
      $star_num_sum += intval($stars[0]);
    }
  }
  if( $star_num_sum || $comment_array ) {
    $average = $star_num_sum/count($comment_array);
    return round($average,1); 
  }
}
/** ===================================================
 * 口コミ情報を表示するここまで
 ======================================================*/

function get_recommend_gym($num, $position = 'sidebar') {
  
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
      $post_thumbnail_url = get_the_post_thumbnail_url( $post_id, 'sidebar-thumb' );
      $post_thumbnail_url_2x = get_the_post_thumbnail_url( $post_id, 'sidebar-thumb-2x' );
      $link = get_the_permalink();
      $title = $my_query->posts[$count]->post_title;
      $content .= '<div class="recommend-gym recommend-gym--' . $position . '">';
      $content .= '<h3 class="recommend-gym__title"><a href="' . $link . '">' .  $title . '</a></h3>';
      $content .= '<div class="recommend-gym__thumb"><a href="' . $link . '"><img src="' . $post_thumbnail_url . '" srcset="' . $post_thumbnail_url . ' 1x, ' . $post_thumbnail_url_2x . ' 2x" alt="' . $title . '"></a></div>';
      $content .= '<p class="recommend-gym__detail"><a href="' . $link . '">このジムの詳細を見る</a></p>';
      $content .= '</div>';
      $count++;
    }
  }
  // 上書きされた$postを元に戻す
  wp_reset_postdata();
  return $content;
}

/**
 * ジムページの下に店舗一覧を表示する関数
 */
function get_gym_region() {

  if( get_post_type() !== 'gym' ) {
    return;
  }

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
  wp_reset_postdata();
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

/**
 * feedの設定
 */
remove_action('do_feed_rdf', 'do_feed_rdf');
remove_action('do_feed_rss', 'do_feed_rss');
remove_action('do_feed_rss2', 'do_feed_rss2');
remove_action('do_feed_atom', 'do_feed_atom');
// サイト全体の記事更新フィード、サイト全体のコメントフィードリンクの削除
remove_action('wp_head', 'feed_links', 2);
// 記事のコメント、記事アーカイブ、カテゴリなどのフィードリンクの削除
remove_action('wp_head', 'feed_links_extra', 3);

/**
 * 親ページ判別
 */
function is_child( $slug = "" ) {
  if( is_singular() )://投稿ページのとき（固定ページ含）
    global $post;
    if ( $post->post_parent ) {//現在のページに親がいる場合
      $post_data = get_post($post->post_parent);//親ページの取得
      if($slug != "") {//$slugが空じゃないとき
        if(is_array($slug)) {//$slugが配列のとき
          for($i = 0 ; $i <= count($slug); $i++) {
            if($slug[$i] == $post_data->post_name || $slug[$i] == $post_data->ID || $slug[$i] == $post_data->post_title) {//$slugの中のどれかが親ページのスラッグ、ID、投稿タイトルと同じのとき
              return true;
            }
          }
        } elseif($slug == $post_data->post_name || $slug == $post_data->ID || $slug == $post_data->post_title) {//$slugが配列ではなく、$slugが親ページのスラッグ、ID、投稿タイトルと同じのとき
          return true;
        } else {
          return false;
        }
      } else {//親ページは存在するけど$slugが空のとき
        return true;
      }
    }else {//親ページがいない
      return false;
    }
  endif;
}

/**
 * タイトルのカスタマイズ
 */
add_theme_support( 'title-tag' );

function wp_document_title_separator( $separator ) {
  $separator = '|';
  return $separator;
}
add_filter( 'document_title_separator', 'wp_document_title_separator' );

function wp_document_title_parts( $title ) {
  if ( is_home() || is_front_page() ) {
    unset( $title['tagline'] ); // キャッチフレーズを出力しない
  } else if ( is_category() ) {
    $title['title'] = '「' . $title['title'] . '」カテゴリーの記事一覧';
  } else if ( is_tag() ) {
    $title['title'] = '「' . $title['title'] . '」タグの記事一覧';
  } else if ( is_archive() ) {
    $title['title'] = $title['title'] . 'の記事一覧';
  }
  return $title;
}
add_filter( 'document_title_parts', 'wp_document_title_parts', 10, 1 );
