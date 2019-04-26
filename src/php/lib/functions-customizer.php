<?php
/**
 * 外観→カスタマイズの設定
 */
function theme_customizer_extension($wp_customize) {
  //セクション
  $wp_customize->add_section( 'link-manager', array (
   'title' => 'リンクマネージャー',
   'priority' => 100,
  ));
    //テーマ設定
    $wp_customize->add_setting( 'diet-concierge', array (
      'type' => 'option'
    ));
     //コントロールの追加
    $wp_customize->add_control( 'diet-concierge', array(
      'section' => 'link-manager',
      'settings' => 'diet-concierge',
      'label' =>'ダイエットコンシェルジュ用コード',
      'description' => 'キャッシュバック対象で、アフィリエイト非対応のジムの上に表示させるコード',
      'type' => 'textarea',
      'priority' => 20,
    ));

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

  //セクション
  $wp_customize->add_section( 'settings_seo', array (
   'title' => 'SEOの設定',
   'priority' => 100,
  ));
    //テーマ設定
    $wp_customize->add_setting( 'meta_description', array (
      'type' => 'option'
    ));
     //コントロールの追加
    $wp_customize->add_control( 'meta_description', array(
      'section' => 'settings_seo',
      'settings' => 'meta_description',
      'label' =>'トップページのmeta description',
      'description' => 'トップページのmeta descriptionを設定してください。',
      'type' => 'textarea',
      'priority' => 30,
    ));
}
add_action('customize_register', 'theme_customizer_extension');