<?php
/**
 *
 *
 *
 */
/*
Plugin Name: Restaurants pizza figaro
Description: Extension qui permet de gérer les restaurants
Version: 1000
Author: le coté obscur
*/

require_once('widget_restaurants.php');
// include_once plugin_dir_path(__FILE__).'/widget_restaurants.php';

/*
 * Fonction appelée lors de l'init pour créer le custom post type
 */
function rpf_register_custom_post_type()
{
	// Enregistrement du custom post type restaurant
    register_post_type( 'restaurant' ,
       [
           'labels'     => [
				'name'                  => __('Restaurants','restaurant-pizza-figaro'), // Titre du menu et de la page
				'singular_name'         => __('Restaurant','restaurant-pizza-figaro'), // Titre au singulier
				'add_new'               => __('Ajouter un nouveau restaurant','restaurant-pizza-figaro'), //Titre du menu
				'add_new_item'          => __('Ajouter un restaurant','restaurant-pizza-figaro'), //Titre de la page d'ajout
				'edit'                  => __('Modifier','restaurant-pizza-figaro'),
				'edit_item'             => __('Modifier un restaurant','restaurant-pizza-figaro'),
				'new_item'              => __('Nouveau restaurant','restaurant-pizza-figaro'),
				'view'                  => __('Voir','restaurant-pizza-figaro'),
				'view_item'             => __('Voir le restaurant','restaurant-pizza-figaro'),
				'search_items'          => __('Rechercher un restaurant','restaurant-pizza-figaro'),
				'not_found'             => __('Aucun restaurant trouvé','restaurant-pizza-figaro'),
				'not_found_in_trash'    => __('Aucun restaurant trouvé dans la corbeille','restaurant-pizza-figaro')
           ],
           'public'     => true, // Définit si le type de post doit être visible
           'has_archive'=> true, // Définit si le type de post dispoe de page d'archives
           'rewrite'    => ['slug' => 'restaurant'], // slug
           'menu_icon'	=> 'dashicons-location-alt', //icone
           'supports' 	=> array('title', 'editor', 'thumbnail'), // champs par défaut
           'register_meta_box_cb' => 'rpf_add_restaurant_metaboxes' //nom de la fonction qui affiche la metabox
       ]
    );

    // Enregistrement d'une taxonomie personnalisée
    register_taxonomy('type_vente', 'restaurant', array(
        'labels' => array(
            'name'              => __( 'Types de vente','restaurant-pizza-figaro'), // Titre de menu
            'add_new_item'      => __( 'Ajouter un type de vente','restaurant-pizza-figaro'),
            'new_item_name'     => __( 'Nouveau type de vente','restaurant-pizza-figaro')
        ),
        'hierarchical' => true,
        'query_var' => true,
        'rewrite' => true,
      )
    );
}

add_action('init', 'rpf_register_custom_post_type');

//  création de la boite meta détails des restaurants
function rpf_add_restaurant_metaboxes(){
  add_meta_box(
    'details_section_id', // Id
    'details', //titre meta
    'rpf_details_box', //nom de la fonction qui affiche le contenu de la boite meta
    'restaurant', //id du type de contenu
    'normal',//contexte
    'default' //priorité
  );
}

function rpf_details_box($post){
  echo "jusqu'ici tout va bien";

  //recupere des metadonnées
  $rpf_horaires=get_post_meta($post->ID, 'rpf_horaires', true );
  $rpf_adresse=get_post_meta($post->ID, 'rpf_adresse', true );
  $rpf_cp=get_post_meta($post->ID, 'rpf_cp', true );
  $rpf_ville=get_post_meta($post->ID, 'rpf_ville', true );
  $rpf_tel=get_post_meta($post->ID, 'rpf_tel', true );

  wp_nonce_field('rpf_restaurants_fields','rpf_restaurant_nonce');

  ?>
  <p class="meta-options">
    <label for="rpf_horaires">horaire</label><br/>
    <textarea id="rpf_horaires" name="rpf_horaires" rows="3" cols="69"><?php echo $rpf_horaires; ?></textarea><br/>
    <label for="rpf_adresse">Adresse</label>
    <input type="text" id="rpf_adresse" name="rpf_adresse" value="<?php echo $rpf_adresse ?>"><br/>
    <label for="rpf_cp">Code postal</label>
    <input type="text" id="rpf_cp" name="rpf_cp" value="<?php echo $rpf_cp ?>"><br/>
    <label for="rpf_ville">Ville</label>
    <input type="text" id="rpf_ville" name="rpf_ville" value="<?php echo $rpf_ville ?>"><br/>
    <label for="rpf_tel">Téléphone</label>
    <input type="text" id="rpf_tel" name="rpf_tel" value="<?php echo $rpf_tel ?>"><br/>
  </p>
  <?php
}


function rpf_save_restaurant_post( $post_ID )
{
    // On vérifie si nous ne sommmes dans une sauvegarde automatique de WordPress
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
        return ;

    // On vérifie le nonce
    if ( !isset( $_POST['rpf_restaurant_nonce'] ) || !wp_verify_nonce( $_POST['rpf_restaurant_nonce'], 'rpf_restaurants_fields' ) )
        return ;

    // On vérifie si l'utilisateur peut modifier un Post
    if ( !current_user_can( 'edit_post', $post_ID ) )
        return;

    // On enregistre les métadonnées si elles existent
    if( array_key_exists('rpf_horaires', $_POST) )
    {
        // On sécurise la valeur de la métadonnée avec la fonction wp_kses_post
        update_post_meta( $post_ID, 'rpf_horaires', wp_kses_post( $_POST['rpf_horaires'] ) );
    }

    if( array_key_exists('rpf_adresse', $_POST) )
    {
        // On sécurise la valeur de la métadonnée avec la fonction sanitize_text_field
        update_post_meta( $post_ID, 'rpf_adresse', sanitize_text_field( $_POST['rpf_adresse'] ) );
    }

    if( array_key_exists('rpf_cp', $_POST) )
    {
        // On sécurise la valeur de la métadonnée avec la fonction sanitize_text_field
        update_post_meta( $post_ID, 'rpf_cp', sanitize_text_field( $_POST['rpf_cp'] ) );
    }

    if( array_key_exists('rpf_ville', $_POST) )
    {
        // On sécurise la valeur de la métadonnée avec la fonction sanitize_text_field
        update_post_meta( $post_ID, 'rpf_ville', sanitize_text_field( $_POST['rpf_ville'] ) );
    }

    if( array_key_exists('rpf_tel', $_POST) )
    {
        // On sécurise la valeur de la métadonnée avec la fonction sanitize_text_field
        update_post_meta( $post_ID, 'rpf_tel', sanitize_text_field( $_POST['rpf_tel'] ) );
    }

    return ;
}

add_action( 'save_post', 'rpf_save_restaurant_post' );

/*
 * Fonction qui permet de définir le template
 */
function get_restaurant_template( $single_template )
{
  global $post;

  // Si le post est du type restaurant, nous utilisons un template du plugin
  if ($post->post_type == 'restaurant')
  {
  // Le nom du fichier du template dans le theme
    $template_name = 'single-restaurant.php';

    // Si le template utilisé correspond au template défini dans le thème alors on n'utilise pas notre template
    if( $single_template === get_stylesheet_directory() . '/' . $template_name )
    {
      return $single_template;
    }

    $single_template = plugin_dir_path(__FILE__) . '/templates/restaurant.php';
  }
  return $single_template;
}
add_filter( 'single_template', 'get_restaurant_template' ) ;

//ajout de champs personnalisés dans la taxonomie type_vente
function rpf_type_vente_custom_fields(){
  //sécurité
  wp_nonce_field('add_type_vente_term_meta','add_type_vente_term_meta_nonce');
  ?>
  <div class="form-field">
    <label for="rpf_couleur_type_vente">couleur du titre de la catégorie</label>
    <input type="text" name="rpf_couleur_type_vente" id="rpf_couleur_type_vente" value="#692323">
  </div>
  <?php
}
add_action('type_vente_add_form_fields', 'rpf_type_vente_custom_fields');

//ajout de champs personnalisés pour modification dans la taxonomie type_vente
function rpf_type_vente_edit_custom_fields($term){
  //récupération de la métadonnée
  $couleur=get_term_meta($term->term_id,'rpf_couleur_type_vente',true);
  //sécurité
  wp_nonce_field('add_type_vente_term_meta','add_type_vente_term_meta_nonce');
  ?>
  <tr class="form-field">
    <th>
      <label for="rpf_couleur_type_vente">couleur du titre de la catégorie</label>
    </th>
    <td>
      <input type="text" name="rpf_couleur_type_vente" id="rpf_couleur_type_vente" value="<?php echo $couleur ?>">
    </td>
  </tr>
  <?php
}
add_action('type_vente_edit_form_fields', 'rpf_type_vente_edit_custom_fields');

function rpf_type_vente_save_custom_fields( $term_id ){

  if(
    !isset( $_POST['add_type_vente_term_meta_nonce'])||
    !wp_verify_nonce($_POST['add_type_vente_term_meta_nonce'],'add_type_vente_term_meta')||
    !isset($_POST['rpf_couleur_type_vente'])
  ){
    return;
  }
  $couleur=sanitize_text_field($_POST['rpf_couleur_type_vente']);

  update_term_meta($term_id,'rpf_couleur_type_vente',$couleur);

}
add_action('edit_type_vente','rpf_type_vente_save_custom_fields');
add_action('create_type_vente','rpf_type_vente_save_custom_fields');

/*
 * On ajoute le script restaurants.js
 */
function rpf_enqueue_restaurants_script( ){

  // On ajoute le scritp restaurants.js en le référençant sous le nom restaurants-script et en ajoutant comme dépendance jquery
  wp_enqueue_script(
    'restaurants-script',
    plugin_dir_url( __FILE__ ).'/js/restaurants.js',
    array( 'jquery' )
  );

  // On crée un nonce pour sécuriser nos actions
  $nonce = wp_create_nonce('restaurants_nonce');

  // On ajoute l'objet rpf_ajax_config pour le script référencé restaurants-script avec 2 paramètres ajax_url et nonce
  wp_localize_script(
    'restaurants-script',
    'rpf_ajax_config',
    array(
      'ajax_url' => admin_url( 'admin-ajax.php' ),
      'nonce'    => $nonce,
    )
  );
}
add_action( 'wp_enqueue_scripts', 'rpf_enqueue_restaurants_script' );

/*
 * Fonction qui va traiter l'appel AJAX rpf_get_restaurant_details
 * et envoyer les détails du restaurant à afficher
 */
function rpf_get_details_restaurant(){
  // On vérifie le nonce
  check_ajax_referer('restaurants_nonce');

  // Variable qui stockera le contenu à envoyer
  $content = '';

  // Requête pour récupérer les informations du restaurant
  $args = array(
    'p' => $_POST['id_restaurant'],
    'post_type' => 'restaurant'
  );
  $query = new WP_Query( $args );

  // On parcours la boucle WordPress (ici une seule fois puisque nous n'avons qu'un seul restaurant)
  while ( $query->have_posts() ) :
    $query->the_post();
    $post_id  = get_the_ID();
    // On ajoutes les informations dans la variable $content
    $content .= "Horaires: " . get_post_meta( $post_id, 'rpf_horaires', true) . '<br>';
    $content .= "Adresse: <br> " . get_post_meta( $post_id, 'rpf_adresse', true) . '<br>';
    $content .= get_post_meta( $post_id, 'rpf_cp', true) . ' ' . get_post_meta( $post_id, 'rpf_ville', true) . '<br>';
    $content .= "Description: " . get_the_content();
  endwhile;

  // On envoie le contenu au format JSON
  wp_send_json( $content );

}
// On ajoute l'action pour les utilisateurs qui ne sont pas loggés
add_action( 'wp_ajax_nopriv_rpf_get_details_restaurant', 'rpf_get_details_restaurant' );
// On ajoute l'action pour les utilisateurs qui ne sont pas loggés
add_action( 'wp_ajax_rpf_get_details_restaurant', 'rpf_get_details_restaurant' );
