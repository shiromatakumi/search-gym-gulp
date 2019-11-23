<?php 
$blog_name = get_bloginfo('name');
$blog_description = !empty( get_option( 'meta_description' ) ) ? get_option( 'meta_description' ) : get_bloginfo( 'description' );
$post_id = get_the_ID();
?>
<?php // メタディスクリプションの設定 ?>
<?php if ( is_home() && !is_paged() ): //トップページ?>
<meta name="description" content="<?php echo $blog_description; ?>">
<?php elseif( is_singular() ): //投稿・固定ページ?>
  <?php if( have_posts() ): while( have_posts() ): the_post(); ?>
    <?php if ( has_excerpt() ): ?>
      <?php $excerpt = preg_replace( "/&#?[a-z0-9]{2,8};/i", "", strip_tags( get_the_excerpt() ) );
        $excerpt = trim($excerpt); ?>
      <?php if( $excerpt ): ?>
<meta name="description" content="<?php echo $excerpt; ?>">
      <?php endif; ?>
    <?php else: ?>
<meta name="description" content="<?php echo get_excerpt_after_shortcode(); // functions.php ?>">
    <?php endif; ?>
  <?php endwhile; endif; ?>
<?php elseif( is_category() ): //カテゴリー?>
<meta name="description" content="<?php echo '「' . single_cat_title('', false) . '」カテゴリーの記事一覧ページ'; ?>">
<?php elseif( is_tag() ): //タグ?>
<meta name="description" content="<?php echo '「' . single_tag_title('', false) . '」タグの記事一覧ページ'; ?>">
<?php else: ?>
<meta name="description" content="<?php echo $blog_description; ?>">
<?php endif; ?>
<?php $post_type = get_post_type($post_id); ?>
<?php if($post_type === 'template' || $post_type === 'gym' || $post_type === 'todofuken' ):  ?>
<meta name="robots" content="noindex,nofollow">
<?php endif; ?>
<?php 

if( $post_type === "studio" ) {
  $base_slug = get_post_meta( $post_id, "base_gym", true );

  if( !empty( $base_slug ) ) {
    $base_post_object = get_page_by_path( $base_slug, "OBJECT", "gym" );
    $base_id = $base_post_object->ID;

    $gym_noindex = get_post_meta( $base_id, "noindex_studio", true );

    if ( $gym_noindex === 'noindex' ) {
?>
<meta name="robots" content="noindex,nofollow">
<?php
    }
  }
}

?>