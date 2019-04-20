<meta name="twitter:card" content="summary">
<?php 
if( @get_the_excerpt() ) {
  $excerpt = preg_replace("/&#?[a-z0-9]{2,8};/i","",strip_tags(@get_the_excerpt()));
  $excerpt = trim($excerpt);
}

if( is_singular() ){
  if( have_posts() ): while( have_posts() ): the_post();
    if( isset( $excerpt ) ) echo '<meta name="twitter:description" content="'.$excerpt.'">'."\n";
  endwhile; endif;
  $title = get_the_title();
  if( is_front_page() ){
    $title = get_bloginfo( 'name' );
  }
  echo '<meta name="twitter:title" content="'; echo $title; echo '">'."\n";
  echo '<meta name="twitter:url" content="'; the_permalink(); echo '">'."\n";
} else {
  $description = !empty( get_option( 'meta_description' ) ) ? get_option( 'meta_description' ) : get_bloginfo('description');
  $title = get_bloginfo( 'name' ) . ' | ' . get_bloginfo( 'description' );
  $url = home_url();

  echo '<meta name="twitter:description" content="' . $description . '">' . "\n";
  echo '<meta name="twitter:title" content="' . $title . '">' . "\n";
  echo '<meta name="twitter:url" content="' . $url . '">' . "\n";
}

if( is_singular() ){
  if( has_post_thumbnail() ){
    $image_id = get_post_thumbnail_id();
    $image = wp_get_attachment_image_src( $image_id, 'full');
    $img_url = $image[0];
    echo '<meta name="twitter:image" content="' . $img_url . '">' . "\n";

  } elseif( has_site_icon() ){
    echo '<meta name="twitter:image" content="'. get_site_icon_url() . '">' . "\n";
  }
} else {
  if( has_site_icon() ){
    echo '<meta name="twitter:image" content="' . get_site_icon_url() . '">' . "\n";
  }
}
?>