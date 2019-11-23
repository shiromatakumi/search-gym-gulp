<?php 
/**
 * 投稿ページの設定
 */
/**
 * 投稿画面テキストエディタのボタンを増やす
 */
if(!function_exists( 'gym_add_quick_tag' )) {
  function gym_add_quick_tag() {
    $page_title = get_the_title();
    $gym_table = '<h4></h4>\n' .
                 '<div class="gym-table-wrap">\n<table class="gym-table" style="min-width:320px">\n' .
                 '<tr>\n<th>&nbsp;</th><th>コース</th>\n</tr>\n' .
                 '<tr>\n<td class="gym-table__td-1">コース内容</td>\n<td></td>\n</tr>\n' .
                 '<tr>\n<td class="gym-table__td-1">入会金</td>\n<td></td>\n</tr>\n' .
                 '<tr>\n<td class="gym-table__td-1">コース料金</td>\n<td></td>\n</tr>\n' .
                 '<tr>\n<td class="gym-table__td-1">期間</td>\n<td></td>\n</tr>\n' .
                 '<tr>\n<td class="gym-table__td-1">回数</td>\n<td></td>\n</tr>\n' .
                 '<tr>\n<td class="gym-table__td-1">時間</td>\n<td></td>\n</tr>\n' .
                 '</table>\n</div>';
    $gym_studio_info = '<div class="gym-table-wrap">\n<table class="gym-table">\n<tr>\n<th colspan="2">店</th>\n</tr>\n<tr>\n<td class="gym-table__td-1">住所</td>\n<td></td>\n</tr>\n<tr>\n<td class="gym-table__td-1">最寄駅</td>\n<td></td>\n</tr>\n</table>\n</div>\n';
    if (wp_script_is('quicktags')){?>
      <script>
        QTags.addButton('qt-link','枠で囲んだリンク','<p class="gym-content__link"></p>');
        QTags.addButton('qt-place','アクセス用見出しとテーブル','<h3><?php echo $page_title; ?>のアクセス</h3>\n<dl class="gym-content__desc-list">\n<dt>住所</dt>\n<dd></dd>\n<dt>最寄駅</dt>\n<dd></dd>\n<dt>営業時間</dt>\n<dd></dd>\n</dl>');
        QTags.addButton('qt-table','ジム情報テーブル', '<?php echo $gym_table; ?>');
        QTags.addButton('qt-h-course','コース見出し', '<h3><?php echo $page_title; ?>のコースと料金</h3>');
        QTags.addButton('qt-base','base', '[base slug=""]');
        QTags.addButton('qt-map','map用', '[map]\n\n[/map]');
        QTags.addButton('qt-posttype-gym','ジム情報のみ表示', '[posttype type="gym"]\n\n[/posttype]');
        QTags.addButton('qt-posttype-studio','店舗情報のみ表示', '[posttype type="studio"]\n\n[/posttype]');
        QTags.addButton('qt-posttype-post','投稿ページのみ表示', '[posttype type="post"]\n\n[/posttype]');
        QTags.addButton('qt-feature-prefecture','都道府県と特徴', '[feature feature="" prefecture=""]');
        QTags.addButton('qt-region-prefecture','エリアと特徴', '[feature2 feature="" region=""]');
        QTags.addButton('qt-get-temp','テンプレート呼び出し', '[temp slug=""]');
        QTags.addButton('qt-list-title','タイトル付きのリスト', '<ul class="list-title" title="タイトル">\n<li>アイテム</li>\n</ul>');
        QTags.addButton('qt-list-title-studio','タイトル付きの店舗リスト', '<ul class="list-title list-title--studio" title="タイトル">\n<li>アイテム</li>\n</ul>');
        QTags.addButton('qt-table-studio-info','店舗情報テーブル', '<?php echo $gym_studio_info; ?>');
      </script>
    <?php }
  }
}
add_action( 'admin_print_footer_scripts', 'gym_add_quick_tag' );

/**
 * 記事保存時に自動でカスタムフィールドの値をセットする
 * こちらは「ジム情報」を更新した時の処理
 */
function update_studio_price() {
  global $wpdb, $post;

  $temp_post = $post;

  $slug_name = @$post->post_name;
  $post_id = @$post->ID;

  $price_value = @get_post_meta($post_id, 'price_per', true);
  if( !isset( $price_value ) || !isset( $slug_name ) || !isset( $post_id ) ) return;

  $args = array(
    'post_type'        => 'studio',
    'posts_per_page'   => -1,
    'meta_query' => array(
      array(
        'key'     => 'base_gym',
        'value'   => $slug_name
      ),
    ),
  );
  $my_query = new WP_Query($args);
  $count = 0;

  if ( $my_query->have_posts() ) {
    while ( $my_query->have_posts() ) {
      $my_query->the_post();

      $studio_id = $my_query->posts[$count]->ID;
      update_post_meta( $studio_id, 'price_per', $price_value);

      $count++;
    }
  }

  // 上書きされた$postを元に戻す
  $post = $temp_post;
}
add_action('save_post', 'update_studio_price');

/**
 * 記事保存時に自動でカスタムフィールドの値をセットする
 * こちらは「ジム情報」を更新した時の処理
 */
function update_studio_custom_field() {
  global $wpdb, $post, $post_type;

  if( $post_type !== "gym" ) return;

  $slug_name = @$post->post_name;
  $post_id = @$post->ID;

  $custom_field_array = array();

  $custom_field_array['woman-only']          = @get_post_meta($post_id, 'woman-only', true);
  $custom_field_array['private-room']        = @get_post_meta($post_id, 'private-room', true);
  $custom_field_array['teaching-meals']      = @get_post_meta($post_id, 'teaching-meals', true);
  $custom_field_array['credit-card']         = @get_post_meta($post_id, 'credit-card', true);
  $custom_field_array['installment-payment'] = @get_post_meta($post_id, 'installment-payment', true);
  $custom_field_array['repayment']           = @get_post_meta($post_id, 'repayment', true);
  $custom_field_array['pickup']              = @get_post_meta($post_id, 'pickup', true);

  if( empty( $custom_field_array ) || !isset( $slug_name ) || !isset( $post_id ) ) return;

  $args = array(
    'post_type'        => 'studio',
    'posts_per_page'   => -1,
    'meta_query' => array(
      array(
        'key'     => 'base_gym',
        'value'   => $slug_name
      ),
    ),
  );

  $my_query = new WP_Query($args);
  $count = 0;

  if ( $my_query->have_posts() ) {
    while ( $my_query->have_posts() ) {
      $my_query->the_post();

      $studio_id = $my_query->posts[$count]->ID;

      foreach( $custom_field_array as $key => $value ) {
        $post_meta = get_post_meta( $studio_id, $key, true );
        if( $post_meta !== '0' && $post_meta !== '2' ) 
          if( !empty( $value ) ) {
            update_post_meta( $studio_id, $key, $value);
          } else {
            delete_post_meta( $studio_id, $key, '1' );
          }
      }
      $count++;
    }
  }

  // 上書きされた$postを元に戻す
  wp_reset_postdata();
}
add_action('save_post', 'update_studio_custom_field');

/**
 * 記事保存時に自動でカスタムフィールドの値をセットする
 * こちらは「店舗情報」を保存した時の処理
 */
//　元データから月当たり料金を取得する
function update_studio_price_at_studio() {
  global $wpdb, $post, $post_type;

  if ( $post_type !== 'studio' ) return;

  $base_gym = @get_post_meta( $post->ID, 'base_gym', true );
  if( !isset( $base_gym ) ) return;
  $post_data = get_page_by_path($base_gym, "OBJECT", "gym");
  if( !isset( $post_data ) ) return;
  $post_id = $post_data->ID;
  $price_per = get_post_meta( $post_id, 'price_per', true );
  if($price_per) {
    update_post_meta( $post->ID, 'price_per', $price_per);
  }
  wp_reset_postdata();
}
add_action('save_post', 'update_studio_price_at_studio');

function update_studio_custom_field_at_studio() {
  global $wpdb, $post, $post_type;

  if ( $post_type !== 'studio' ) return;

  $post_id = $post->ID;

  $base_gym = @get_post_meta( $post->ID, 'base_gym', true );
  if( !isset( $base_gym ) ) return;

  $post_data = get_page_by_path($base_gym, "OBJECT", "gym");
  if( !isset( $post_data ) ) return;

  $base_post_id = $post_data->ID;
  $custom_field_array = array();

  $custom_field_array['woman-only']          = @get_post_meta($base_post_id, 'woman-only', true);
  $custom_field_array['private-room']        = @get_post_meta($base_post_id, 'private-room', true);
  $custom_field_array['teaching-meals']      = @get_post_meta($base_post_id, 'teaching-meals', true);
  $custom_field_array['credit-card']         = @get_post_meta($base_post_id, 'credit-card', true);
  $custom_field_array['installment-payment'] = @get_post_meta($base_post_id, 'installment-payment', true);
  $custom_field_array['repayment']           = @get_post_meta($base_post_id, 'repayment', true);
  $custom_field_array['pickup']              = @get_post_meta($base_post_id, 'pickup', true);

  if ( empty( $custom_field_array ) ) return;

  foreach( $custom_field_array as $key => $value ) {
    $custom_field = get_post_meta( $post_id, $key, true );
    if( !empty( $value ) && $custom_field !== '0' ) {
      update_post_meta( $post_id, $key, $value);
    }
  }
  wp_reset_postdata();
}
add_action('save_post', 'update_studio_custom_field_at_studio');

/**
 * アフィリエイトコードを保存するためのカスタムフィールド
 */
// 固定カスタムフィールドボックス
function add_aficode_fields() {
  //add_meta_box(表示される入力ボックスのHTMLのID, ラベル, 表示する内容を作成する関数名, 投稿タイプ, 表示方法)
  add_meta_box( 'aficode', 'アフィリエイトコード', 'insert_aficode_fields', 'gym', 'normal');
  add_meta_box( 'aficode', 'アフィリエイトコード', 'insert_aficode_fields', 'studio', 'normal');
}
add_action('admin_menu', 'add_aficode_fields');

// カスタムフィールドの入力エリア
function insert_aficode_fields() {
    global $post;
 
    //下記に管理画面に表示される入力エリアを作ります。「get_post_meta()」は現在入力されている値を表示するための記述です。
    echo 'アフィコード: <textarea name="aficode" value="'. htmlspecialchars( get_post_meta($post->ID, 'aficode', true) ).'" style="width:100%;height:80px;">'. htmlspecialchars( get_post_meta($post->ID, 'aficode', true) ).'</textarea>';
}
// カスタムフィールドの値を保存
function save_aficode_fields( $post_id ) {
  if(!empty($_POST['aficode'])){ //題名が入力されている場合
    update_post_meta($post_id, 'aficode', $_POST['aficode'] ); //値を保存
  }else{ //題名未入力の場合
    delete_post_meta($post_id, 'aficode'); //値を削除
  }
}
add_action('save_post', 'save_aficode_fields');

/**
 * 投稿画面の右サイドに枠(メタボックス)を追加します。
 */
function add_custom_boxes_side() {
  add_meta_box( 'noindex_studio', 'このジムをnoindex', 'meta_box_noindex_studio', 'gym', 'side');
}
add_action( 'add_meta_boxes', 'add_custom_boxes_side' );

// 広告オンオフ切り替えボックス
function meta_box_noindex_studio() {
  global $post;
  $id = $post->ID;
  $noindex_studio_value = get_post_meta( $id,'noindex_studio', true );
?>
  <label><input type="checkbox" id="noindex_studio" name="noindex_studio" value="noindex" <?php if( $noindex_studio_value === 'noindex' ) echo 'checked'; ?>/>このジムをnoindex</label>
<?php
}
function save_my_custom_data_side($post_id) {
  if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return $post_id;

  $post_meta_value = null;

  $post_keys = array( 'noindex_studio' );
  foreach($post_keys as $post_key){
    if( isset( $_POST[$post_key] ) && $_POST[$post_key] === '' ){
      delete_post_meta( $post_id, $post_key );
    } elseif( !empty( $_POST[$post_key] ) ) {
      $value = $_POST[$post_key];
      update_post_meta( $post_id, $post_key, $value );
    } elseif( ! isset( $_POST[$post_key] ) ) {
      delete_post_meta( $post_id, $post_key );
    } else {
      $value = '';
    }
  } 
}
add_action('save_post', 'save_my_custom_data_side');
