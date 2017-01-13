<?php
/**
 * Get option wrapper
 * @param mixed $name
 * @param mixed $default
 * @return mixed 
 */
function ale_option($name, $default = false) {
	echo ale_get_option($name, $default);
}
function ale_filtered_option($name, $default = false, $filter = 'the_content') {
	echo apply_filters($filter, ale_get_option($name, $default));
}
function ale_get_option($name, $default = false) {
	$name = 'ale_' . $name;
	if (false === $default) {
		$options = aletheme_get_options();
		foreach ($options as $option) {
			if (isset($option['id']) && $option['id'] == $name) {
				$default = isset($option['std']) ? $option['std'] : false;
				break;
			}
		}
	}
	return of_get_option($name, $default);
}

/**
 * Echo meta for post
 * @param string $key
 * @param boolean $single
 * @param mixed $post_id 
 */
function ale_meta($key, $single = true, $post_id = null) {
	echo ale_get_meta($key, $single, $post_id);
}
/**
 * Find meta for post
 * @param string $key
 * @param boolean $single
 * @param mixed $post_id 
 */
function ale_get_meta($key, $single = true, $post_id = null) {
	if (null === $post_id) {
		$post_id = get_the_ID();
	}
	$key = 'ale_' . $key;
	return get_post_meta($post_id, $key, $single);
}
/**
 * Apply filters to post meta
 * @param string $key
 * @param string $filter
 * @param mixed $post_id 
 */
function ale_filtered_meta($key, $filter = 'the_content', $post_id = null) {
	echo apply_filters($filter, ale_get_meta($key, true, $post_id));
}

/**
 * Display permalink 
 * 
 * @param int|string $system
 * @param int $isCat 
 */
function ale_permalink($system, $isCat = false) {
    echo ale_get_permalink($system, $isCat);
}
/**
 * Get permalink for page, post or category
 * 
 * @param int|string $system
 * @param bool $isCat
 * @return string
 */
function ale_get_permalink($system, $isCat = 0)  {
    if ($isCat) {
        if (!is_numeric($system)) {
            $system = get_cat_ID($system);
        }
        return get_category_link($system);
    } else {
        $page = ale_get_page($system);
        
        return null === $page ? '' : get_permalink($page->ID);
    }
}

/**
 * Display custom excerpt
 */
function ale_excerpt() {
    echo ale_get_excerpt();
}
/**
 * Get only excerpt, without content.
 * 
 * @global object $post
 * @return string 
 */
function ale_get_excerpt() {
    global $post;
	$excerpt = trim($post->post_excerpt);
	$excerpt = $excerpt ? apply_filters('the_content', $excerpt) : '';
    return $excerpt;
}

/**
 * Display first category link
 */
function ale_first_category() {
    $cat = ale_get_first_category();
	if (!$cat) {
		echo '';
		return;
	}
    echo '<a href="' . ale_get_permalink($cat->cat_ID, true) . '">' . $cat->name . '</a>';
}
/**
 * Parse first post category
 */
function ale_get_first_category() {
    $cats = get_the_category();
    return isset($cats[0]) ? $cats[0] : null;
}

/**
 * Get page by name, id or slug. 
 * @global object $wpdb
 * @param mixed $name
 * @return object 
 */
function ale_get_page($slug) {
    global $wpdb;
    
    if (is_numeric($slug)) {
        $page = get_page($slug);
    } else {
        $page = $wpdb->get_row($wpdb->prepare("SELECT DISTINCT * FROM $wpdb->posts WHERE post_name=%s AND post_status=%s", $slug, 'publish'));
    }
    
    return $page;
}

/**
 * Find all subpages for page
 * @param int $id
 * @return array
 */
function ale_get_subpages($id) {
    $query = new WP_Query(array(
        'post_type'         => 'page',
        'orderby'           => 'menu_order',
        'order'             => 'ASC',
        'posts_per_page'    => -1,
        'post_parent'       => (int) $id,
    ));

    $entries = array();
    while ($query->have_posts()) : $query->the_post();
        $entry = array(
            'id' => get_the_ID(),
            'title' => get_the_title(),
            'link' => get_permalink(),
            'content' => get_the_content(),
        );
        $entries[] = $entry;
    endwhile;
    wp_reset_query();
    return $entries;
}

function ale_page_links() {
	global $wp_query, $wp_rewrite;
	$wp_query->query_vars['paged'] > 1 ? $current = $wp_query->query_vars['paged'] : $current = 1;
 
	$pagination = array(
		'base' => @add_query_arg('page','%#%'),
		'format' => '',
		'total' => $wp_query->max_num_pages,
		'current' => $current,
		'show_all' => true,
		'type' => 'list',
		'next_text' => 'Следующие посты',
		'prev_text' => 'Предыдущие посты'
		);
 
	if( $wp_rewrite->using_permalinks() )
		$pagination['base'] = user_trailingslashit( trailingslashit( remove_query_arg( 's', get_pagenum_link( 1 ) ) ) . 'page/%#%/', 'paged' );
 
	if( !empty($wp_query->query_vars['s']) )
		$pagination['add_args'] = array( 's' => get_query_var( 's' ) );
 
	echo paginate_links($pagination);
}

function ale_page_links_custom($custom_query) {
        global $wp_query, $wp_rewrite;
        $custom_query->query_vars['paged'] > 1 ? $current = $custom_query->query_vars['paged'] : $current = 1;

        $pagination = array(
            'base' => @add_query_arg('page','%#%'),
            'format' => '',
            'total' => $custom_query->max_num_pages,
            'current' => $current,
            'show_all' => true,
            'type' => 'list',
            'next_text' => 'Следующие посты',
            'prev_text' => 'Предыдущие посты'
        );

        if( $wp_rewrite->using_permalinks() )
                $pagination['base'] = user_trailingslashit( trailingslashit( remove_query_arg( 's', get_pagenum_link( 1 ) ) ) . 'page/%#%/', 'paged' );

        if( !empty($custom_query->query_vars['s']) )
                $pagination['add_args'] = array( 's' => get_query_var( 's' ) );

        echo paginate_links($pagination);
}


/**
 * Generate random number
 *
 * Creates a 4 digit random number for used
 * mostly for unique ID creation. 
 * 
 * @return integer 
 */
function ale_get_random_number() {
	return substr( md5( uniqid( rand(), true) ), 0, 4 );
}

/**
 * Retreive Google Fonts List.
 * 
 * @return array 
 */
function ale_get_google_webfonts()
{
	return array(
        'ABeeZee' => 'ABeeZee',
        'Abel' => 'Abel',
        'Abril+Fatface' => 'Abril Fatface',
        'Aclonica' => 'Aclonica',
        'Acme' => 'Acme',
        'Actor' => 'Actor',
        'Adamina' => 'Adamina',
        'Advent+Pro' => 'Advent Pro',
        'Aguafina+Script' => 'Aguafina Script',
        'Akronim' => 'Akronim',
        'Aladin' => 'Aladin',
        'Aldrich' => 'Aldrich',
        'Alegreya' => 'Alegreya',
        'Alegreya+SC' => 'Alegreya SC',
        'Alex+Brush' => 'Alex Brush',
        'Alfa+Slab+One' => 'Alfa Slab One',
        'Alice' => 'Alice',
        'Alike' => 'Alike',
        'Alike+Angular' => 'Alike Angular',
        'Allan' => 'Allan',
        'Allerta' => 'Allerta',
        'Allerta+Stencil' => 'Allerta Stencil',
        'Allura' => 'Allura',
        'Almendra' => 'Almendra',
        'Almendra+Display' => 'Almendra Display',
        'Almendra+SC' => 'Almendra SC',
        'Amarante' => 'Amarante',
        'Amaranth' => 'Amaranth',
        'Amatic+SC' => 'Amatic SC',
        'Amethysta' => 'Amethysta',
        'Anaheim' => 'Anaheim',
        'Andada' => 'Andada',
        'Andika' => 'Andika',
        'Angkor' => 'Angkor',
        'Annie+Use+Your+Telescope' => 'Annie Use Your Telescope',
        'Anonymous+Pro' => 'Anonymous Pro',
        'Antic' => 'Antic',
        'Antic+Didone' => 'Antic Didone',
        'Antic+Slab' => 'Antic Slab',
        'Anton' => 'Anton',
        'Arapey' => 'Arapey',
        'Arbutus' => 'Arbutus',
        'Arbutus+Slab' => 'Arbutus Slab',
        'Architects+Daughter' => 'Architects Daughter',
        'Archivo+Black' => 'Archivo Black',
        'Archivo+Narrow' => 'Archivo Narrow',
        'Arimo' => 'Arimo',
        'Arizonia' => 'Arizonia',
        'Armata' => 'Armata',
        'Artifika' => 'Artifika',
        'Arvo' => 'Arvo',
        'Asap' => 'Asap',
        'Asset' => 'Asset',
        'Astloch' => 'Astloch',
        'Asul' => 'Asul',
        'Atomic+Age' => 'Atomic Age',
        'Aubrey' => 'Aubrey',
        'Audiowide' => 'Audiowide',
        'Autour+One' => 'Autour One',
        'Average' => 'Average',
        'Average+Sans' => 'Average Sans',
        'Averia+Gruesa+Libre' => 'Averia Gruesa Libre',
        'Averia+Libre' => 'Averia Libre',
        'Averia+Sans+Libre' => 'Averia Sans Libre',
        'Averia+Serif+Libre' => 'Averia Serif Libre',
        'Bad+Script' => 'Bad Script',
        'Balthazar' => 'Balthazar',
        'Bangers' => 'Bangers',
        'Basic' => 'Basic',
        'Battambang' => 'Battambang',
        'Baumans' => 'Baumans',
        'Bayon' => 'Bayon',
        'Belgrano' => 'Belgrano',
        'Belleza' => 'Belleza',
        'BenchNine' => 'BenchNine',
        'Bentham' => 'Bentham',
        'Berkshire+Swash' => 'Berkshire Swash',
        'Bevan' => 'Bevan',
        'Bigelow+Rules' => 'Bigelow Rules',
        'Bigshot+One' => 'Bigshot One',
        'Bilbo' => 'Bilbo',
        'Bilbo+Swash+Caps' => 'Bilbo Swash Caps',
        'Bitter' => 'Bitter',
        'Black+Ops+One' => 'Black Ops One',
        'Bokor' => 'Bokor',
        'Bonbon' => 'Bonbon',
        'Boogaloo' => 'Boogaloo',
        'Bowlby+One' => 'Bowlby One',
        'Bowlby+One+SC' => 'Bowlby One SC',
        'Brawler' => 'Brawler',
        'Bree+Serif' => 'Bree Serif',
        'Bubblegum+Sans' => 'Bubblegum Sans',
        'Bubbler+One' => 'Bubbler One',
        'Buda' => 'Buda',
        'Buenard' => 'Buenard',
        'Butcherman' => 'Butcherman',
        'Butterfly+Kids' => 'Butterfly Kids',
        'Cabin' => 'Cabin',
        'Cabin+Condensed' => 'Cabin Condensed',
        'Cabin+Sketch' => 'Cabin Sketch',
        'Caesar+Dressing' => 'Caesar Dressing',
        'Cagliostro' => 'Cagliostro',
        'Calligraffitti' => 'Calligraffitti',
        'Cambo' => 'Cambo',
        'Candal' => 'Candal',
        'Cantarell' => 'Cantarell',
        'Cantata+One' => 'Cantata One',
        'Cantora+One' => 'Cantora One',
        'Capriola' => 'Capriola',
        'Cardo' => 'Cardo',
        'Carme' => 'Carme',
        'Carrois+Gothic' => 'Carrois Gothic',
        'Carrois+Gothic+SC' => 'Carrois Gothic SC',
        'Carter+One' => 'Carter One',
        'Caudex' => 'Caudex',
        'Cedarville+Cursive' => 'Cedarville Cursive',
        'Ceviche+One' => 'Ceviche One',
        'Changa+One' => 'Changa One',
        'Chango' => 'Chango',
        'Chau+Philomene+One' => 'Chau Philomene One',
        'Chela+One' => 'Chela One',
        'Chelsea+Market' => 'Chelsea Market',
        'Chenla' => 'Chenla',
        'Cherry+Cream+Soda' => 'Cherry Cream Soda',
        'Cherry+Swash' => 'Cherry Swash',
        'Chewy' => 'Chewy',
        'Chicle' => 'Chicle',
        'Chivo' => 'Chivo',
        'Cinzel' => 'Cinzel',
        'Cinzel+Decorative' => 'Cinzel Decorative',
        'Clicker+Script' => 'Clicker Script',
        'Coda' => 'Coda',
        'Coda+Caption' => 'Coda Caption',
        'Codystar' => 'Codystar',
        'Combo' => 'Combo',
        'Comfortaa' => 'Comfortaa',
        'Coming+Soon' => 'Coming Soon',
        'Concert+One' => 'Concert One',
        'Condiment' => 'Condiment',
        'Content' => 'Content',
        'Contrail+One' => 'Contrail One',
        'Convergence' => 'Convergence',
        'Cookie' => 'Cookie',
        'Copse' => 'Copse',
        'Corben' => 'Corben',
        'Courgette' => 'Courgette',
        'Cousine' => 'Cousine',
        'Coustard' => 'Coustard',
        'Covered+By+Your+Grace' => 'Covered By Your Grace',
        'Crafty+Girls' => 'Crafty Girls',
        'Creepster' => 'Creepster',
        'Crete+Round' => 'Crete Round',
        'Crimson+Text' => 'Crimson Text',
        'Croissant+One' => 'Croissant One',
        'Crushed' => 'Crushed',
        'Cuprum' => 'Cuprum',
        'Cutive' => 'Cutive',
        'Cutive+Mono' => 'Cutive Mono',
        'Damion' => 'Damion',
        'Dancing+Script' => 'Dancing Script',
        'Dangrek' => 'Dangrek',
        'Dawning+of+a+New+Day' => 'Dawning of a New Day',
        'Days+One' => 'Days One',
        'Delius' => 'Delius',
        'Delius+Swash+Caps' => 'Delius Swash Caps',
        'Delius+Unicase' => 'Delius Unicase',
        'Della+Respira' => 'Della Respira',
        'Denk+One' => 'Denk One',
        'Devonshire' => 'Devonshire',
        'Didact+Gothic' => 'Didact Gothic',
        'Diplomata' => 'Diplomata',
        'Diplomata+SC' => 'Diplomata SC',
        'Domine' => 'Domine',
        'Donegal+One' => 'Donegal One',
        'Doppio+One' => 'Doppio One',
        'Dorsa' => 'Dorsa',
        'Dosis' => 'Dosis',
        'Dr+Sugiyama' => 'Dr Sugiyama',
        'Droid+Sans' => 'Droid Sans',
        'Droid+Sans+Mono' => 'Droid Sans Mono',
        'Droid+Serif' => 'Droid Serif',
        'Duru+Sans' => 'Duru Sans',
        'Dynalight' => 'Dynalight',
        'EB+Garamond' => 'EB Garamond',
        'Eagle+Lake' => 'Eagle Lake',
        'Eater' => 'Eater',
        'Economica' => 'Economica',
        'Electrolize' => 'Electrolize',
        'Elsie' => 'Elsie',
        'Elsie+Swash+Caps' => 'Elsie Swash Caps',
        'Emblema+One' => 'Emblema One',
        'Emilys+Candy' => 'Emilys Candy',
        'Engagement' => 'Engagement',
        'Englebert' => 'Englebert',
        'Enriqueta' => 'Enriqueta',
        'Erica+One' => 'Erica One',
        'Esteban' => 'Esteban',
        'Euphoria+Script' => 'Euphoria Script',
        'Ewert' => 'Ewert',
        'Exo' => 'Exo',
        'Expletus+Sans' => 'Expletus Sans',
        'Fanwood+Text' => 'Fanwood Text',
        'Fascinate' => 'Fascinate',
        'Fascinate+Inline' => 'Fascinate Inline',
        'Faster+One' => 'Faster One',
        'Fasthand' => 'Fasthand',
        'Federant' => 'Federant',
        'Federo' => 'Federo',
        'Felipa' => 'Felipa',
        'Fenix' => 'Fenix',
        'Finger+Paint' => 'Finger Paint',
        'Fjalla+One' => 'Fjalla One',
        'Fjord+One' => 'Fjord One',
        'Flamenco' => 'Flamenco',
        'Flavors' => 'Flavors',
        'Fondamento' => 'Fondamento',
        'Fontdiner+Swanky' => 'Fontdiner Swanky',
        'Forum' => 'Forum',
        'Francois+One' => 'Francois One',
        'Freckle+Face' => 'Freckle Face',
        'Fredericka+the+Great' => 'Fredericka the Great',
        'Fredoka+One' => 'Fredoka One',
        'Freehand' => 'Freehand',
        'Fresca' => 'Fresca',
        'Frijole' => 'Frijole',
        'Fruktur' => 'Fruktur',
        'Fugaz+One' => 'Fugaz One',
        'GFS+Didot' => 'GFS Didot',
        'GFS+Neohellenic' => 'GFS Neohellenic',
        'Gabriela' => 'Gabriela',
        'Gafata' => 'Gafata',
        'Galdeano' => 'Galdeano',
        'Galindo' => 'Galindo',
        'Gentium+Basic' => 'Gentium Basic',
        'Gentium+Book+Basic' => 'Gentium Book Basic',
        'Geo' => 'Geo',
        'Geostar' => 'Geostar',
        'Geostar+Fill' => 'Geostar Fill',
        'Germania+One' => 'Germania One',
        'Gilda+Display' => 'Gilda Display',
        'Give+You+Glory' => 'Give You Glory',
        'Glass+Antiqua' => 'Glass Antiqua',
        'Glegoo' => 'Glegoo',
        'Gloria+Hallelujah' => 'Gloria Hallelujah',
        'Goblin+One' => 'Goblin One',
        'Gochi+Hand' => 'Gochi Hand',
        'Gorditas' => 'Gorditas',
        'Goudy+Bookletter+1911' => 'Goudy Bookletter 1911',
        'Graduate' => 'Graduate',
        'Grand+Hotel' => 'Grand Hotel',
        'Gravitas+One' => 'Gravitas One',
        'Great+Vibes' => 'Great Vibes',
        'Griffy' => 'Griffy',
        'Gruppo' => 'Gruppo',
        'Gudea' => 'Gudea',
        'Habibi' => 'Habibi',
        'Hammersmith+One' => 'Hammersmith One',
        'Hanalei' => 'Hanalei',
        'Hanalei+Fill' => 'Hanalei Fill',
        'Handlee' => 'Handlee',
        'Hanuman' => 'Hanuman',
        'Happy+Monkey' => 'Happy Monkey',
        'Headland+One' => 'Headland One',
        'Henny+Penny' => 'Henny Penny',
        'Herr+Von+Muellerhoff' => 'Herr Von Muellerhoff',
        'Holtwood+One+SC' => 'Holtwood One SC',
        'Homemade+Apple' => 'Homemade Apple',
        'Homenaje' => 'Homenaje',
        'IM+Fell+DW+Pica' => 'IM Fell DW Pica',
        'IM+Fell+DW+Pica+SC' => 'IM Fell DW Pica SC',
        'IM+Fell+Double+Pica' => 'IM Fell Double Pica',
        'IM+Fell+Double+Pica+SC' => 'IM Fell Double Pica SC',
        'IM+Fell+English' => 'IM Fell English',
        'IM+Fell+English+SC' => 'IM Fell English SC',
        'IM+Fell+French+Canon' => 'IM Fell French Canon',
        'IM+Fell+French+Canon+SC' => 'IM Fell French Canon SC',
        'IM+Fell+Great+Primer' => 'IM Fell Great Primer',
        'IM+Fell+Great+Primer+SC' => 'IM Fell Great Primer SC',
        'Iceberg' => 'Iceberg',
        'Iceland' => 'Iceland',
        'Imprima' => 'Imprima',
        'Inconsolata' => 'Inconsolata',
        'Inder' => 'Inder',
        'Indie+Flower' => 'Indie Flower',
        'Inika' => 'Inika',
        'Irish+Grover' => 'Irish Grover',
        'Istok+Web' => 'Istok Web',
        'Italiana' => 'Italiana',
        'Italianno' => 'Italianno',
        'Jacques+Francois' => 'Jacques Francois',
        'Jacques+Francois+Shadow' => 'Jacques Francois Shadow',
        'Jim+Nightshade' => 'Jim Nightshade',
        'Jockey+One' => 'Jockey One',
        'Jolly+Lodger' => 'Jolly Lodger',
        'Josefin+Sans' => 'Josefin Sans',
        'Josefin+Slab' => 'Josefin Slab',
        'Joti+One' => 'Joti One',
        'Judson' => 'Judson',
        'Julee' => 'Julee',
        'Julius+Sans+One' => 'Julius Sans One',
        'Junge' => 'Junge',
        'Jura' => 'Jura',
        'Just+Another+Hand' => 'Just Another Hand',
        'Just+Me+Again+Down+Here' => 'Just Me Again Down Here',
        'Kameron' => 'Kameron',
        'Karla' => 'Karla',
        'Kaushan+Script' => 'Kaushan Script',
        'Kavoon' => 'Kavoon',
        'Keania+One' => 'Keania One',
        'Kelly+Slab' => 'Kelly Slab',
        'Kenia' => 'Kenia',
        'Khmer' => 'Khmer',
        'Kite+One' => 'Kite One',
        'Knewave' => 'Knewave',
        'Kotta+One' => 'Kotta One',
        'Koulen' => 'Koulen',
        'Kranky' => 'Kranky',
        'Kreon' => 'Kreon',
        'Kristi' => 'Kristi',
        'Krona+One' => 'Krona One',
        'La+Belle+Aurore' => 'La Belle Aurore',
        'Lancelot' => 'Lancelot',
        'Lato' => 'Lato',
        'League+Script' => 'League Script',
        'Leckerli+One' => 'Leckerli One',
        'Ledger' => 'Ledger',
        'Lekton' => 'Lekton',
        'Lemon' => 'Lemon',
        'Libre+Baskerville' => 'Libre Baskerville',
        'Life+Savers' => 'Life Savers',
        'Lilita+One' => 'Lilita One',
        'Limelight' => 'Limelight',
        'Linden+Hill' => 'Linden Hill',
        'Lobster' => 'Lobster',
        'Lobster+Two' => 'Lobster Two',
        'Londrina+Outline' => 'Londrina Outline',
        'Londrina+Shadow' => 'Londrina Shadow',
        'Londrina+Sketch' => 'Londrina Sketch',
        'Londrina+Solid' => 'Londrina Solid',
        'Lora' => 'Lora',
        'Love+Ya+Like+A+Sister' => 'Love Ya Like A Sister',
        'Loved+by+the+King' => 'Loved by the King',
        'Lovers+Quarrel' => 'Lovers Quarrel',
        'Luckiest+Guy' => 'Luckiest Guy',
        'Lusitana' => 'Lusitana',
        'Lustria' => 'Lustria',
        'Macondo' => 'Macondo',
        'Macondo+Swash+Caps' => 'Macondo Swash Caps',
        'Magra' => 'Magra',
        'Maiden+Orange' => 'Maiden Orange',
        'Mako' => 'Mako',
        'Marcellus' => 'Marcellus',
        'Marcellus+SC' => 'Marcellus SC',
        'Marck+Script' => 'Marck Script',
        'Margarine' => 'Margarine',
        'Marko+One' => 'Marko One',
        'Marmelad' => 'Marmelad',
        'Marvel' => 'Marvel',
        'Mate' => 'Mate',
        'Mate+SC' => 'Mate SC',
        'Maven+Pro' => 'Maven Pro',
        'McLaren' => 'McLaren',
        'Meddon' => 'Meddon',
        'MedievalSharp' => 'MedievalSharp',
        'Medula+One' => 'Medula One',
        'Megrim' => 'Megrim',
        'Meie+Script' => 'Meie Script',
        'Merienda' => 'Merienda',
        'Merienda+One' => 'Merienda One',
        'Merriweather' => 'Merriweather',
        'Merriweather+Sans' => 'Merriweather Sans',
        'Metal' => 'Metal',
        'Metal+Mania' => 'Metal Mania',
        'Metamorphous' => 'Metamorphous',
        'Metrophobic' => 'Metrophobic',
        'Michroma' => 'Michroma',
        'Milonga' => 'Milonga',
        'Miltonian' => 'Miltonian',
        'Miltonian+Tattoo' => 'Miltonian Tattoo',
        'Miniver' => 'Miniver',
        'Miss+Fajardose' => 'Miss Fajardose',
        'Modern+Antiqua' => 'Modern Antiqua',
        'Molengo' => 'Molengo',
        'Molle' => 'Molle',
        'Monda' => 'Monda',
        'Monofett' => 'Monofett',
        'Monoton' => 'Monoton',
        'Monsieur+La+Doulaise' => 'Monsieur La Doulaise',
        'Montaga' => 'Montaga',
        'Montez' => 'Montez',
        'Montserrat' => 'Montserrat',
        'Montserrat+Alternates' => 'Montserrat Alternates',
        'Montserrat+Subrayada' => 'Montserrat Subrayada',
        'Moul' => 'Moul',
        'Moulpali' => 'Moulpali',
        'Mountains+of+Christmas' => 'Mountains of Christmas',
        'Mouse+Memoirs' => 'Mouse Memoirs',
        'Mr+Bedfort' => 'Mr Bedfort',
        'Mr+Dafoe' => 'Mr Dafoe',
        'Mr+De+Haviland' => 'Mr De Haviland',
        'Mrs+Saint+Delafield' => 'Mrs Saint Delafield',
        'Mrs+Sheppards' => 'Mrs Sheppards',
        'Muli' => 'Muli',
        'Mystery+Quest' => 'Mystery Quest',
        'Neucha' => 'Neucha',
        'Neuton' => 'Neuton',
        'New+Rocker' => 'New Rocker',
        'News+Cycle' => 'News Cycle',
        'Niconne' => 'Niconne',
        'Nixie+One' => 'Nixie One',
        'Nobile' => 'Nobile',
        'Nokora' => 'Nokora',
        'Norican' => 'Norican',
        'Nosifer' => 'Nosifer',
        'Nothing+You+Could+Do' => 'Nothing You Could Do',
        'Noticia+Text' => 'Noticia Text',
        'Noto+Sans' => 'Noto Sans',
        'Nova+Cut' => 'Nova Cut',
        'Nova+Flat' => 'Nova Flat',
        'Nova+Mono' => 'Nova Mono',
        'Nova+Oval' => 'Nova Oval',
        'Nova+Round' => 'Nova Round',
        'Nova+Script' => 'Nova Script',
        'Nova+Slim' => 'Nova Slim',
        'Nova+Square' => 'Nova Square',
        'Numans' => 'Numans',
        'Nunito' => 'Nunito',
        'Odor+Mean+Chey' => 'Odor Mean Chey',
        'Offside' => 'Offside',
        'Old+Standard+TT' => 'Old Standard TT',
        'Oldenburg' => 'Oldenburg',
        'Oleo+Script' => 'Oleo Script',
        'Oleo+Script+Swash+Caps' => 'Oleo Script Swash Caps',
        'Open+Sans' => 'Open Sans',
        'Open+Sans+Condensed' => 'Open Sans Condensed',
        'Oranienbaum' => 'Oranienbaum',
        'Orbitron' => 'Orbitron',
        'Oregano' => 'Oregano',
        'Orienta' => 'Orienta',
        'Original+Surfer' => 'Original Surfer',
        'Oswald' => 'Oswald',
        'Over+the+Rainbow' => 'Over the Rainbow',
        'Overlock' => 'Overlock',
        'Overlock+SC' => 'Overlock SC',
        'Ovo' => 'Ovo',
        'Oxygen' => 'Oxygen',
        'Oxygen+Mono' => 'Oxygen Mono',
        'PT+Mono' => 'PT Mono',
        'PT+Sans' => 'PT Sans',
        'PT+Sans+Caption' => 'PT Sans Caption',
        'PT+Sans+Narrow' => 'PT Sans Narrow',
        'PT+Serif' => 'PT Serif',
        'PT+Serif+Caption' => 'PT Serif Caption',
        'Pacifico' => 'Pacifico',
        'Paprika' => 'Paprika',
        'Parisienne' => 'Parisienne',
        'Passero+One' => 'Passero One',
        'Passion+One' => 'Passion One',
        'Patrick+Hand' => 'Patrick Hand',
        'Patrick+Hand+SC' => 'Patrick Hand SC',
        'Patua+One' => 'Patua One',
        'Paytone+One' => 'Paytone One',
        'Peralta' => 'Peralta',
        'Permanent+Marker' => 'Permanent Marker',
        'Petit+Formal+Script' => 'Petit Formal Script',
        'Petrona' => 'Petrona',
        'Philosopher' => 'Philosopher',
        'Piedra' => 'Piedra',
        'Pinyon+Script' => 'Pinyon Script',
        'Pirata+One' => 'Pirata One',
        'Plaster' => 'Plaster',
        'Play' => 'Play',
        'Playball' => 'Playball',
        'Playfair+Display' => 'Playfair Display',
        'Playfair+Display+SC' => 'Playfair Display SC',
        'Podkova' => 'Podkova',
        'Poiret+One' => 'Poiret One',
        'Poller+One' => 'Poller One',
        'Poly' => 'Poly',
        'Pompiere' => 'Pompiere',
        'Pontano+Sans' => 'Pontano Sans',
        'Port+Lligat+Sans' => 'Port Lligat Sans',
        'Port+Lligat+Slab' => 'Port Lligat Slab',
        'Prata' => 'Prata',
        'Preahvihear' => 'Preahvihear',
        'Press+Start+2P' => 'Press Start 2P',
        'Princess+Sofia' => 'Princess Sofia',
        'Prociono' => 'Prociono',
        'Prosto+One' => 'Prosto One',
        'Puritan' => 'Puritan',
        'Purple+Purse' => 'Purple Purse',
        'Quando' => 'Quando',
        'Quantico' => 'Quantico',
        'Quattrocento' => 'Quattrocento',
        'Quattrocento+Sans' => 'Quattrocento Sans',
        'Questrial' => 'Questrial',
        'Quicksand' => 'Quicksand',
        'Quintessential' => 'Quintessential',
        'Qwigley' => 'Qwigley',
        'Racing+Sans+One' => 'Racing Sans One',
        'Radley' => 'Radley',
        'Raleway' => 'Raleway',
        'Raleway+Dots' => 'Raleway Dots',
        'Rambla' => 'Rambla',
        'Rammetto+One' => 'Rammetto One',
        'Ranchers' => 'Ranchers',
        'Rancho' => 'Rancho',
        'Rationale' => 'Rationale',
        'Redressed' => 'Redressed',
        'Reenie+Beanie' => 'Reenie Beanie',
        'Revalia' => 'Revalia',
        'Ribeye' => 'Ribeye',
        'Ribeye+Marrow' => 'Ribeye Marrow',
        'Righteous' => 'Righteous',
        'Risque' => 'Risque',
        'Roboto' => 'Roboto',
        'Roboto+Condensed' => 'Roboto Condensed',
        'Rochester' => 'Rochester',
        'Rock+Salt' => 'Rock Salt',
        'Rokkitt' => 'Rokkitt',
        'Romanesco' => 'Romanesco',
        'Ropa+Sans' => 'Ropa Sans',
        'Rosario' => 'Rosario',
        'Rosarivo' => 'Rosarivo',
        'Rouge+Script' => 'Rouge Script',
        'Ruda' => 'Ruda',
        'Rufina' => 'Rufina',
        'Ruge+Boogie' => 'Ruge Boogie',
        'Ruluko' => 'Ruluko',
        'Rum+Raisin' => 'Rum Raisin',
        'Ruslan+Display' => 'Ruslan Display',
        'Russo+One' => 'Russo One',
        'Ruthie' => 'Ruthie',
        'Rye' => 'Rye',
        'Sacramento' => 'Sacramento',
        'Sail' => 'Sail',
        'Salsa' => 'Salsa',
        'Sanchez' => 'Sanchez',
        'Sancreek' => 'Sancreek',
        'Sansita+One' => 'Sansita One',
        'Sarina' => 'Sarina',
        'Satisfy' => 'Satisfy',
        'Scada' => 'Scada',
        'Schoolbell' => 'Schoolbell',
        'Seaweed+Script' => 'Seaweed Script',
        'Sevillana' => 'Sevillana',
        'Seymour+One' => 'Seymour One',
        'Shadows+Into+Light' => 'Shadows Into Light',
        'Shadows+Into+Light+Two' => 'Shadows Into Light Two',
        'Shanti' => 'Shanti',
        'Share' => 'Share',
        'Share+Tech' => 'Share Tech',
        'Share+Tech+Mono' => 'Share Tech Mono',
        'Shojumaru' => 'Shojumaru',
        'Short+Stack' => 'Short Stack',
        'Siemreap' => 'Siemreap',
        'Sigmar+One' => 'Sigmar One',
        'Signika' => 'Signika',
        'Signika+Negative' => 'Signika Negative',
        'Simonetta' => 'Simonetta',
        'Sintony' => 'Sintony',
        'Sirin+Stencil' => 'Sirin Stencil',
        'Six+Caps' => 'Six Caps',
        'Skranji' => 'Skranji',
        'Slackey' => 'Slackey',
        'Smokum' => 'Smokum',
        'Smythe' => 'Smythe',
        'Sniglet' => 'Sniglet',
        'Snippet' => 'Snippet',
        'Snowburst+One' => 'Snowburst One',
        'Sofadi+One' => 'Sofadi One',
        'Sofia' => 'Sofia',
        'Sonsie+One' => 'Sonsie One',
        'Sorts+Mill+Goudy' => 'Sorts Mill Goudy',
        'Source+Code+Pro' => 'Source Code Pro',
        'Source+Sans+Pro' => 'Source Sans Pro',
        'Special+Elite' => 'Special Elite',
        'Spicy+Rice' => 'Spicy Rice',
        'Spinnaker' => 'Spinnaker',
        'Spirax' => 'Spirax',
        'Squada+One' => 'Squada One',
        'Stalemate' => 'Stalemate',
        'Stalinist+One' => 'Stalinist One',
        'Stardos+Stencil' => 'Stardos Stencil',
        'Stint+Ultra+Condensed' => 'Stint Ultra Condensed',
        'Stint+Ultra+Expanded' => 'Stint Ultra Expanded',
        'Stoke' => 'Stoke',
        'Strait' => 'Strait',
        'Sue+Ellen+Francisco' => 'Sue Ellen Francisco',
        'Sunshiney' => 'Sunshiney',
        'Supermercado+One' => 'Supermercado One',
        'Suwannaphum' => 'Suwannaphum',
        'Swanky+and+Moo+Moo' => 'Swanky and Moo Moo',
        'Syncopate' => 'Syncopate',
        'Tangerine' => 'Tangerine',
        'Taprom' => 'Taprom',
        'Tauri' => 'Tauri',
        'Telex' => 'Telex',
        'Tenor+Sans' => 'Tenor Sans',
        'Text+Me+One' => 'Text Me One',
        'The+Girl+Next+Door' => 'The Girl Next Door',
        'Tienne' => 'Tienne',
        'Tinos' => 'Tinos',
        'Titan+One' => 'Titan One',
        'Titillium+Web' => 'Titillium Web',
        'Trade+Winds' => 'Trade Winds',
        'Trocchi' => 'Trocchi',
        'Trochut' => 'Trochut',
        'Trykker' => 'Trykker',
        'Tulpen+One' => 'Tulpen One',
        'Ubuntu' => 'Ubuntu',
        'Ubuntu+Condensed' => 'Ubuntu Condensed',
        'Ubuntu+Mono' => 'Ubuntu Mono',
        'Ultra' => 'Ultra',
        'Uncial+Antiqua' => 'Uncial Antiqua',
        'Underdog' => 'Underdog',
        'Unica+One' => 'Unica One',
        'UnifrakturCook' => 'UnifrakturCook',
        'UnifrakturMaguntia' => 'UnifrakturMaguntia',
        'Unkempt' => 'Unkempt',
        'Unlock' => 'Unlock',
        'Unna' => 'Unna',
        'VT323' => 'VT323',
        'Vampiro+One' => 'Vampiro One',
        'Varela' => 'Varela',
        'Varela+Round' => 'Varela Round',
        'Vast+Shadow' => 'Vast Shadow',
        'Vibur' => 'Vibur',
        'Vidaloka' => 'Vidaloka',
        'Viga' => 'Viga',
        'Voces' => 'Voces',
        'Volkhov' => 'Volkhov',
        'Vollkorn' => 'Vollkorn',
        'Voltaire' => 'Voltaire',
        'Waiting+for+the+Sunrise' => 'Waiting for the Sunrise',
        'Wallpoet' => 'Wallpoet',
        'Walter+Turncoat' => 'Walter Turncoat',
        'Warnes' => 'Warnes',
        'Wellfleet' => 'Wellfleet',
        'Wendy+One' => 'Wendy One',
        'Wire+One' => 'Wire One',
        'Yanone+Kaffeesatz' => 'Yanone Kaffeesatz',
        'Yellowtail' => 'Yellowtail',
        'Yeseva+One' => 'Yeseva One',
        'Yesteryear' => 'Yesteryear',
        'Zeyada' => 'Zeyada'
	);
}

/**
 * Get Save Web Fonts
 * @return array
 */
function ale_get_safe_webfonts() {
	return array(
		'Arial'				=> 'Arial',
		'Verdana'			=> 'Verdana, Geneva',
		'Trebuchet'			=> 'Trebuchet',
		'Georgia'			=> 'Georgia',
		'Times New Roman'   => 'Times New Roman',
		'Tahoma'			=> 'Tahoma, Geneva',
		'Palatino'			=> 'Palatino',
		'Helvetica'			=> 'Helvetica',
		'Gill Sans'			=> 'Gill Sans',
	);
}

function ale_get_typo_styles() {
	return array(
		'normal'      => 'Normal',
		'italic'      => 'Italic',
	);
}

function ale_get_typo_weights() {
	return array(
		'normal'      => 'Normal',
		'bold'      => 'Bold',
	);
}

function ale_get_typo_transforms() {
	return array(
		'none'      => 'None',
		'uppercase'	=> 'UPPERCASE',
		'lowercase'	=> 'lowercase',
		'capitalize'=> 'Capitalize',
	);
}

function ale_get_typo_variants() {
	return array(
		'normal'      => 'normal',
		'small-caps'  => 'Small Caps',
	);
}

/**
 * Get default font styles
 * @return array
 */
function ale_get_font_styles() {
	return array(
		'normal'      => 'Normal',
		'italic'      => 'Italic',
		'bold'        => 'Bold',
		'bold italic' => 'Bold Italic'
	);
}

/**
 * Display custom RSS url
 */
function ale_rss() {
    echo ale_get_rss();
}

/**
 * Get custom RSS url
 */
function ale_get_rss() {
    $rss_url = ale_get_option('feedburner');
    return $rss_url ? $rss_url : get_bloginfo('rss2_url');
}

/**
 * Display custom RSS url
 */
function ale_favicon() {
    echo ale_get_favicon();
}

/**
 * Get custom RSS url
 */
function ale_get_favicon() {
    $favicon = ale_get_option('favicon');
    return $favicon ? $favicon : THEME_URL . '/aletheme/assets/favicon.ico';
}

/**
 * Get template part
 * 
 * @param string $slug
 * @param string $name
 */
function ale_part($slug, $name = null) {
	get_template_part('partials/' . $slug, $name);
}

/**
 * Page Title Wrapper
 * @param type $title 
 */
function ale_page_title($title) {
	echo ale_get_page_title($title);
}
function ale_get_page_title($title) {
	return '<header class="page-title"><h2 class="a">' . $title . '</h2></header>';
}

/**
 * Find if the current browser is on mobile device
 * @return boolean 
 */
function is_mobile() {
	if(preg_match('/(alcatel|amoi|android|avantgo|blackberry|benq|cell|cricket|docomo|elaine|htc|iemobile|iphone|ipad|ipaq|ipod|j2me|java|midp|mini|mmp|mobi|motorola|nec-|nokia|palm|panasonic|philips|phone|sagem|sharp|sie-|smartphone|sony|symbian|t-mobile|telus|up\.browser|up\.link|vodafone|wap|webos|wireless|xda|xoom|zte)/i', $_SERVER['HTTP_USER_AGENT'])) {
		return true;
	} else {
		return false;
	}
}

function array_put_to_position(&$array, $object, $position, $name = null) {
	$count = 0;
	$return = array();
	foreach ($array as $k => $v) {  
			// insert new object
			if ($count == $position) {  
					if (!$name) $name = $count;
					$return[$name] = $object;
					$inserted = true;
			}  
			// insert old object
			$return[$k] = $v;
			$count++;
	}  
	if (!$name) $name = $count;
	if (!$inserted) $return[$name];
	$array = $return;
	return $array;
}


/**
 * Get archives by year
 * 
 * @global object $wpdb
 * @param string $year
 * @return array 
 */
function ale_archives_get_by_year($year = "") {
	global $wpdb;
	
	$where = "";
	if (!empty($year)) {
		$where = "AND YEAR(post_date) = " . ((int) $year);
	}
	$query = "SELECT DISTINCT YEAR(post_date) AS `year`, MONTH(post_date) AS `month`, DATE_FORMAT(post_date, '%b') AS `abmonth`, DATE_FORMAT(post_date, '%M') AS `fmonth`, count(ID) as posts
									FROM $wpdb->posts
							WHERE post_type = 'post' AND post_status = 'publish' $where
									GROUP BY YEAR(post_date), MONTH(post_date)
									ORDER BY post_date DESC";

	return $wpdb->get_results($query);
}

/**
 * Get archives years list
 * 
 * @global object $wpdb
 * @return array 
 */
function ale_archives_get_years() {
	global $wpdb;

	$query = "SELECT DISTINCT YEAR(post_date) AS `year`
									FROM $wpdb->posts
							WHERE post_type = 'post' AND post_status = 'publish'
									GROUP BY YEAR(post_date) ORDER BY post_date DESC";

	return $wpdb->get_results($query);
}

/**
 * Get archives months list
 * 
 * @return type 
 */
function ale_archives_get_months() {
	return array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
}

/**
 * Display Archives 
 */
function ale_archives($delim = '&nbsp;/&nbsp;') {
    $year = null;
    ?>
    <div class="ale-archives cf">
        <ul class="left">
            <li><?php _e('Archives', 'aletheme');?></li>
            <li>
                <ul>
                    <li><a href="#" class="down">&lt;</a></li>
                    <li><span id="archives-active-year"></span></li>
                    <li><a href="#" class="up">&gt;</a></li>
                </ul>
            </li>
        </ul>
        <?php
            $months = ale_archives_get_months();
            $archives = ale_archives_get_by_year();
        ?>
        <div class="right">
            <div class="months">
                <?php foreach ($archives as $archive) : ?>
                    <?php
                        if ($year == $archive->year) {
                            continue;
                        }
                        $year = $archive->year;
                        $y_archives = ale_archives_get_by_year($archive->year);
                    ?>
                    <div class="year-months" id="archive-year-<?php echo $year?>">
                    <?php foreach ($months as $key => $month) :?>
                        <?php foreach ($y_archives as $y_archive) :?>
                            <?php if (($key == ($y_archive->month-1)) && $y_archive->posts):?>
                                <a href="<?php echo get_month_link($year, $y_archive->month)?>"><?php echo $month; ?></a>
                                <?php if ($key != 11 && $delim):?>
                                    <span class="delim"><?php echo $delim; ?></span>
                                <?php endif;?>
                                <?php break;?>
                            <?php endif;?>
                        <?php endforeach;?>
                        <?php if ($key != $y_archive->month-1):?>
                            <span><?php echo $month; ?></span>
                            <?php if ($key != 11 && $delim):?>
                                <span class="delim"><?php echo $delim; ?></span>
                            <?php endif;?>
                        <?php endif;?>
                    <?php endforeach;?>
                    </div>
                <?php endforeach;?>
            </div>
        </div>
    </div>
<?php
}

/**
 * Add combined actions for AJAX.
 * 
 * @param string $tag
 * @param string $function_to_add
 * @param integer $priority
 * @param integer $accepted_args 
 */
function ale_add_ajax_action($tag, $function_to_add, $priority = 10, $accepted_args = 1) {
	add_action('wp_ajax_' . $tag, $function_to_add, $priority, $accepted_args);
	add_action('wp_ajax_nopriv_' . $tag, $function_to_add, $priority, $accepted_args);
}

/**
 * Get contact form 7 from content
 * @param string $content
 * @return string 
 */
function ale_contact7_form($content) {
	$matches = array();
	preg_match('~(\[contact\-form\-7.*\])~simU', $content, $matches);
	return $matches[1];
}

/**
 * Remove contact form from content
 * @param string $content
 * @return string
 */
function ale_remove_contact7_form($content) {
	$content = preg_replace('~(\[contact\-form\-7.*\])~simU', '', $content);
	return $content;
}

/**
 * Check if it's a blog page
 * @global object $post
 * @return boolean 
 */
function ale_is_blog () {
	global  $post;
	$posttype = get_post_type($post);
	return ( ((is_archive()) || (is_author()) || (is_category()) || (is_home()) || (is_single()) || (is_tag())) && ($posttype == 'post')) ? true : false ;
}

if ( function_exists('register_sidebar') ) {

        register_sidebar(array(
            'name' => 'Main Sidebar',
            'id' => 'main-sidebar',
            'description' => 'Appears as the left sidebar on Blog pages',
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<p class="caption">',
            'after_title' => '</p><div class="line"></div>',
        ));

}

//Support automatic-feed-links
add_theme_support( 'automatic-feed-links' );

//Unreal construction to passed/hide "Theme Checker Plugin" recommendation about Header nad Background
if('Theme Checke' == 'Hide') {
    add_theme_support( 'custom-header');
    add_theme_support( 'custom-background');
}

//Comment Reply script
function aletheme_enqueue_comment_reply() {
    // on single blog post pages with comments open and threaded comments
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        // enqueue the javascript that performs in-link comment reply fanciness
        wp_enqueue_script( 'comment-reply' );
    }
}
// Hook into wp_enqueue_scripts
add_action( 'wp_enqueue_scripts', 'aletheme_enqueue_comment_reply' );

/**
 * Remove HTML attributes from comments if is Socha Comments Selected
 */
if(ale_get_option('comments_style') == 'wp'){
    add_filter( 'comment_text', 'wp_filter_nohtml_kses' );
    add_filter( 'comment_text_rss', 'wp_filter_nohtml_kses' );
    add_filter( 'comment_excerpt', 'wp_filter_nohtml_kses' );
}

function ale_trim_excerpt($length) {
    global $post;
    $explicit_excerpt = $post->post_excerpt;
    if ( '' == $explicit_excerpt ) {
        $text = get_the_content('');
        $text = apply_filters('the_content', $text);
        $text = str_replace(']]>', ']]>', $text);
    }
    else {
        $text = apply_filters('the_content', $explicit_excerpt);
    }
    $text = strip_shortcodes( $text ); // optional
    $text = strip_tags($text);
    $excerpt_length = $length;
    $words = explode(' ', $text, $excerpt_length + 1);
    if (count($words)> $excerpt_length) {
        array_pop($words);
        array_push($words, '[&hellip;]');
        $text = implode(' ', $words);
        $text = apply_filters('the_excerpt',$text);
    }
    return $text;
}





// Breadcrumbs Custom Function

function get_breadcrumbs() {

    $text['home']     = __('Home','aletheme');
    $text['category'] = __('Archive','aletheme').' "%s"';
    $text['search']   = __('Search results','aletheme').' "%s"';
    $text['tag']      = __('Tag','aletheme').' "%s"';
    $text['author']   = __('Author','aletheme').' %s';
    $text['404']      = __('Error 404','aletheme');

    $show_current   = 1;
    $show_on_home   = 0;
    $show_home_link = 1;
    $show_title     = 1;
    $delimiter      = '&nbsp; › &nbsp;';
    $before         = '<span class="current">';
    $after          = '</span>';

    global $post;
    $home_link    = home_url('/');
    $link_before  = '<span typeof="v:Breadcrumb">';
    $link_after   = '</span>';
    $link_attr    = ' rel="v:url" property="v:title"';
    $link         = $link_before . '<a' . $link_attr . ' href="%1$s">%2$s</a>' . $link_after;
    $parent_id    = $parent_id_2 = $post->post_parent;
    $frontpage_id = get_option('page_on_front');

    if (is_home() || is_front_page()) {

        if ($show_on_home == 1) echo '<div class="breadcrumbs"><a href="' . $home_link . '">' . $text['home'] . '</a></div>';

    }
    else {

        echo '<div class="breadcrumbs" xmlns:v="http://rdf.data-vocabulary.org/#">';
        if ($show_home_link == 1) {
            echo sprintf($link, $home_link, $text['home']);
            if ($frontpage_id == 0 || $parent_id != $frontpage_id) echo $delimiter;
        }

        if ( is_category() ) {
            $this_cat = get_category(get_query_var('cat'), false);
            if ($this_cat->parent != 0) {
                $cats = get_category_parents($this_cat->parent, TRUE, $delimiter);
                if ($show_current == 0) $cats = preg_replace("#^(.+)$delimiter$#", "$1", $cats);
                $cats = str_replace('<a', $link_before . '<a' . $link_attr, $cats);
                $cats = str_replace('</a>', '</a>' . $link_after, $cats);
                if ($show_title == 0) $cats = preg_replace('/ title="(.*?)"/', '', $cats);
                echo $cats;
            }
            if ($show_current == 1) echo $before . sprintf($text['category'], single_cat_title('', false)) . $after;

        } elseif ( is_search() ) {
            echo $before . sprintf($text['search'], get_search_query()) . $after;

        } elseif ( is_day() ) {
            echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $delimiter;
            echo sprintf($link, get_month_link(get_the_time('Y'),get_the_time('m')), get_the_time('F')) . $delimiter;
            echo $before . get_the_time('d') . $after;

        } elseif ( is_month() ) {
            echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $delimiter;
            echo $before . get_the_time('F') . $after;

        } elseif ( is_year() ) {
            echo $before . get_the_time('Y') . $after;

        } elseif ( is_single() && !is_attachment() ) {
            if ( get_post_type() != 'post' ) {
                $post_type = get_post_type_object(get_post_type());
                $slug = $post_type->rewrite;
                printf($link, $home_link . '/' . $slug['slug'] . '/', $post_type->labels->singular_name);
                if ($show_current == 1) echo $delimiter . $before . get_the_title() . $after;
            } else {
                $cat = get_the_category(); $cat = $cat[0];
                $cats = get_category_parents($cat, TRUE, $delimiter);
                if ($show_current == 0) $cats = preg_replace("#^(.+)$delimiter$#", "$1", $cats);
                $cats = str_replace('<a', $link_before . '<a' . $link_attr, $cats);
                $cats = str_replace('</a>', '</a>' . $link_after, $cats);
                if ($show_title == 0) $cats = preg_replace('/ title="(.*?)"/', '', $cats);
                echo $cats;
                if ($show_current == 1) echo $before . get_the_title() . $after;
            }

        } elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
            $post_type = get_post_type_object(get_post_type());
            echo $before . $post_type->labels->singular_name . $after;

        } elseif ( is_attachment() ) {
            $parent = get_post($parent_id);
            $cat = get_the_category($parent->ID); $cat = $cat[0];
            $cats = get_category_parents($cat, TRUE, $delimiter);
            $cats = str_replace('<a', $link_before . '<a' . $link_attr, $cats);
            $cats = str_replace('</a>', '</a>' . $link_after, $cats);
            if ($show_title == 0) $cats = preg_replace('/ title="(.*?)"/', '', $cats);
            echo $cats;
            printf($link, get_permalink($parent), $parent->post_title);
            if ($show_current == 1) echo $delimiter . $before . get_the_title() . $after;

        } elseif ( is_page() && !$parent_id ) {
            if ($show_current == 1) echo $before . get_the_title() . $after;

        } elseif ( is_page() && $parent_id ) {
            if ($parent_id != $frontpage_id) {
                $breadcrumbs = array();
                while ($parent_id) {
                    $page = get_page($parent_id);
                    if ($parent_id != $frontpage_id) {
                        $breadcrumbs[] = sprintf($link, get_permalink($page->ID), get_the_title($page->ID));
                    }
                    $parent_id = $page->post_parent;
                }
                $breadcrumbs = array_reverse($breadcrumbs);
                for ($i = 0; $i < count($breadcrumbs); $i++) {
                    echo $breadcrumbs[$i];
                    if ($i != count($breadcrumbs)-1) echo $delimiter;
                }
            }
            if ($show_current == 1) {
                if ($show_home_link == 1 || ($parent_id_2 != 0 && $parent_id_2 != $frontpage_id)) echo $delimiter;
                echo $before . get_the_title() . $after;
            }

        } elseif ( is_tag() ) {
            echo $before . sprintf($text['tag'], single_tag_title('', false)) . $after;

        } elseif ( is_author() ) {
            global $author;
            $userdata = get_userdata($author);
            echo $before . sprintf($text['author'], $userdata->display_name) . $after;

        } elseif ( is_404() ) {
            echo $before . $text['404'] . $after;
        }

        if ( get_query_var('paged') ) {
            if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ' (';
            echo __('Page') . ' ' . get_query_var('paged');
            if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ')';
        }

        echo '</div><!-- .breadcrumbs -->';

    }
}

// KAMA BC 

/**
 * Хлебные крошки для WordPress (breadcrumbs)
 *
 * @param  string [$sep  = '']      Разделитель. По умолчанию ' » '
 * @param  array  [$l10n = array()] Для локализации. См. переменную $default_l10n.
 * @param  array  [$args = array()] Опции. См. переменную $def_args
 * @return string Выводит на экран HTML код
 *
 * version 3.3.1
 */
function kama_breadcrumbs( $sep = ' » ', $l10n = array(), $args = array() ){
    $kb = new Kama_Breadcrumbs;
    echo $kb->get_crumbs( $sep, $l10n, $args );
}

class Kama_Breadcrumbs {

    public $arg;

    // Локализация
    static $l10n = array(
        'home'       => 'Strona główna',
        'paged'      => 'Страница %d',
        '_404'       => 'Ошибка 404',
        'search'     => 'Результаты поиска по запросу - <b>%s</b>',
        'author'     => 'Архив автора: <b>%s</b>',
        'year'       => 'Архив за <b>%d</b> год',
        'month'      => 'Архив за: <b>%s</b>',
        'day'        => '',
        'attachment' => 'Медиа: %s',
        'tag'        => 'Записи по метке: <b>%s</b>',
        'tax_tag'    => '%1$s из "%2$s" по тегу: <b>%3$s</b>',
        // tax_tag выведет: 'тип_записи из "название_таксы" по тегу: имя_термина'.
        // Если нужны отдельные холдеры, например только имя термина, пишем так: 'записи по тегу: %3$s'
    );

    // Параметры по умолчанию
    static $args = array(
        'on_front_page'   => true,  // выводить крошки на главной странице
        'show_post_title' => true,  // показывать ли название записи в конце (последний элемент). Для записей, страниц, вложений
        'show_term_title' => true,  // показывать ли название элемента таксономии в конце (последний элемент). Для меток, рубрик и других такс
        'title_patt'      => '<span class="kb_title">%s</span>', // шаблон для последнего заголовка. Если включено: show_post_title или show_term_title
        'last_sep'        => true,  // показывать последний разделитель, когда заголовок в конце не отображается
        'markup'          => 'schema.org', // 'markup' - микроразметка. Может быть: 'rdf.data-vocabulary.org', 'schema.org', '' - без микроразметки
                                           // или можно указать свой массив разметки:
                                           // array( 'wrappatt'=>'<div class="kama_breadcrumbs">%s</div>', 'linkpatt'=>'<a href="%s">%s</a>', 'sep_after'=>'', )
        'priority_tax'    => array('category'), // приоритетные таксономии, нужно когда запись в нескольких таксах
        'priority_terms'  => array(), // 'priority_terms' - приоритетные элементы таксономий, когда запись находится в нескольких элементах одной таксы одновременно.
                                      // Например: array( 'category'=>array(45,'term_name'), 'tax_name'=>array(1,2,'name') )
                                      // 'category' - такса для которой указываются приор. элементы: 45 - ID термина и 'term_name' - ярлык.
                                      // порядок 45 и 'term_name' имеет значение: чем раньше тем важнее. Все указанные термины важнее неуказанных...
        'nofollow' => false, // добавлять rel=nofollow к ссылкам?

        // служебные
        'sep'             => '',
        'linkpatt'        => '',
        'pg_end'          => '',
    );

    function get_crumbs( $sep, $l10n, $args ){
        global $post, $wp_query, $wp_post_types;

        self::$args['sep'] = $sep;

        // Фильтрует дефолты и сливает
        $loc = (object) array_merge( apply_filters('kama_breadcrumbs_default_loc', self::$l10n ), $l10n );
        $arg = (object) array_merge( apply_filters('kama_breadcrumbs_default_args', self::$args ), $args );

        $arg->sep = '<span class="kb_sep">'. $arg->sep .'</span>'; // дополним

        // упростим
        $sep = & $arg->sep;
        $this->arg = & $arg;

        // микроразметка ---
        if(1){
            $mark = & $arg->markup;

            // Разметка по умолчанию
            if( ! $mark ) $mark = array(
                'wrappatt'  => '<div class="kama_breadcrumbs">%s</div>',
                'linkpatt'  => '<a href="%s">%s</a>',
                'sep_after' => '',
            );
            // rdf
            elseif( $mark === 'rdf.data-vocabulary.org' ) $mark = array(
                'wrappatt'   => '<div class="kama_breadcrumbs" prefix="v: http://rdf.data-vocabulary.org/#">%s</div>',
                'linkpatt'   => '<span typeof="v:Breadcrumb"><a href="%s" rel="v:url" property="v:title">%s</a>',
                'sep_after'  => '</span>', // закрываем span после разделителя!
            );
            // schema.org
            elseif( $mark === 'schema.org' ) $mark = array(
                'wrappatt'   => '<div class="kama_breadcrumbs" itemscope itemtype="http://schema.org/BreadcrumbList">%s</div>',
                'linkpatt'   => '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a href="%s" itemprop="item"><span itemprop="name">%s</span></a></span>',
                'sep_after'  => '',
            );

            elseif( ! is_array($mark) )
                die( __CLASS__ .': "markup" parameter must be array...');

            $wrappatt  = $mark['wrappatt'];
            $arg->linkpatt  = $arg->nofollow ? str_replace('<a ','<a rel="nofollow"', $mark['linkpatt']) : $mark['linkpatt'];
            $arg->sep      .= $mark['sep_after']."\n";
        }

        $linkpatt = $arg->linkpatt; // упростим

        $q_obj = get_queried_object();

        // может это архив пустой таксы?
        $ptype = null;
        if( empty($post) ){
            if( isset($q_obj->taxonomy) )
                $ptype = & $wp_post_types[ get_taxonomy($q_obj->taxonomy)->object_type[0] ];
        }
        else $ptype = & $wp_post_types[ $post->post_type ];

        // paged
        $arg->pg_end = '';
        if( ($paged_num = get_query_var('paged')) || ($paged_num = get_query_var('page')) )
            $arg->pg_end = $sep . sprintf( $loc->paged, (int) $paged_num );

        $pg_end = $arg->pg_end; // упростим

        // ну, с богом...
        $out = '';

        if( is_front_page() ){
            return $arg->on_front_page ? sprintf( $wrappatt, ( $paged_num ? sprintf($linkpatt, get_home_url(), $loc->home) . $pg_end : $loc->home ) ) : '';
        }
        // страница записей, когда для главной установлена отдельная страница.
        elseif( is_home() ) {
            $out = $paged_num ? ( sprintf( $linkpatt, get_permalink($q_obj), esc_html($q_obj->post_title) ) . $pg_end ) : esc_html($q_obj->post_title);
        }
        elseif( is_404() ){
            $out = $loc->_404;
        }
        elseif( is_search() ){
            $out = sprintf( $loc->search, esc_html( $GLOBALS['s'] ) );
        }
        elseif( is_author() ){
            $tit = sprintf( $loc->author, esc_html($q_obj->display_name) );
            $out = ( $paged_num ? sprintf( $linkpatt, get_author_posts_url( $q_obj->ID, $q_obj->user_nicename ) . $pg_end, $tit ) : $tit );
        }
        elseif( is_year() || is_month() || is_day() ){
            $y_url  = get_year_link( $year = get_the_time('Y') );

            if( is_year() ){
                $tit = sprintf( $loc->year, $year );
                $out = ( $paged_num ? sprintf($linkpatt, $y_url, $tit) . $pg_end : $tit );
            }
            // month day
            else {
                $y_link = sprintf( $linkpatt, $y_url, $year);
                $m_url  = get_month_link( $year, get_the_time('m') );

                if( is_month() ){
                    $tit = sprintf( $loc->month, get_the_time('F') );
                    $out = $y_link . $sep . ( $paged_num ? sprintf( $linkpatt, $m_url, $tit ) . $pg_end : $tit );
                }
                elseif( is_day() ){
                    $m_link = sprintf( $linkpatt, $m_url, get_the_time('F'));
                    $out = $y_link . $sep . $m_link . $sep . get_the_time('l');
                }
            }
        }
        // Древовидные записи
        elseif( is_singular() && $ptype->hierarchical ){
            $out = $this->_add_title( $this->_page_crumbs($post), $post );
        }
        // Таксы, плоские записи и вложения
        else {
            $term = $q_obj; // таксономии

            // определяем термин для записей (включая вложения attachments)
            if( is_singular() ){
                // изменим $post, чтобы определить термин родителя вложения
                if( is_attachment() && $post->post_parent ){
                    $save_post = $post; // сохраним
                    $post = get_post($post->post_parent);
                }

                // учитывает если вложения прикрепляются к таксам древовидным - все бывает :)
                $taxonomies = get_object_taxonomies( $post->post_type );
                // оставим только древовидные и публичные, мало ли...
                $taxonomies = array_intersect( $taxonomies, get_taxonomies( array('hierarchical' => true, 'public' => true) ) );

                if( $taxonomies ){
                    // сортируем по приоритету
                    if( ! empty($arg->priority_tax) ){
                        usort( $taxonomies, function($a,$b)use($arg){
                            $a_index = array_search($a, $arg->priority_tax);
                            if( $a_index === false ) $a_index = 9999999;

                            $b_index = array_search($b, $arg->priority_tax);
                            if( $b_index === false ) $b_index = 9999999;

                            return ( $b_index === $a_index ) ? 0 : ( $b_index < $a_index ? 1 : -1 ); // меньше индекс - выше
                        } );
                    }

                    // пробуем получить термины, в порядке приоритета такс
                    foreach( $taxonomies as $taxname ){
                        if( $terms = get_the_terms( $post->ID, $taxname ) ){
                            // проверим приоритетные термины для таксы
                            $prior_terms = & $arg->priority_terms[ $taxname ];
                            if( $prior_terms && count($terms) > 2 ){
                                foreach( (array) $prior_terms as $term_id ){
                                    $filter_field = is_numeric($term_id) ? 'term_id' : 'slug';
                                    $_terms = wp_list_filter( $terms, array($filter_field=>$term_id) );

                                    if( $_terms ){
                                        $term = array_shift( $_terms );
                                        break;
                                    }
                                }
                            }
                            else
                                $term = array_shift( $terms );

                            break;
                        }
                    }
                }

                if( isset($save_post) ) $post = $save_post; // вернем обратно (для вложений)
            }

            // вывод

            // все виды записей с терминами или термины
            if( $term && isset($term->term_id) ){
                $term = apply_filters('kama_breadcrumbs_term', $term );

                // attachment
                if( is_attachment() ){
                    if( ! $post->post_parent )
                        $out = sprintf( $loc->attachment, esc_html($post->post_title) );
                    else {
                        if( ! $out = apply_filters('attachment_tax_crumbs', '', $term, $this ) ){
                            $_crumbs    = $this->_tax_crumbs( $term, 'self' );
                            $parent_tit = sprintf( $linkpatt, get_permalink($post->post_parent), get_the_title($post->post_parent) );
                            $_out = implode( $sep, array($_crumbs, $parent_tit) );
                            $out = $this->_add_title( $_out, $post );
                        }
                    }
                }
                // single
                elseif( is_single() ){
                    if( ! $out = apply_filters('post_tax_crumbs', '', $term, $this ) ){
                        $_crumbs = $this->_tax_crumbs( $term, 'self' );
                        $out = $this->_add_title( $_crumbs, $post );
                    }
                }
                // не древовидная такса (метки)
                elseif( ! is_taxonomy_hierarchical($term->taxonomy) ){
                    // метка
                    if( is_tag() )
                        $out = $this->_add_title('', $term, sprintf( $loc->tag, esc_html($term->name) ) );
                    // такса
                    elseif( is_tax() ){
                        $post_label = $ptype->labels->name;
                        $tax_label = $GLOBALS['wp_taxonomies'][ $term->taxonomy ]->labels->name;
                        $out = $this->_add_title('', $term, sprintf( $loc->tax_tag, $post_label, $tax_label, esc_html($term->name) ) );
                    }
                }
                // древовидная такса (рибрики)
                else {
                    if( ! $out = apply_filters('term_tax_crumbs', '', $term, $this ) ){
                        $_crumbs = $this->_tax_crumbs( $term, 'parent' );
                        $out = $this->_add_title( $_crumbs, $term, esc_html($term->name) );                     
                    }
                }
            }
            // влоежния от записи без терминов
            elseif( is_attachment() ){
                $parent = get_post($post->post_parent);
                $parent_link = sprintf( $linkpatt, get_permalink($parent), esc_html($parent->post_title) );
                $_out = $parent_link;

                // вложение от записи древовидного типа записи
                if( is_post_type_hierarchical($parent->post_type) ){
                    $parent_crumbs = $this->_page_crumbs($parent);
                    $_out = implode( $sep, array( $parent_crumbs, $parent_link ) );
                }

                $out = $this->_add_title( $_out, $post );
            }
            // записи без терминов
            elseif( is_singular() ){
                $out = $this->_add_title( '', $post );
            }
        }

        // замена ссылки на архивную страницу для типа записи
        $home_after = apply_filters('kama_breadcrumbs_home_after', '', $linkpatt, $sep, $ptype );

        if( '' === $home_after ){
            // Ссылка на архивную страницу типа записи для: отдельных страниц этого типа; архивов этого типа; таксономий связанных с этим типом.
            if( $ptype && $ptype->has_archive && ! in_array( $ptype->name, array('post','page','attachment') )
                && ( is_post_type_archive() || is_singular() || (is_tax() && in_array($term->taxonomy, $ptype->taxonomies)) )
            ){
                $pt_title = $ptype->labels->name;

                // первая страница архива типа записи
                if( is_post_type_archive() && ! $paged_num )
                    $home_after = $pt_title;
                // singular, paged post_type_archive, tax
                else{
                    $home_after = sprintf( $linkpatt, get_post_type_archive_link($ptype->name), $pt_title );

                    $home_after .= ( ($paged_num && ! is_tax()) ? $pg_end : $sep ); // пагинация
                }
            }
        }

        $before_out = sprintf( $linkpatt, home_url(), $loc->home ) . ( $home_after ? $sep.$home_after : ($out ? $sep : '') );

        $out = apply_filters('kama_breadcrumbs_pre_out', $out, $sep, $loc, $arg );

        $out = sprintf( $wrappatt, $before_out . $out );

        return apply_filters('kama_breadcrumbs', $out, $sep, $loc, $arg );
    }

    function _page_crumbs( $post ){
        $parent = $post->post_parent;

        $crumbs = array();
        while( $parent ){
            $page = get_post( $parent );
            $crumbs[] = sprintf( $this->arg->linkpatt, get_permalink($page), esc_html($page->post_title) );
            $parent = $page->post_parent;
        }

        return implode( $this->arg->sep, array_reverse($crumbs) );
    }

    function _tax_crumbs( $term, $start_from = 'self' ){
        $termlinks = array();
        $term_id = ($start_from === 'parent') ? $term->parent : $term->term_id;
        while( $term_id ){
            $term       = get_term( $term_id, $term->taxonomy );
            $termlinks[] = sprintf( $this->arg->linkpatt, get_term_link($term), esc_html($term->name) );
            $term_id    = $term->parent;
        }

        if( $termlinks )
            return implode( $this->arg->sep, array_reverse($termlinks) ) /*. $this->arg->sep*/;
        return '';
    }

    // добалвяет заголовок к переданному тексту, с учетом всех опций. Добавляет разделитель в начало, если надо.
    function _add_title( $add_to, $obj, $term_title = '' ){
        $arg = & $this->arg; // упростим...
        $title = $term_title ? $term_title : esc_html($obj->post_title); // $term_title чиститься отдельно, теги моугт быть...
        $show_title = $term_title ? $arg->show_term_title : $arg->show_post_title;

        // пагинация
        if( $arg->pg_end ){
            $link = $term_title ? get_term_link($obj) : get_permalink($obj);
            $add_to .= ($add_to ? $arg->sep : '') . sprintf( $arg->linkpatt, $link, $title ) . $arg->pg_end;
        }
        // дополняем - ставим sep
        elseif( $add_to ){
            if( $show_title )
                $add_to .= $arg->sep . sprintf( $arg->title_patt, $title );
            elseif( $arg->last_sep )
                $add_to .= $arg->sep;
        }
        // sep будет потом...
        elseif( $show_title )
            $add_to = sprintf( $arg->title_patt, $title );

        return $add_to;
    }

}

/**
 * Изменения:
 * 3.3 - новые хуки: attachment_tax_crumbs, post_tax_crumbs, term_tax_crumbs. Позволяют дополнить крошки таксономий.
 * 3.2 - баг с разделителем, с отключенным 'show_term_title'. Стабилизировал логику.
 * 3.1 - баг с esc_html() для заголовка терминов - с тегами получалось криво...
 * 3.0 - Обернул в класс. Добавил опции: 'title_patt', 'last_sep'. Доработал код. Добавил пагинацию для постов.
 * 2.5 - ADD: Опция 'show_term_title'
 * 2.4 - Мелкие правки кода
 * 2.3 - ADD: Страница записей, когда для главной установлена отделенная страница.
 * 2.2 - ADD: Link to post type archive on taxonomies page
 * 2.1 - ADD: $sep, $loc, $args params to hooks
 * 2.0 - ADD: в фильтр 'kama_breadcrumbs_home_after' добавлен четвертый аргумент $ptype
 * 1.9 - ADD: фильтр 'kama_breadcrumbs_default_loc' для изменения локализации по умолчанию
 * 1.8 - FIX: заметки, когда в рубрике нет записей
 * 1.7 - Улучшена работа с приоритетными таксономиями.
 */
// end KAMA BC

// TGM Script code

add_action( 'tgmpa_register', 'aletheme_register_required_plugins' );
function aletheme_register_required_plugins() {

    /**
     * Array of plugin arrays. Required keys are name and slug.
     * If the source is NOT from the .org repo, then source is also required.
     */
    $plugins = array(

        //Example Init
        array(
            'name'      => 'Akismet',
            'slug'      => 'akismet',
            'required'  => false,
        ),
    );

    // Change this to your theme text domain, used for internationalising strings
    $theme_text_domain = 'aletheme';

    /**
     * Array of configuration settings. Amend each line as needed.
     * If you want the default strings to be available under your own theme domain,
     * leave the strings uncommented.
     * Some of the strings are added into a sprintf, so see the comments at the
     * end of each line for what each argument will be.
     */
    $config = array(
        'domain'         => $theme_text_domain,          // Text domain - likely want to be the same as your theme.
        'default_path'   => '',                          // Default absolute path to pre-packaged plugins
        'parent_menu_slug'  => 'themes.php',     // Default parent menu slug
        'parent_url_slug'  => 'themes.php',     // Default parent URL slug
        'menu'           => 'install-required-plugins',  // Menu slug
        'has_notices'       => true,                        // Show admin notices or not
        'is_automatic'     => false,         // Automatically activate plugins after installation or not
        'message'    => '',       // Message to output right before the plugins table
        'strings'        => array(
            'page_title'                          => __( 'Install Required Plugins', $theme_text_domain ),
            'menu_title'                          => __( 'Install Plugins', $theme_text_domain ),
            'installing'                          => __( 'Installing Plugin: %s', $theme_text_domain ), // %1$s = plugin name
            'oops'                                => __( 'Something went wrong with the plugin API.', $theme_text_domain ),
            'notice_can_install_required'        => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.' ), // %1$s = plugin name(s)
            'notice_can_install_recommended'   => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.' ), // %1$s = plugin name(s)
            'notice_cannot_install'       => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.' ), // %1$s = plugin name(s)
            'notice_can_activate_required'       => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s)
            'notice_can_activate_recommended'   => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s)
            'notice_cannot_activate'      => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.' ), // %1$s = plugin name(s)
            'notice_ask_to_update'       => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.' ), // %1$s = plugin name(s)
            'notice_cannot_update'       => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.' ), // %1$s = plugin name(s)
            'install_link'           => _n_noop( 'Begin installing plugin', 'Begin installing plugins' ),
            'activate_link'          => _n_noop( 'Activate installed plugin', 'Activate installed plugins' ),
            'return'                              => __( 'Return to Required Plugins Installer', $theme_text_domain ),
            'plugin_activated'                    => __( 'Plugin activated successfully.', $theme_text_domain ),
            'complete'          => __( 'All plugins installed and activated successfully. %s', $theme_text_domain ), // %1$s = dashboard link
            'nag_type'         => 'updated' // Determines admin notice type - can only be 'updated' or 'error'
        )
    );

    tgmpa( $plugins, $config );

}