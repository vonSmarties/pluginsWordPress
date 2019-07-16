<?php
/**
 * Template de l'affichage des restaurants
 */

get_header();
?>

	<section id="primary" class="content-area">
		<main id="main" class="site-main">

			<?php

			/* Start the Loop */
			while ( have_posts() ) :
				the_post();

				//get_template_part( 'template-parts/content/content', 'single' );
        the_title('<h2>','</h2>');

        the_content();

        $rpf_horaires=get_post_meta($post->ID, 'rpf_horaires', true );
        $rpf_adresse=get_post_meta($post->ID, 'rpf_adresse', true );
        $rpf_cp=get_post_meta($post->ID, 'rpf_cp', true );
        $rpf_ville=get_post_meta($post->ID, 'rpf_ville', true );
        $rpf_tel=get_post_meta($post->ID, 'rpf_tel', true );

        ?>
        <p>Horaires : <?php echo $rpf_horaires; ?></p>
        <p>Adresse : <?php echo $rpf_adresse.' '.$rpf_cp.' '.$rpf_ville; ?></p>
        <p>Téléphone : <?php echo $rpf_tel; ?></p>
				<p>Type de vente : <?php the_terms($post->ID,'type_vente'); ?></p>
        <?php

			endwhile; // End of the loop.
			?>

		</main><!-- #main -->
	</section><!-- #primary -->

<?php
get_footer();
