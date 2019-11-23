  <div class="nav-footer-wrap">
    <?php 
      wp_nav_menu( array(
        'theme_location' => 'footer',
        'menu_class'     => 'nav-footer__list',
        'container'      => 'nav',
        'container_class'=> 'nav-footer',
        'depth'          => 1,
      ) );
     ?>
  </div>
  <footer class="footer" id="footer">
    <div class="footer-inner">
      <div class="copyright">
        <p class="copyright__text">copyright&copy; <?php bloginfo( 'name' ); ?> Allrights Reserved.</p>
      </div>
    </div>
  </footer>
  </div><!-- .container -->
<?php wp_footer(); ?>
</body>
</html>