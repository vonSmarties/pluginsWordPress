<?php

/**
 * Widget qui permet d'afficher les drives
 */
class Restaurant_Widget extends WP_Widget {
	// Constructeur de la classe
	function __construct()
	{
		parent::__construct(
			'restaurants_widget', // Identifiant de la widget
			'Restaurants', // Nom qui apparaîtra dans l'interface d'administration
			array('description' => __( 'Affiche le nom des restaurants')) // Description
		);
	}

	// Fonction update
	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;
		$instance['title'] = sanitize_text_field($new_instance['title']);
		$instance['nb_posts'] = (int) $new_instance['nb_posts'];
		return $instance;
	}

	// Fonction qui permet d'afficher le formulaire de la Widget
	function form( $instance )
	{
		if( $instance)
		{
			$title = esc_attr( $instance['title'] );
			$nb_posts = $instance['nb_posts'];
		}
		else
		{
			$title = '';
			$nb_posts = 5;
		}
		?>
			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>">Titre</label>
				<input
					class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
					name="<?php echo $this->get_field_name('title'); ?>"
					type="text" value="<?php echo $title; ?>"
				/>
			</p>
			<p>
			 	<label for="<?php echo $this->get_field_id( 'nb_posts' ); ?>">Nombre de restaurant à afficher</label>
          <input
						class="tiny-text" id="<?php echo $this->get_field_id( 'nb_posts' ); ?>"
          	name="<?php echo $this->get_field_name( 'nb_posts' ); ?>"
            type="number" step="1" min="1" value="<?php echo $nb_posts; ?>" size="3"
					/>
      </p>
		<?php
	}

	// Fonction qui gère l'affichage de la Widget sur le site
	function widget( $args, $instance ){
		// On vérifie que le paramètre widget_id est présent
      if ( ! isset( $args['widget_id'] ) ) {
        $args['widget_id'] = $this->id;
      }

      // On récupère le titre de la widget
		$title = $instance['title'];

		// On récupère le nombre de posts à afficher
		$nb_posts = $instance['nb_posts'];

    // On applique le filtre widget_title
    $title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

    // On récupère les restaurants
    $query = new WP_Query(
			apply_filters(
				'widget_posts_args', array(
	        'post_type'         => 'restaurant',
	        'no_found_rows'     => true,
	        'post_status'       => 'publish',
	        'posts_per_page'	  => $nb_posts,
	        'orderby' 			  	=> 'rand'
    		)
			)
		);
    if ( $query->have_posts() ){
      ?>
      <?php echo $args['before_widget']; ?>
      <?php if ( $title ) {
        echo $args['before_title'] . $title . $args['after_title'];
    	} ?>
      <ul>
      <?php while ( $query->have_posts() ) : $query->the_post(); ?>
        <li>
          <a href="#" class="titre-restaurant" data-id="<?php the_ID(); ?>">
						<?php the_title(); ?>
					</a>
        </li>
      <?php endwhile; ?>
      </ul>
			<div id="detail">

			</div>
      <?php echo $args['after_widget']; ?>
    	<?php
    	// On réinitialise la variable globale $post car la requète l'a alterée
    	wp_reset_postdata();

    }
  }
} //end class Restaurant_Widget


function register_restaurant_widget() {
  register_widget('Restaurant_Widget');
}
add_action('widgets_init', 'register_restaurant_widget');
