<footer class="entry-footer">
  <?php get_gym_region(); ?>
  
  <div class="share-buttom">
    <?php 
      $url = esc_url( get_the_permalink() );
      $title = get_the_title();
     ?>
    <!-- <li class="tw sns-btn__item">
      <a href="http://twitter.com/share?url=<?php echo $url; ?>&text=<?php echo $title;?>" target="_blank" rel="nofollow">
          <i class="fa fa-twitter"></i>
          <span class="share_txt">ツイート</span>
      </a>
    </li>
    facebook
    <li class="fb sns-btn__item">
        <a href="http://www.facebook.com/share.php?u=<?php echo $url; ?>&t=<?php echo $title; ?>" target="_blank" rel="nofollow">
            <i class="fa fa-facebook"></i>
            <span class="share_txt">シェア</span>
        </a>
    </li>
    はてなブックマーク
    <li class="hatebu sns-btn__item">
      <a href="http://b.hatena.ne.jp/entry/<?php echo $url; ?>" target="_blank" rel="nofollow">
            <span class="batebu-icon">B!</span>
            <span class="share_txt">はてブ</span>
        </a>
    </li> -->
  </div>
  <?php 
    $post_id = get_the_ID();
    $post_type = get_post_type( $post_id );

    if( $post_type === 'studio' ): ?>
      <?php get_template_part( 'template/entry-footer-studio' ); ?>
    <?php endif; ?>
  <div class="widget widget--recommend widget--sidebar">
    <h3 class="widget__title widget__title--sidebar">おすすめジム</h3>
    <div class="recommend-gym-list">
      <?php echo get_recommend_gym(6); ?>
    </div>
  </div>
</footer>