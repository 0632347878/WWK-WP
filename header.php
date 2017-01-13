<!doctype html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" <?php language_attributes(); ?>> <!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<title><?php wp_title('|', true, 'right'); bloginfo('name'); ?></title>
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?> >



 <section class="section header_w">
    <header class="header row">
      <div class="logo_w">
        <a href="<?php echo get_home_url(); ?>" class="logo"><img src="<?php echo bloginfo("template_url"); ?>/img/logo.png" alt="Logo" alt=""></a>
      </div>
      <nav class="top_menu">
        <div id="menu_holder">
        <?php 
        wp_nav_menu( array(
          'menu_class'=>'menu',
          'menu_id' => 'menu',   
          'theme_location'=>'header_menu',
          'after'=>''       
        ) );
      ?>
        </div>
      </nav>
     <?php dynamic_sidebar( 'top-area' ); ?>
    </header>
  </section>