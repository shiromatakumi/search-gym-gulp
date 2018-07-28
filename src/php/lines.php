<?php 

$yamanote = array(
	'渋谷', '恵比寿', '目黒', '五反田', '大崎', '品川', '田町', '浜松町', '新橋',
	'有楽町', '東京', '神田', '秋葉原', '御徒町', '上野', '鶯谷', '日暮里', '西日暮里',
	'田端', '駒込', '巣鴨', '大塚', '池袋', '目白', '高田馬場', '新大久保', '新宿',
	'代々木', '原宿'
);
$metro_ginza = array(
	'浅草', '田原町', '稲荷町', '上野', '上野広小路', '末広町', '神田', '三越前', '日本橋',
	'京橋', '銀座', '新橋', '虎ノ門', '溜池山王', '赤坂見附', '青山一丁目', '外苑前', '表参道', '渋谷'
);
$metro_marunouti = array(
	'池袋', '新大塚', '茗荷谷', '後楽園', '本郷三丁目', '御茶ノ水', '淡路町', '大手町', '東京', '銀座',
	'霞ヶ関', '国会議事堂前', '赤坂見附', '四ツ谷', '四谷三丁目', '新宿御苑前', '新宿三丁目', '新宿',
	'西新宿', '中野坂上', '新中野', '東高円寺', '新高円寺', '南阿佐ヶ谷', '荻窪'
);

$lines_array = array(
	'JR山手線' => $yamanote,
	'東京メトロ銀座線' => $metro_ginza,
	'東京メトロ丸ノ内線' => $metro_marunouti,
);

/**
 * the_post()でグローバル変数$postが上書きされてしまうので、
 * 一旦変数に格納して代入し直す
 */
global $post;
$temp_post = $post;

$content = '';

foreach( $lines_array as $line => $stations ) {

	if( empty($line) || empty($stations) ) return;

	$content .= '<div class="search-line"><h3 class="search-line__title">' . $line . '</h3>';
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
$post = $temp_post;
echo $content;

 ?>

