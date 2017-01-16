<?php
/*
  * Template name: Zawodnicy
  * */
get_header();



?>
<main role="main" class="main_push">
    <!-- section -->
    <section>
		
     <section class="bread_crumb_w page_content">
       <span class="breadcrump_text"><?php if( function_exists('kama_breadcrumbs') ) echo kama_breadcrumbs(); ?></span>
       
     </section>
     
    <?php if (have_posts()): while (have_posts()) : the_post(); ?>

        <!-- article -->
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
          	
            <?php the_content(); ?>
           

                   
        </article>
        <!-- /article -->

    <?php endwhile; ?>

    <?php else: ?>

        <!-- article -->
        <article>

            <h2><?php _e( 'Sorry, nothing to display.', 'html5blank' ); ?></h2>

        </article>
        <!-- /article -->

    <?php endif; ?>

    </section>
    <!-- /section -->
</main>
  <a href="#slider" title="Go Top" class="enigma_scrollup" style="display: none;"><i class="icon-arrow-to-top icon"></i></a>   
    


<?php get_footer();

