<?php

function get_place_array(){

  $place_array_tokyo = array(

    '東京都23区都心部' => array(
      '渋谷', '恵比寿', '表参道', '青山', '原宿', '代官山', '新宿', '高田馬場', '赤坂', '神田', '銀座', '新橋', '六本木', '西麻布', '有楽町', '麻布十番','神楽坂', '代々木上原','代々木','飯田橋', '笹塚', '日本橋', '秋葉原', '田町',
    ),
    '東京都23区南エリア' => array(
      '目黒', '中目黒', '学芸大学', '下北沢', '三軒茶屋', '経堂', '自由が丘', '二子玉川', '五反田', '品川', '大森', '蒲田',
    ),
    '東京都23区西エリア' => array(
      '中野', '練馬駅',
    ),
    '東京都23区東エリア' => array(
      '豊洲', '葛西・西葛西', '葛飾'
    ),
    '東京都23区北エリア' => array(
      '池袋', '赤羽',
    ),
    '東京都23区外' => array(
      '吉祥寺', '立川', '聖蹟桜ヶ丘', '八王子', '町田',
    ),
  );
  
  $place_array_kanto = array(
    
    '神奈川県' => array(
      '横浜', '関内', '川崎','武蔵小杉', '藤沢', '本厚木',
    ),
    '埼玉県' => array(
      '大宮', '南越谷', '所沢', '浦和',
    ),
    '千葉県' => array(
      '千葉駅', '柏', '松戸'
    ),
    '群馬県' => array(
      '高崎', 
    ),
    '栃木県' => array(
      '宇都宮',
    ),
    '茨城県' => array(
      '水戸', 'つくば',
    ),
  );

  $place_array_kansai = array(
    '大阪府' => array(
      '梅田', '心斎橋', '難波', '本町', '堀江', '天王寺', '京橋', '江坂', '豊中', '堺市', '堺東', '高槻', '枚方',
    ),
    '京都府' => array(
      '河原町',
    ),
    '兵庫県' => array(
      '三宮', '西宮',
    ),
    '和歌山県' => array(
      '和歌山市',
    ),
  );

  $place_array_hokkaido = array(
    '北海道' => array(
      '札幌',
    )
  );

  $place_array_tohoku = array(
    '青森県' => array(
      '青森駅', 
    ),
    '岩手県' => array(
      '盛岡', 
    ),
    '宮城県' => array(
      '仙台', 
    ),
    '秋田県' => array(
      '秋田駅', 
    ),
    '福島県' => array(
      '郡山', 
    ),
  );

  $place_array_hokuriku = array(
    '新潟県' => array(
      '新潟市', 
    ),
    '石川県' => array(
      '金沢', 
    ),
    '福井県' => array(
      '福井市', 
    ),
  );

  $place_array_koshinetsu = array(
    '山梨県' => array(
      '甲府', 
    ),
    '長野県' => array(
      '長野市', 
    ),
  );

  $place_array_tokai = array(
    '愛知県' => array(
      '名古屋駅', '栄', '千種', '金山',
    ),
    '静岡県' => array(
      '浜松', '静岡駅',
    ),
    '三重県' => array(
      '四日市', 
    ),
    '岐阜県' => array(
      '岐阜駅', 
    ),
  );

  $place_array_chugoku = array(
    '岡山県' => array(
      '岡山市', 
    ),
    '広島県' => array(
      '八丁堀', 
    ),
  );

  $place_array_shikoku = array(
    '愛媛県' => array(
      '松山', 
    ),
    '徳島県' => array(
      '徳島市', 
    ),
    '香川県' => array(
      '高松', 
    ),
    '高知県' => array(
      '高知市', 
    ),
  );

  $place_array_kyusyu = array(
    '福岡県' => array(
      '博多駅', '天神', '小倉', '久留米', 
    ),
    '長崎県' => array(
      '長崎市', '佐世保',
    ),
    '大分県' => array(
      '大分市', 
    ),
    '佐賀県' => array(
      '佐賀市', 
    ),
    '熊本県' => array(
      '熊本市', 
    ),
    '宮崎県' => array(
      '宮崎市', 
    ),
    '鹿児島県' => array(
      '鹿児島市', 
    ),
    '沖縄県' => array(
      '那覇市', 
    ),
  );

  $place_array = array(
    '東京都内'   => $place_array_tokyo,
    '関東'      => $place_array_kanto,
    '関西'      => $place_array_kansai,
    '北海道'    => $place_array_hokkaido,
    '東北'      => $place_array_tohoku,
    '北陸'      => $place_array_hokuriku,
    '甲信越'     => $place_array_koshinetsu,
    '東海'      => $place_array_tokai,
    '中国'      => $place_array_chugoku,
    '四国'      => $place_array_shikoku,
    '九州・沖縄' => $place_array_kyusyu,
  );

  return $place_array;
}