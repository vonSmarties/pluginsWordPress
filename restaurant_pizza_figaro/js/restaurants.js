jQuery( document ).ready( function( $ ){

  //code à executer
  $('.titre-restaurant').click( function( event ) {
    //fonction qui sera executée lors d'un clic sur un élément ayant la classe titre-restaurant
    event.preventDefault();

    $.post(
      rpf_ajax_config.ajax_url,
      {     // requete POST
        _ajax_nonce : rpf_ajax_config.nonce , // nonce
        action     : 'rpf_get_details_restaurant', // action
        id_restaurant :  $(this).attr('data-id')  // identifiant du restaurant cliqué
      },
      function( content ){
        //fonction qui sera executée lorsque le navigateur aura recu les données
        $('#detail').html( content );
      }
    ); // fin de $.post
  }); // fin de
});
