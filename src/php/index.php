<?php get_header(); ?>
    <div class="wrapper">
      <?php if( is_home() || is_front_page() ): ?>
      <?php if ( $paged < 2 ) : ?>
      <div class="home-top">
        <div class="home-top-inner" style="background-image:url(<?php echo get_template_directory_uri(); ?>/image/main-visual.jpg);">
          <div class="home-top-box">
            <h2 class="home-top-box__title">人気のトレーニングジム検索サイト</h2>
            <?php 
              $count_pages = wp_count_posts( 'studio' );
              $pages = $count_pages->publish;
             ?>
            <p class="home-top-box__desc">現在の掲載店舗数: <span class="count-tenpo"><?php echo $pages; ?></span>件</p>
            <div class="home-top-box__links">
              <a href="#search-area">エリアから検索する</a>
            </div>
          </div>
        </div>
      </div>
      <?php endif; ?>
      <?php endif; ?>
      <div class="wrapper-inner <?php if ( !$paged ) echo 'wrapper-inner--top'; ?>">
        <main id="main" class="main">
          <div class="main-inner">
            <?php if( is_home() || is_front_page() ): ?>
            <?php 
            function get_menu_by_todofuken($location){
              if( is_active_nav_menu($location) ) {
                wp_nav_menu( array(
                  'theme_location' => $location,
                  'menu_class' => 'area-nav-child__list',
                  'container'       => false,
                  'depth'          => 1,
                ) );
              }
            } ?>
            <?php if ( $paged < 2 ) : ?>
            <section class="top-section top-section--serach-area" id="search-area">
              <h3 class="top-section__heading">エリアから探す</h3>
              <div class="area-nav-wrap">
                <nav class="area-nav">
                  <ul class="area-nav__list">
                    <li class="area-nav__item"><span class="area-nav__arrow">東京エリア</span>
                      <?php get_menu_by_todofuken( 'tokyo' ); ?>
                    </li>
                    <li class="area-nav__item"><span class="area-nav__arrow">関東（東京以外）</span>
                      <?php get_menu_by_todofuken( 'kanto' ); ?>
                    </li>
                    <li class="area-nav__item"><span class="area-nav__arrow">北海道</span>
                      <?php get_menu_by_todofuken( 'hokkaido' ); ?>
                    </li>
                    <li class="area-nav__item"><span class="area-nav__arrow">東北</span>
                      <?php get_menu_by_todofuken( 'tohoku' ); ?>
                    </li>
                    <li class="area-nav__item"><span class="area-nav__arrow">北陸</span>
                      <?php get_menu_by_todofuken( 'hokuriku' ); ?>
                    </li>
                    <li class="area-nav__item"><span class="area-nav__arrow">甲信越</span>
                      <?php get_menu_by_todofuken( 'koushinetsu' ); ?>
                    </li>
                    <li class="area-nav__item"><span class="area-nav__arrow">東海</span>
                      <?php get_menu_by_todofuken( 'tokai' ); ?>
                    </li>
                    <li class="area-nav__item"><span class="area-nav__arrow">大阪エリア</span>
                      <?php get_menu_by_todofuken( 'osaka' ); ?>
                    </li>
                    <li class="area-nav__item"><span class="area-nav__arrow">近畿（大阪以外）</span>
                      <?php get_menu_by_todofuken( 'kinki' ); ?>
                    </li>
                    <li class="area-nav__item"><span class="area-nav__arrow">中国</span>
                      <?php get_menu_by_todofuken( 'chugoku' ); ?>
                    </li>
                    <li class="area-nav__item"><span class="area-nav__arrow">四国</span>
                      <?php get_menu_by_todofuken( 'shikoku' ); ?>
                    </li>
                    <li class="area-nav__item"><span class="area-nav__arrow">九州</span>
                      <?php get_menu_by_todofuken( 'kyusyu' ); ?>
                    </li>
                    <li class="area-nav__item"><span class="area-nav__arrow">沖縄</span>
                      <?php get_menu_by_todofuken( 'okinawa' ); ?>
                    </li>
                  </ul>
                </nav>
              </div>
            </section>
            <?php endif; ?>
            <?php endif; ?>
            <section class="top-section top-section--entries">
              <h3 class="top-section__heading">記事一覧</h3>
              <?php get_template_part( 'template/entries' ); ?>
            </section>
          </div>
        </main>
        <?php get_sidebar(); ?>
      </div>
    </div>
<?php get_footer(); ?>