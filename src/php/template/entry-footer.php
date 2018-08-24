<footer class="entry-footer">
  <?php get_template_part( 'data/todofuken_list' ); ?>
  <?php get_gym_region(); ?>
  
  <div class="share-area share-area__buttom">
    <?php get_template_part( 'template/share-buttons' ); ?>
  </div>
  <?php 
    $post_id = get_the_ID();
    $post_type = get_post_type( $post_id );

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