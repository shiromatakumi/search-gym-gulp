<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<title><?php wp_title( '|', true, 'right' ); ?><?php bloginfo( 'name' ); ?></title>
<style>@@include('temp/css/style.min.css')</style>
<?php wp_head(); ?>
<?php if ( $searchconsole_tag = get_option( 'search-console' ) ): ?>
<meta name="google-site-verification" content="<?php echo $searchconsole_tag; ?>" />
<?php endif; ?>
<?php if ( $analytics_tag = get_option( 'analytics' ) ): ?>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $analytics_tag; ?>"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', '<?php echo $analytics_tag; ?>');
</script>
<?php endif ?>
</head>
<body <?php body_class(); ?>>
  <div class="container">
    <header class="header" id="header" >
      <div class="header-inner">
        <div class="site-title">
          <?php if( is_home() || is_front_page() ): ?>
          <h1 class="site-title__text"><a href="<?php echo home_url(); ?>"><?php bloginfo( 'name' ); ?></a></h1>
          <?php else: ?>
          <p class="site-title__text"><a href="<?php echo home_url(); ?>"><?php bloginfo( 'name' ); ?></a></p>
          <?php endif; ?>
        </div>
        <div class="global-nav-wrap">
          <?php 
            wp_nav_menu( array(
              'theme_location' => 'global',
              'menu_class'      => 'global-nav__list',
              'container'      => 'nav',
              'container_class'=> 'global-nav',
              'depth'          => 1,
            ) );
           ?>
        </div>
      </div>
    </header><!-- /header -->