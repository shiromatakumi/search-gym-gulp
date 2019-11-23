<?php
/*
Template Name: 検索用のテンプレート
*/
$place_array = get_place_array();

$is_submitarea = isset( $_GET['area'] );

$search_coditions = array();

$page_url = get_the_permalink();

if( isset( $_GET['woman-only']) ){
  $woman_only = $_GET['woman-only'];
  $search_coditions['woman-only'] = $woman_only;
}

if( isset( $_GET['private-room']) ){
  $private_room = $_GET['private-room'];
  $search_coditions['private-room'] = $private_room;
}

if( isset( $_GET['teaching-meals']) ){
  $teaching_meals = $_GET['teaching-meals'];
  $search_coditions['teaching-meals'] = $teaching_meals;
}

if( isset( $_GET['credit-card'] ) ){
  $credit_card = $_GET['credit-card'];
  $search_coditions['credit-card'] = $credit_card;
}

if( isset( $_GET['installment-payment'] ) ){
  $installment_payment = $_GET['installment-payment'];
  $search_coditions['installment-payment'] = $installment_payment;
}

if( isset( $_GET['repayment'] ) ){
  $repayment = $_GET['repayment'];
  $search_coditions['repayment'] = $repayment;
}

if( isset( $_GET['pickup'] ) ){
  $pickup = $_GET['pickup'];
  $search_coditions['pickup'] = $pickup;
}


$is_after_submit = !empty( $search_coditions );

if( $is_submitarea ) {

  // エリアの処理
  $site_url_with_param = mb_convert_encoding( urldecode( $_SERVER['REQUEST_URI'] ), 'UTF-8' );
  $param_all = parse_url( $site_url_with_param );
  $query = '&' . $param_all['query'];
  $param_array = explode( '&', $param_all['query'] );
  $param_area_array = array();

  foreach($param_array as $param) {
    if( preg_match("/^area=/",$param) ) {
      $param_area_array[] = ltrim( $param, 'area=' );
    }
  }

  //配列の中の空要素を削除する
  $param_area_array = array_filter($param_area_array, "strlen");
   
  //添字を振り直す
  $param_area_array = array_values($param_area_array);

  for($i=0; $i<count( $param_area_array ); $i++ ){
    $param_area_array[$i] = urldecode( $param_area_array[$i] );
  }

  $meta_query_array = array();
  $meta_query_area_array = array();
  $meta_query_details_array = array();

  foreach( $param_area_array as $region ) {
    $meta_query_area_array[] = array(
      'key' => 'region',
      'value' => $region,
      'compare'=>'=',
    );
  }
  foreach( $search_coditions as $key => $value ) {
    $meta_query_details_array[] = array(
      'key' => $key,
      'value' => $value,
      'compare'=>'=',
    );
  }
  $meta_query_area_array['relation'] = 'OR';
  $meta_query_details_array['relation'] = 'AND';

  $meta_query_array[] = $meta_query_area_array;
  $meta_query_array[] = $meta_query_details_array;

  $meta_query_array['relation'] = 'AND';
  
  
  $args = array(
    'post_type'      => 'studio',
    'posts_per_page' => -1,
    'orderby'        => 'meta_value_num',
    'order'          => 'ASC',
    'meta_key'       => 'price_per',
    'meta_query'     => $meta_query_array,
  );

  $my_query = new WP_Query($args);

  $count = 0;
  $hit_count = 0;
  
} else {
  $error_msg = '[！] エリアが設定されていません。エリアの指定は必須です';
}

?>
<?php $entry_post_type = get_post_type(); ?>
<?php get_header(); ?>
  <div class="wrapper">
    <div class="wrapper-inner wrapper-inner--page">
      <main id="main" class="main">
        <div class="main-inner">
          <?php if( $is_submitarea ): // 検索結果 ?>
          <div class="selected-options" id="selected-options">
            <form action="<?php echo $page_url; ?>" method="get" accept-charset="utf-8">
              <div class="select-details">
                <p>選択中のエリア：
                <?php foreach($param_area_array as $area): ?>
                <span class="selected-area"><?php echo $area; ?></span>
                <input type="hidden" name="area" value="<?php echo urlencode($area); ?>">
                <?php endforeach; ?>
                </p>
                <p><a href="<?php echo $page_url; ?>">エリアを変更する</a></p>
                <h3></h3>
                <label class="select-details__label"><input type="checkbox" name="woman-only" value="1" class="select-details__checkbox" <?php if( isset( $woman_only ) ) echo 'checked'; ?>>女性限定</label>
                <label class="select-details__label"><input type="checkbox" name="private-room" value="1" class="select-details__checkbox" <?php if( isset( $private_room ) ) echo 'checked'; ?>>完全個室</label>
                <label class="select-details__label"><input type="checkbox" name="teaching-meals" value="1" class="select-details__checkbox" <?php if( isset( $teaching_meals ) ) echo 'checked'; ?>>食事指導あり</label>
                <label class="select-details__label"><input type="checkbox" name="credit-card" value="1" class="select-details__checkbox" <?php if( isset( $credit_card ) ) echo 'checked'; ?>>クレジット払い可</label>
                <label class="select-details__label"><input type="checkbox" name="installment-payment" value="1" class="select-details__checkbox" <?php if( isset( $installment_payment ) ) echo 'checked'; ?>>分割払い可</label>
                <label class="select-details__label"><input type="checkbox" name="repayment" value="1" class="select-details__checkbox" <?php if( isset( $repayment ) ) echo 'checked'; ?>>返金制度あり</label>
                <label class="select-details__label"><input type="checkbox" name="pickup" value="1" class="select-details__checkbox" <?php if( isset( $pickup ) ) echo 'checked'; ?>>おすすめ</label>
              </div>
              <input type="submit" name="" value="再検索" class="search-details__submit">
            </form>
            
          </div>
          
          <div class="search-detail entry-content">
            <?php 
            if ( $my_query->have_posts() ): ?>
            <h1 class="entry-title">検索結果（1回あたりの金額が安い順）</h1>
            <p class="hit-num"><?php echo $my_query->post_count; ?>件ヒットしました。</p>
              <?php while ( $my_query->have_posts() ):
              $my_query->the_post();

              $post_id = $my_query->posts[$count]->ID;

              // 店舗からジムのIDを取得する
              $meta_values = get_post_meta($post_id, 'base_gym', true);
              $post_base_obj = get_page_by_path( $meta_values, OBJECT, 'gym' );
              $post_base_id = $post_base_obj->ID;

              /**
               * ここから
               */

              $post_thumbnail_url = get_the_post_thumbnail_url( $post_id, 'full' );
              $content_text = apply_filters('the_content',$my_query->posts[$count]->post_content);
              $title = $my_query->posts[$count]->post_title;
              // ベースとなるジムのアフィコードを取得
              $meta_values = get_post_meta($post_id, 'base_gym', true);
              $post_base_obj = get_page_by_path( $meta_values, OBJECT, 'gym' );
              $post_base_id = $post_base_obj->ID;
              $aficode = get_post_meta($post_base_id, 'aficode', true);
            ?>
            <div class="gym-content">
              <h2 class="gym-content__title"><?php echo $title; ?></h2>
              <div class="gym-content__thumb"><img src="<?php echo $post_thumbnail_url ?>" alt="<?php echo $title; ?>"></div>
              <?php echo $content_text; ?>
              <?php if( $aficode ) echo '<p class="gym-content__btn">' . $aficode . '</p>'; ?>
            </div>
            <?php $count++; endwhile; else: ?>
            <h1 class="gym-content__title">ジムがヒットしませんでした</h1>
            <p>検索した条件のジムが見つかりませんでした。</p>
            <p>条件を変更して、再度検索してください。</p>
            <?php endif; ?>
          </div>
          <?php else: ?>
          <h1 class="entry-title">詳細検索ページ</h1>
          <?php if( $is_after_submit ): ?>
          <p class="error-msg"><?php echo $error_msg; ?></p>
          <?php else: ?>
            <p class="error-msg">※エリアの指定は必須です</p>
          <?php endif; ?>
          <div class="search-detail">
            <form action="<?php echo $page_url; ?>" method="get" accept-charset="utf-8">
              <div class="select-details">
                <h2 class="select-details__title">詳細条件を選択</h2>
                <label class="select-details__label"><input type="checkbox" name="woman-only" value="1" class="select-details__checkbox" <?php if( isset( $woman_only ) ) echo 'checked'; ?>>女性限定</label>
                <label class="select-details__label"><input type="checkbox" name="private-room" value="1" class="select-details__checkbox" <?php if( isset( $private_room ) ) echo 'checked'; ?>>完全個室</label>
                <label class="select-details__label"><input type="checkbox" name="teaching-meals" value="1" class="select-details__checkbox" <?php if( isset( $teaching_meals ) ) echo 'checked'; ?>>食事指導あり</label>
                <label class="select-details__label"><input type="checkbox" name="credit-card" value="1" class="select-details__checkbox" <?php if( isset( $credit_card ) ) echo 'checked'; ?>>クレジット払い可</label>
                <label class="select-details__label"><input type="checkbox" name="installment-payment" value="1" class="select-details__checkbox" <?php if( isset( $installment_payment ) ) echo 'checked'; ?>>分割払い可</label>
                <label class="select-details__label"><input type="checkbox" name="repayment" value="1" class="select-details__checkbox" <?php if( isset( $repayment ) ) echo 'checked'; ?>>返金制度あり</label>
                <label class="select-details__label"><input type="checkbox" name="pickup" value="1" class="select-details__checkbox" <?php if( isset( $pickup ) ) echo 'checked'; ?>>おすすめ</label>
              </div>
              <div class="select-details select-details--area">
                <h2 class="select-details__title">エリアを選択</h2>
                <?php foreach( $place_array as $key => $value_array ): ?>
                <h3 class="area__title"><?php echo $key; ?></h3>
                  <?php foreach( $value_array as $area => $place_array): ?>
                  <h4><?php echo $area; ?></h4>
                  <div class="select-area__inputs">
                    <?php foreach($place_array as $place): ?>
                    <label class="select-details__label select-details__label--area"><input type="checkbox" name="area" value="<?php echo urlencode( $place ); //文字化けを防止 ?>" class="select-details__checkbox select-details__checkbox--area"><?php echo $place; ?></label>
                    <?php endforeach; ?>
                  </div>
                  <?php endforeach; ?>
                <?php endforeach; ?>
              </div>
              <div class="search-details__submit-wrap">
                <input type="submit" name="" value="検索" class="search-details__submit">
              </div>
            </form>
          </div>
          <?php endif; ?>
        </div>
      </main>
      <?php get_sidebar(); ?>
    </div>
  </div>
  <?php if( $is_submitarea ): // 検索結果 ?>
  <div class="re-search">
    <a href="#selected-options">検索条件を変更する</a>
  </div>
  <?php endif; ?>
<?php get_footer(); ?>