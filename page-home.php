<?php
/*
  * Template name: Home
  * */
get_header();



?>
<main role="main" class="main_push">
    <!-- section -->
    <section>
		<?php ale_get_option('sitelogo'); ?>
		<!-- begin slider -->
		<div class="slider_w">
		  <div class="section_slider">
				<div class="homeslider wrapper">
					<ul class="slides">
						<?php $slider = ale_sliders_get_slider('test-slider');  ?>
						<?php if($slider):?>
							<?php foreach ($slider['slides'] as $slide) : ?>
								<li>
									<figure>
										<img src="<?php echo $slide['image']; ?>" alt="<?php echo $slide['title']; ?>" />
										<figcaption>
											<div class="sliderdata">
												<?php if($slide['title']){ ?>
													<div class="titleslide headerfont">
														<?php if($slide['url']){
															echo "<a href='".$slide['url']."'>";
														} ?>

														<?php echo $slide['title']; ?>

														<?php if($slide['url']){
															echo "</a>";
														} ?>
													</div>
												<?php } ?>
												<?php if($slide['description']){ ?>
													<div class="descriptionslide">
														<?php echo $slide['description']; ?>
													</div>
												<?php } ?>
												<?php if($slide['html']){ ?>
													<div class="descriptionslide">
														<?php echo $slide['html']; ?>
													</div>
												<?php } ?>
											</div>
										</figcaption>
									</figure>
								</li>
							<?php endforeach; ?>
						<?php endif;?>
					</ul>
				<div class="round_btn"> 
				   <a href="#" class="gallery_link">
				    <i class="icon icon_duble"></i>
				    <p class="btn_title_big">dołącz</p>
				    <p class="btn_title_few">do nas</p>
				   </a> 
				 </div>
				</div>
				</div> 
     </div>   
     <!-- end slider -->
     <!-- begin section_under_gallery -->
     <section class="under_gallery_w">
       <div class="section_under_gallery page_content">
         <div class="under_gallery_inner">
           <div class="icon_w"><i class="icon_info icon"></i></div>
           <div class="content_holder">
             <h1 class="main_heading"><?php echo ale_get_option('headingundergallery'); ?></h1>
             <p class="inner_text1"><?php echo ale_get_option('paragraph1'); ?></p>
             <p class="inner_text2"><?php echo ale_get_option('paragraph2'); ?></p>
             <p class="inner_text_3"><?php echo ale_get_option('paragraph3'); ?></p>
           </div>
           <div class="round_btn round_btn__small"> 
              <a href="#" class="gallery_link">
               <i class="icon icon_duble_small"></i>
               <p class="btn_title2_big">dołącz</p>
               <p class="btn_title2_few">do nas</p>
              </a> 
           </div>
         </div>
       </div>
     </section>
     <section class="bread_crumb_w page_content">
       <span class="breadcrump_text"><?php if( function_exists('kama_breadcrumbs') ) echo kama_breadcrumbs(); ?></span>
       
     </section>
     
		 <!-- end section_under_gallery -->
    <?php if (have_posts()): while (have_posts()) : the_post(); ?>

        <!-- article -->
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
          	
            <?php the_content(); ?>
            <div id="slider-images">
            <?php 
							$id=145; 
							$post = get_post($id); 
							$content = apply_filters('the_content', $post->post_content); 
							echo $content;  
						?>
						</div>
            <!-- Slider main container -->
            <div class="swiper-container page_content">
                <!-- Additional required wrapper -->
                <div class="swiper-wrapper" id="gallery">

                </div>

<script>
var galleryBlock = document.querySelector("#gallery");
var oldImgBlock = document.querySelector("#slider-images")
var sliderImagesArray = document.querySelectorAll("#slider-images .wp-caption");

function addSlide(){
	var newSlide = document.createElement("div");
	galleryBlock.appendChild(newSlide);
	newSlide.classList.add("swiper-slide");
}
  
function wrapTheSlides(){
	var count = 0;
	for(i=0;i<sliderImagesArray.length;i++){
		if(count === 0){
			addSlide();
			console.log(count);
		}
		if(count<8){
			galleryBlock.lastChild.innerHTML += sliderImagesArray[i].outerHTML;
			count++;
			if(i === sliderImagesArray.length - 1){
				oldImgBlock.remove();
			}
		}
		if(count>=8){
			count = 0;
		}
	}
}

wrapTheSlides();

</script>   
                
                <!-- If we need navigation buttons -->
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
                
               
            </div>
            

            
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

