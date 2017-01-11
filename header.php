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
    <!--       <ul id="menu">
            <li class="top_menu_item"><a class="top_menu_link" href="#">Strona główna</a></li>
            <li class="top_menu_item"><a class="top_menu_link" href="#">Aktualności</a></li>
            <li class="top_menu_item"><a class="top_menu_link" href="#">Dołącz do nas </a></li>
            <li class="top_menu_item"><a class="top_menu_link" href="#">Treningi</a></li>
            <li class="drop_btn top_menu_item">
              <a class="top_menu_link" href="#">Poznaj nas</a>
              <ul class="dropdown_content">
                <li class="dropdown_item tringle_parent"><a class="dropdown_link" href="#">Osiągnięcia</a></li>
                <li class="dropdown_item"><a class="dropdown_link" href="#">Historia</a></li>
                <li class="dropdown_item"><a class="dropdown_link" href="#">Statut</a></li>
                <li class="dropdown_item"><a class="dropdown_link" href="#">Wspieraj nas</a></li>
                <li class="dropdown_item"><a class="dropdown_link" href="#">Przekaż 1%</a></li>
                <li class="dropdown_item"><a class="dropdown_link dropdown_link__mod" href="#">Nasi medaliści 
                     Mistrzostw Polski </a></li>
                <li class="dropdown_item"><a class="dropdown_link dropdown_link__mod" href="#">BikePark Powstania 
                     Warszawskiego</a></li>
              </ul>
            </li>
            <li class="top_menu_item"><a class="top_menu_link" href="#">Zespół</a></li>
            <li class="top_menu_item"><a class="top_menu_link" href="#">Zdjęcia</a></li>
            <li class="top_menu_item"><a class="top_menu_link" href="#">Filmy</a></li>
            <li class="top_menu_item"><a class="top_menu_link" href="#">Partnerzy</a></li>
            <li class="top_menu_item"><a class="top_menu_link" href="#">Kontakt</a></li>
          </ul> -->
        </div>
      </nav>
      <ul class="social">
        <li class="sicial_top_item">
          <a class="sicial_top_link icon icon_facebook" href="#"></a>
        </li>
        <li class="sicial_top_item">
          <a class="sicial_top_link icon icon_play" href="#"></a>
        </li>
        <li class="sicial_top_item">
          <a class="sicial_top_link icon icon_instagram" href="#"></a>
        </li>
        </div>
    </header>
  </section>