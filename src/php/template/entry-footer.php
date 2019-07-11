<footer class="entry-footer">
  <?php get_template_part( 'data/todofuken_list' ); ?>
  <?php get_gym_region(); ?>
  
  <div class="share-area share-area__buttom">
    <?php get_template_part( 'template/share-buttons' ); ?>
  </div>
  <?php 
    $post_id = get_the_ID();
    $post_type = get_post_type( $post_id );

    if( $post_type === 'studio' ) {
      $meta_values = get_post_meta($post_id, 'base_gym', true);
      $post_base_obj = get_page_by_path( $meta_values, OBJECT, 'gym' );
      $post_base_id = $post_base_obj->ID;
      $display_ad = get_post_meta( $post_base_id, 'display_ad', true );
    } else {
      $display_ad = get_post_meta( $post_id, 'display_ad', true );
    }
   ?>
    <?php if ( is_active_sidebar( 'entry-bottom-ad' ) /*&& !empty( $display_ad )*/ ) : ?>
      <?php dynamic_sidebar( 'entry-bottom-ad' ); ?>
    <?php endif; ?>
  <?php 
    if( $post_type === 'studio' ): ?>
      <?php get_template_part( 'template/entry-footer-studio' ); ?>
    <?php endif; ?>
  <div class="widget widget--recommend widget--sidebar">
    <h3 class="widget__title widget__title--sidebar">おすすめジム</h3>
    <div class="recommend-gym-list recommend-gym-list--footer">
      <?php echo get_recommend_gym(6, 'footer'); ?>
    </div>
  </div>
</footer>