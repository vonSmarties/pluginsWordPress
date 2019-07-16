<?php
/**
 *
 *
 *
 */
/*
Plugin Name: bigorra voyages
Description: Extension qui permet de gérer les voyages proposés par Bigorra voyage
Version: 1.0
Author: PEREZ Thomas
*/

/*
 * Fonction appelée lors de l'init pour créer le custom post type
 */
function tpbv_register_custom_post_type()
{
	// Enregistrement du custom post type voyage
  register_post_type( 'voyage' ,
    [
      'labels'     => [
    		'name'                  => __('Voyages','bigorra voyages'), // Titre du menu et de la page
    		'singular_name'         => __('Voyage','bigorra voyages'), // Titre au singulier
    		'add_new'               => __('Ajouter un nouveau voyage','bigorra voyages'), //Titre du menu
    		'add_new_item'          => __('Ajouter un voyage','bigorra voyages'), //Titre de la page d'ajout
    		'edit'                  => __('Modifier','bigorra voyages'),
    		'edit_item'             => __('Modifier un voyage','bigorra voyages'),
    		'new_item'              => __('Nouveau voyage','bigorra voyages'),
    		'view'                  => __('Voir','bigorra voyages'),
    		'view_item'             => __('Voir le voyage','bigorra voyages'),
    		'search_items'          => __('Rechercher un voyage','bigorra voyages'),
    		'not_found'             => __('Aucun voyage trouvé','bigorra voyages'),
    		'not_found_in_trash'    => __('Aucun voyage trouvé dans la corbeille','bigorra voyages')
      ],
      'public'     => true, // Définit si le type de post doit être visible
      'has_archive'=> true, // Définit si le type de post dispoe de page d'archives
      'rewrite'    => ['slug' => 'voyages'], // slug
      'menu_icon'	=> 'dashicons-location-alt', //icone
      'supports' 	=> array('title', 'editor', 'thumbnail'), // champs par défaut
      'register_meta_box_cb' => 'tpbv_add_voyage_metaboxes' //nom de la fonction qui affiche la metabox
    ]
  );
}

add_action('init', 'tpbv_register_custom_post_type');

//  création de la boite meta détails des voyages
function tpbv_add_voyage_metaboxes(){
  add_meta_box(
    'details_section_id', // Id
    'details', //titre meta
    'tpbv_details_box', //nom de la fonction qui affiche le contenu de la boite meta
    'voyage', //id du type de contenu
    'normal',//contexte
    'default' //priorité
  );
}

function tpbv_details_box($post){
  echo "jusqu'ici tout va bien";

  //recupere des metadonnées
  $tpbv_tarifs=get_post_meta($post->ID, 'tpbv_tarifs', true );
  $tpbv_durees_dates=get_post_meta($post->ID, 'tpbv_durees_dates', true );

  wp_nonce_field('tpbv_voyages_fields','tpbv_voyage_nonce');

  ?>
  <p class="meta-options">
    <label for="tpbv_tarifs">tarifs</label>
    <input type="text" id="tpbv_tarifs" name="tpbv_tarifs" value="<?php echo $tpbv_tarifs ?>"><br/>
    <label for="tpbv_durees_dates">durée et dates</label>
    <input type="text" id="tpbv_durees_dates" name="tpbv_durees_dates" value="<?php echo $tpbv_durees_dates ?>">
  </p>
  <?php
}


function tpbv_save_voyage_post( $post_ID )
{
  // On vérifie si nous ne sommmes dans une sauvegarde automatique de WordPress
  if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
    return ;

  // On vérifie le nonce
  if ( !isset( $_POST['tpbv_voyage_nonce'] ) || !wp_verify_nonce( $_POST['tpbv_voyage_nonce'], 'tpbv_voyages_fields' ) )
    return ;

  // On vérifie si l'utilisateur peut modifier un Post
  if ( !current_user_can( 'edit_post', $post_ID ) )
    return;

  // On enregistre les métadonnées si elles existent
  if( array_key_exists('tpbv_tarifs', $_POST) )
  {
    // On sécurise la valeur de la métadonnée avec la fonction wp_kses_post
    update_post_meta( $post_ID, 'tpbv_tarifs', wp_kses_post( $_POST['tpbv_tarifs'] ) );
  }

  if( array_key_exists('tpbv_durees_dates', $_POST) )
  {
    // On sécurise la valeur de la métadonnée avec la fonction sanitize_text_field
    update_post_meta( $post_ID, 'tpbv_durees_dates', sanitize_text_field( $_POST['tpbv_durees_dates'] ) );
  }

  return ;
}

add_action( 'save_post', 'tpbv_save_voyage_post' );

/*
 * Fonction qui permet de définir le template
 */
function get_voyage_template( $single_template )
{
  global $post;

  // Si le post est du type voyage, nous utilisons un template du plugin
  if ($post->post_type == 'voyage')
  {
  // Le nom du fichier du template dans le theme
    $template_name = 'single-voyage.php';

    // Si le template utilisé correspond au template défini dans le thème alors on n'utilise pas notre template
    if( $single_template === get_stylesheet_directory() . '/' . $template_name )
    {
      return $single_template;
    }

    $single_template = plugin_dir_path(__FILE__) . '/templates/voyage.php';
  }
  return $single_template;
}
add_filter( 'single_template', 'get_voyage_template' ) ;
