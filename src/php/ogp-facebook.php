<meta property="og:type" content="<?php echo (is_singular() ? 'article' : 'website'); ?>">
<?php $excerpt = preg_replace("/&#?[a-z0-9]{2,8};/i","",strip_tags(get_the_excerpt()));
      $excerpt = trim($excerpt); ?>
<?php 
if(is_singular()){
  if(have_posts()): while(have_posts()): the_post();
    echo '<meta property="og:description" content="'.$excerpt.'">'."\n";
  endwhile; endif;
  $title = get_the_title();
  if(is_front_page()){
    $title = get_bloginfo('name');
  }
  echo '<meta property="og:title" content="'; echo $title; echo '">'."\n";
  echo '<meta property="og:url" content="'; the_permalink(); echo '">'."\n";
} else {
  $description = get_bloginfo('description');
  $title = get_bloginfo('name');
  $url = home_url();

  echo '<meta property="og:description" content="'.$description.'">'."\n";
  echo '<meta property="og:title" content="'; echo $title; echo '">'."\n";
  echo '<meta property="og:url" content="'; echo $url; echo '">'."\n";
}

if(is_singular()){
  if(has_post_thumbnail()){
    $image_id = get_post_thumbnail_id();
    $image = wp_get_attachment_image_src( $image_id, 'full');
    echo '<meta property="og:image" content="'.$image[0].'">'."\n";
  } elseif(has_site_icon()){
    echo '<meta property="og:image" content="'.get_site_icon_url().'">'."\n";
  }
} else {
  if(has_site_icon()){
    echo '<meta property="og:image" content="'.get_site_icon_url().'">'."\n";
  }
}
?>
<meta property="og:site_name" content="<?php bloginfo('name'); ?>">
<meta property="og:locale" content="ja_JP">