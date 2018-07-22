<?php
$post_cat = get_the_category();
$catid = $post_cat[0]->cat_ID; //親カテゴリーの取得
$args = array(
  'child_of' => $catid
);
$child_cat = get_categories($args); //親カテゴリーの子を全部取得
$all_cats = $all_children = array();
$content_num = 3;
foreach($post_cat as $key) {
  array_push($all_cats, $key->cat_ID);
}
foreach($child_cat as $key){
  array_push($all_children, $key->cat_ID);
}
?>
<!-- パンくずリスト -->
<div class="breadcrumb">
  <ol class="breadcrumb__list" itemscope itemtype="http://schema.org/BreadcrumbList">
    <li itemprop="itemListElement" itemscope
      itemtype="http://schema.org/ListItem"><a href="<?php echo home_url(); ?>" itemprop="item"><span itemprop="name">HOME</span></a><meta itemprop="position" content="1" /></li>
    <?php if($catid): ?>
    <li itemprop="itemListElement" itemscope
      itemtype="http://schema.org/ListItem"><a href="<?php echo get_category_link( $catid ); ?>" itemprop="item"><span itemprop="name"><?php echo esc_html( get_cat_name( $catid ) ); ?></span></a><meta itemprop="position" content="2" /></li>
    <?php endif; ?>
    <?php foreach($all_children as $id): if(array_search($id, $all_cats)): ?>
     <li itemprop="itemListElement" itemscope
      itemtype="http://schema.org/ListItem"><a href="<?php echo get_category_link( $id ); ?>" itemprop="item"><span itemprop="name"><?php echo esc_html( get_cat_name( $id ) ); ?></span></a> <meta itemprop="position" content="<?php echo $content_num; ?>" /></li>
    <?php $content_num++; ?>
    <?php endif; endforeach; ?>
  </ol>
</div>
<!-- パンくずリスト -->