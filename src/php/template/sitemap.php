<?php 

$args = array(
    'post_type'        => 'post',
    'posts_per_page'   => -1,
    'orderby'          => 'post_date',
    'order'            => 'DESC'
  );
  $my_query = new WP_Query($args);
  $count = 0;

if ( $my_query->have_posts() ) :?>
  <h2>投稿記事一覧</h2>
  <ul class="sitemap__list">
  <?php while ( $my_query->have_posts() ) : $my_query->the_post();?>
    <li class="sitemap__item"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
  <?php endwhile;  ?>
  </ul>
<?php endif; ?>

<?php wp_reset_postdata(); // 上書きされた$postを元に戻す ?>


<?php 
$args = array(
    'post_type'        => 'gym',
    'posts_per_page'   => -1,
    'orderby'          => 'post_date',
    'order'            => 'ASC'
  );
  $my_query = new WP_Query($args);
  $count = 0;

if ( $my_query->have_posts() ) : ?>
  <h2>全ジムリンク</h2>
  <ul class="sitemap__list">
  <?php while ( $my_query->have_posts() ) : $my_query->the_post();?>
    <li class="sitemap__item sitemap__item--gym"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
    <?php
      $base_slug = get_post_field( 'post_name', get_the_ID() );
      $args = array(
        'post_type'        => 'studio',
        'posts_per_page'   => -1,
        'orderby'          => 'post_date',
        'order'            => 'DESC',
        'meta_key'         => 'base_gym',
        'meta_value'       => $base_slug,
      );
      $my_query_child = new WP_Query($args);
    ?>
    <?php if ( $my_query_child->have_posts() ) : ?>
      <ul class="sitemap__list-child">
      <?php while ( $my_query_child->have_posts() ) : $my_query_child->the_post();?>
        <li class="sitemap__item-child"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
      <?php endwhile;  ?>
      </ul>
    <?php endif; ?>
    </li>
  <?php endwhile;  ?>
  </ul>
<?php endif; ?>

<?php wp_reset_postdata(); // 上書きされた$postを元に戻す ?>