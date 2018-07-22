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
}
add_action( 'widgets_init', 'theme_widget_settings' );