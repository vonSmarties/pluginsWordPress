<?php
/**
 *
 *
 *
 */
/*
Plugin Name: firstPlugIn
Description: wait and see !!!
Version: 1000
Author: le coté obscur
*/

//ajout de contenue au debut des articles
function fpiAjouterContenue($contenue){
  $contenue='<p>petite licorne arc en ciel</p>'.$contenue;
  return $contenue;
}
add_filter('the_content','fpiAjouterContenue');

//afficher un message dans l'interface admin
function fpiAfficherMessage(){
  echo '<b>unicorn rules</b>';
}
//add_action('admin_notices','fpiAfficherMessage');

//initialisation des shortcodes
function fpiInitShortcode(){

  //renvoie le contenue afficher à la place du shortcode
  function fpiTraiterShortcode($atts=[],$contenue=null){

    //mise en forme des clefs du tableau $atts
    $atts=array_change_key_case((array) $atts,CASE_LOWER);

    //defintion des valeurs par défaut des paramètres
    $atts=shortcode_atts(['titre'=>'Unicorn'],$atts);

    return 'le titre du shortcode est : '.$atts['titre'];
  }
  add_shortcode('monShortcode','fpiTraiterShortCode');

  function fpiAjouterCarte($params){

    //mise en forme des clefs du tableau $atts
    $params=array_change_key_case((array) $params,CASE_LOWER);

    //defintion des valeurs par défaut des paramètres
    $params=shortcode_atts(['largeur'=>400,'hauteur'=>600],$params);

    return '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2902.6864455942136!2d-0.369115685209326!3d43.32082288187888!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd5648afb93c9f3d%3A0xbd2043ae2d7897be!2sTechnopole+H%C3%A9lioparc!5e0!3m2!1sfr!2sfr!4v1561471862282!5m2!1sfr!2sfr" width="'.$params['largeur'].'" height="'.$params['hauteur'].'" frameborder="0" style="border:0" allowfullscreen></iframe>';
  }
  add_shortcode('carte','fpiAjouterCarte');
}
add_action('init','fpiInitShortcode');

function fpi_setting_init(){

  //ajout de la section dans la pages réglage généraux
  add_settings_section(
    'fpi_section_param_carte', //id unique de la section
    'paramètres de la carte', //titre de la section affiché sur la mb_substitute_character
    'fpi_section_param_carte_description', //nom de la fonction qui affiche la description de la fonction
    'fpi_param_carte' //id de la page sur laquelle la section sera affichée
  );

  //ajout du champ latitude
  add_settings_field(
    'fpi_latitude',//id champ
    'latitude',//titre champ
    'fpi_latitude_html',//nom de la fonction d'affichage
    'fpi_param_carte',//id de la page d'affichage
    'fpi_section_param_carte'// id de la section d'affichage
  );

  register_setting(
    'fpi_param_carte',//id page d'affichage
    'fpi_latitude'//id champ
  );

  //ajout du champ longitude
  add_settings_field(
    'fpi_longitude',//id champ
    'longitude',//titre champ
    'fpi_longitude_html',//nom de la fonction d'affichage
    'fpi_param_carte',//id de la page d'affichage
    'fpi_section_param_carte'// id de la section d'affichage
  );

  register_setting(
    'fpi_param_carte',//id page d'affichage
    'fpi_longitude'//id champ
  );

}
add_action('admin_init','fpi_setting_init');

function fpi_section_param_carte_description(){
  echo 'veuillez saisir la latitude et la longitude';
}

function fpi_latitude_html(){
  echo '<input type="text" id="fpi_latitude" name="fpi_latitude" value="'.get_option('fpi_latitude').'"/></br>latitude du point à afficher';
}

function fpi_longitude_html(){
  echo '<input type="text" id="fpi_longitude" name="fpi_longitude" value="'.get_option('fpi_longitude').'"/></br>longitude du point à afficher';
}

//creation d'une nouvelle page dans l'adminstration
function fpi_option_page(){
  add_menu_page(
    'Paramétrage de la carte', //titre onglet
    'option firstPlugIn',//libéllé menu
    'manage_options',//permission nécessaire
    'fpi_param_carte',//slug ou id qui apparait dans la page
    'fpi_param_carte_html',//nom de la fonction qui affiche le contenu de la page
    'dashicons-location-alt',//icone wp
    20 //position dans le menu : plus il est grand plus la position sera basse
  );
}
add_action('admin_menu','fpi_option_page');

function  fpi_param_carte_html(){
  echo "jusqu'ici tout va bien";
  ?>
  <div class="wrap">
    <h2>option de firstPlugIn</h2>

    <form action="option.php" method="post">

      <?php
      settings_fields('fpi_param_carte');
      do_settings_sections('fpi_param_carte');
      submit_button();
      ?>

    </form>
  </div>
  <?php
}
