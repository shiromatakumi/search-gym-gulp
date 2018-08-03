<?php 
/**
 * JR線（東京）
 */
$jr_yamanote = array(
	'渋谷', '恵比寿', '目黒', '五反田', '大崎', '品川', '田町', '浜松町', '新橋',
	'有楽町', '東京', '神田', '秋葉原', '御徒町', '上野', '鶯谷', '日暮里', '西日暮里',
	'田端', '駒込', '巣鴨', '大塚', '池袋', '目白', '高田馬場', '新大久保', '新宿',
	'代々木', '原宿'
);
$jr_chuou = array(
	'東京','神田','御茶ノ水','水道橋','飯田橋','市ケ谷','四ツ谷','信濃町','千駄ケ谷','代々木','新宿',
	'大久保','東中野','中野','高円寺','阿佐ケ谷','荻窪','西荻窪','吉祥寺','三鷹','武蔵境','東小金井',
	'武蔵小金井','国分寺','西国分寺','国立','立川','日野','豊田','八王子','西八王子','高尾'
);
$jr_soubu = array(
	'千葉', '西千葉', '稲毛', '新検見川', '幕張', '幕張本郷', '津田沼', '東船橋','船橋','西船橋',
	'下総中山','本八幡','市川','小岩','新小岩','平井','亀戸','錦糸町','両国','浅草橋','秋葉原',
	'御茶ノ水','水道橋','飯田橋','市ケ谷','四ツ谷','信濃町','千駄ケ谷','代々木','新宿',
	'大久保','東中野','中野','高円寺','阿佐ケ谷','荻窪','西荻窪','吉祥寺','三鷹'
);
$jr_joban = array(
	'上野','日暮里','三河島','南千住','北千住','綾瀬','亀有','金町','松戸','北松戸','馬橋',
	'新松戸','北小金','南柏','柏'
);
$jr_saikyo = array(
	'大崎','恵比寿','渋谷','新宿','池袋','板橋','十条','赤羽','北赤羽','浮間舟渡','戸田公園',
	'戸田','北戸田','武蔵浦和','中浦和','南与野','与野本町','北与野','大宮','日進'
);
$jr_keihintouhoku = array(
	'秋葉原', '御徒町', '上野', '鶯谷', '日暮里', '西日暮里','田端','上中里','王子','東十条',
	'赤羽','川口','西川口','蕨','南浦和','浦和','北浦和','与野','さいたま新都心','大宮'
);
/**
 * 東京メトロ
 */
$metro_ginza = array(
	'浅草', '田原町', '稲荷町', '上野', '上野広小路', '末広町', '神田', '三越前', '日本橋',
	'京橋', '銀座', '新橋', '虎ノ門', '溜池山王', '赤坂見附', '青山一丁目', '外苑前', '表参道', '渋谷'
);
$metro_marunouti = array(
	'池袋', '新大塚', '茗荷谷', '後楽園', '本郷三丁目', '御茶ノ水', '淡路町', '大手町', '東京', '銀座',
	'霞ヶ関', '国会議事堂前', '赤坂見附', '四ツ谷', '四谷三丁目', '新宿御苑前', '新宿三丁目', '新宿',
	'西新宿', '中野坂上', '新中野', '東高円寺', '新高円寺', '南阿佐ヶ谷', '荻窪'
);
$metro_hibiya = array(
	'中目黒', '恵比寿', '広尾', '六本木', '神谷町', '霞ケ関', '日比谷', '銀座', '東銀座', '築地',
	'八丁堀', '茅場町', '人形町', '小伝馬町', '秋葉原', '仲御徒町', '上野', '入谷',
	'三ノ輪', '南千住', '北千住'
);
$metro_tozai = array(
	'中野', '落合', '高田馬場', '早稲田', '神楽坂', '飯田橋', '九段下', '竹橋', '大手町', '日本橋',
	'茅場町', '門前仲町', '木場', '東陽町', '南砂町', '西葛西', '葛西', '浦安', '南行徳' , '行徳',
	'妙典', '原木中山', '西船橋'
);
$metro_chiyoda = array(
	'代々木上原', '代々木公園', '原宿', '表参道', '乃木坂', '赤坂', '国会議事堂前', '霞ケ関', '日比谷', '二重橋前',
	'大手町', '新御茶ノ水', '湯島', '根津', '千駄木', '西日暮里', '町屋', '北千住', '綾瀬' , '北綾瀬'
);


$lines_array = array(
	'JR山手線' => $jr_yamanote,
	'JR中央線' => $jr_chuou,
	'JR総武線' => $jr_soubu,
	'JR常磐線' => $jr_joban,
	'JR埼京線' => $jr_saikyo,
	'JR京浜東北線' => $jr_keihintouhoku,
	'東京メトロ銀座線' => $metro_ginza,
	'東京メトロ丸ノ内線' => $metro_marunouti,
	'東京メトロ日比谷線' => $metro_hibiya,
	'東京メトロ東西線' => $metro_tozai,
	'東京メトロ千代田線' => $metro_chiyoda,
);

/* ここの続きは後で
$lines_array = array(
	'jr_co' => array(
		'JR山手線' => $jr_yamanote,
		'JR中央線' => $jr_chuou,
		'JR総武線' => $jr_soubu,
		'JR常磐線' => $jr_joban,
		'JR埼京線' => $jr_saikyo,
		'JR京浜東北線' => $jr_keihintouhoku,
	),
	'metoro_co' => array(
		'東京メトロ銀座線' => $metro_ginza,
		'東京メトロ丸ノ内線' => $metro_marunouti,
		'東京メトロ日比谷線' => $metro_hibiya,
		'東京メトロ東西線' => $metro_tozai,
		'東京メトロ千代田線' => $metro_chiyoda,
	)
);

$railway_co = array(
	'jr_co'				=> 'JR',
	'metoro_co'		=> '東京メトロ',
);
*/

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
echo $content;

 ?>

