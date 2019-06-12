<?php
/**
 * Footer
 *
 * @package BBSB
 **/

?>
<footer class="footer section">
  <div class="page-wrap">
  <div class="wp-block-columns has-3-columns">
    <div class="wp-block-column">
      <p>Seniorenstift Neuhausen</p>
      <p>Winthirstr. 20</p>
      <p>80639 München</p>
      <br>
      <p>Telefon: 089/1678200</p>
      <a href="mailto:info@heimfuerblindefrauen.de">info@heimfuerblindefrauen.de</a>
    </div>
    <nav class="wp-block-column footer-main-nav">
      <ul id="footer-main-menu">
      <?php
        wp_nav_menu(
          array(
            'theme_location' => 'footer-main',
            'walker' => new Footer_Nav_Walker(),
            'container'      => '',
            'items_wrap'     => '%3$s',
            'depth'          => 1,
          )
        );
      ?>
      </ul>
    </nav>
    <nav class="wp-block-column footer-service-nav">
      <ul id="footer-service-menu">
      <?php
        wp_nav_menu(
          array(
            'theme_location' => 'footer-service',
            'walker' => new Footer_Nav_Walker(),
            'container'      => '',
            'items_wrap'     => '%3$s',
            'depth'          => 1,
          )
        );
      ?>
      </ul>
    </nav>
  </div>
  <div class="copyright">
    <p>&copy; Seniorenstift Neuhausen 2019</p>
  </div>
</div>
</footer>

</div>
<?php wp_footer(); ?>
</body>
</html>
