<?php 

/**
 * サイドバーの登録
 */
function theme_widget_settings() {
    register_sidebar( array(
        'name' => 'サイドバー上部',
        'id' => 'sidebar-top',
        'description' => 'サイドバーの一番上に表示されます',
        'before_widget' => '<div id="%1$s" class="widget widget--sidebar %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget__title widget__title--sidebar">',
        'after_title'   => '</h3>',
    ) );
    register_sidebar( array(
        'name' => 'サイドバーおすすめ下',
        'id' => 'sidebar-bottom',
        'description' => 'サイドバーのおすすめジムの下に表示されます',
        'before_widget' => '<div id="%1$s" class="widget widget--sidebar %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget__title widget__title--sidebar">',
        'after_title'   => '</h3>',
    ) );
    register_sidebar( array(
        'name' => 'トップページ上部（PC)',
        'id' => 'toppage-upper',
        'description' => 'トップページ上部のウィジェット',
        'before_widget' => '<div id="%1$s" class="widget widget--toppage-upper %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget__title widget__title--toppage-upper">',
        'after_title'   => '</h3>',
    ) );
    register_sidebar( array(
        'name' => 'トップページ上部（SP）',
        'id' => 'toppage-upper-sp',
        'description' => 'トップページ上部のウィジェット',
        'before_widget' => '<div id="%1$s" class="widget widget--toppage-upper %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget__title widget__title--toppage-upper">',
        'after_title'   => '</h3>',
    ) );
    register_sidebar( array(
        'name' => '記事上広告',
        'id' => 'entry-top-ad',
        'description' => '記事上に広告を表示させる',
        'before_widget' => '<div id="%1$s" class="widget widget--entry-top-ad %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget__title widget__title--entry-top-ad">',
        'after_title'   => '</h3>',
    ) );
    register_sidebar( array(
        'name' => '記事下広告',
        'id' => 'entry-bottom-ad',
        'description' => '記事下に広告を表示させる',
        'before_widget' => '<div id="%1$s" class="widget widget--entry-bottom-ad %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget__title widget__title--entry-bottom-ad">',
        'after_title'   => '</h3>',
    ) );
}
add_action( 'widgets_init', 'theme_widget_settings' );