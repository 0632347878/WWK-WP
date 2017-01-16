    <!-- Footer -->
    <nav class="footer-menu">

        

        <?php if(is_page_template('page-home.php')){ ?>
        <!-- Footer Menu -->

        <?php } ?>

        

    </nav>






</div>
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