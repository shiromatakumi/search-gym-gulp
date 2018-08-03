<?php 
$blog_name = get_bloginfo('name');
$blog_description = get_bloginfo('description');
$post_id = get_the_ID();
?>
<?php // メタディスクリプションの設定 ?>
<?php if (is_home() && !is_paged()): //トップページ?>
<meta name="description" content="<?php echo $blog_description; ?>">
<?php elseif(is_singular()): //投稿・固定ページ?>
  <?php if(have_posts()): while(have_posts()): the_post(); ?>
    <?php $excerpt = preg_replace( "/&#?[a-z0-9]{2,8};/i", "", strip_tags( get_the_excerpt() ) );
      $excerpt = trim($excerpt); ?>
    <?php if( $excerpt ): ?>
<meta name="description" content="<?php echo $excerpt; ?>">
    <?php endif; ?>
  <?php endwhile; endif; ?>
<?php elseif( is_category() ): //カテゴリー?>
<meta name="description" content="<?php echo '「' . single_cat_title('', false) . '」カテゴリーの記事一覧ページ'; ?>">
<?php elseif( is_tag() ): //タグ?>
<meta name="description" content="<?php echo '「' . single_tag_title('', false) . '」タグの記事一覧ページ'; ?>">
<?php else: ?>
<meta name="description" content="<?php echo $blog_description; ?>">
<?php endif; ?>