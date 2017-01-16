    <!-- Footer -->
<footer class="footer">
  <div class="footer_top_w"></div>
  <div class="footer_main_w">
    <div class="footer_main page_content">
      <nav class="bottom_menu">
            <?php 
        wp_nav_menu( array(
          'menu_class'=>'menu',  
          'theme_location'=>'footer_menu',
          'after'=>'',
          'exclude' => 23      
        ) );
      ?>
      </nav>
      <div class="logo_bottom_w">
        <a class="logo" href="<?php echo get_home_url(); ?>"><img src="<?php echo bloginfo("template_url"); ?>/img/logo-bottom.png" alt="logo-bottom"></a>
      </div>
      <div class="contacts_block">
       <div class="contact_phone_w"><i class="icon-phone-icon icon"></i><span class="contact_phone"><?php echo ale_get_option('phone'); ?></span></div>
       <div class="contact_mail_w"><i class="icon-email icon"></i><span class="contact_mail"><?php echo ale_get_option('mail'); ?></span></div>
      </div>
       <div class="social_w"><?php dynamic_sidebar( 'top-area' ); ?></div>
    </div>
  </div>
  <div class="footer_bottom_w">
    <section class="fotter_bottom page_content">
      <div class="fotter_bottom_inner">
        <span class="copyright_text">&copy; Copyright 2017 <b>WKK Warszawki Klub Kolarski.</b></span>
        <span class="done_text">Wykonanie: <b>Pracownia stron internetowych Agapa</b></span>
      </div>
    </section>
  </div>
</footer>







    <!-- Scripts -->
    <?php wp_footer(); ?>

    <script>
      $(function() {
        $('#menu').slicknav();
      });
      $('#menu').slicknav({
         label: '',
         duration: 1000,
         easingOpen: "easeOutBounce", //available with jQuery UI
         prependTo: '#menu_holder'
       });   
    </script>




    <script>
      var mySwiper = new Swiper('.swiper-container', {
          speed: 400,
          spaceBetween: 100,
          // uniqueNavElements: true,
          // Optional parameters
          direction: 'horizontal',
          loop: true,
          //autoplay: 2000,
          // spaceBetween: 20,
          slidesPerView: 1,
          // Navigation arrows
          nextButton: '.swiper-button-next',
          prevButton: '.swiper-button-prev'
      }); 
    </script>
    <script>
    $('#gallery').photobox('a', { thumbs:true, loop:false });
</script>

</body>
</html>