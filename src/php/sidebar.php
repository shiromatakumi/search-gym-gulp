<div class="sidebar">
  <?php if ( is_active_sidebar( 'sidebar-top' ) ) : ?>
    <?php dynamic_sidebar( 'sidebar-top' ); ?>
  <?php endif; ?>
  <div class="widget widget--recommend widget--sidebar">
    <h3 class="widget__title widget__title--sidebar">おすすめジム</h3>
    <div class="recommend-gym-list">
      <?php  echo get_recommend_gym(6); ?>
    </div>
  </div>
  <?php if ( is_active_sidebar( 'sidebar-bottom' ) ) : ?>
    <?php dynamic_sidebar( 'sidebar-bottom' ); ?>
  <?php endif; ?>
</div>