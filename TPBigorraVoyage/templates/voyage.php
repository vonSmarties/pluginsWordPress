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

				$tpbv_tarifs=get_post_meta($post->ID, 'tpbv_tarifs', true );
			  $tpbv_durees_dates=get_post_meta($post->ID, 'tpbv_durees_dates', true );

				//get_template_part( 'template-parts/content/content', 'single' );
        the_title('<h2>','</h2>');

				?>
				<h4>A partir de <?php echo $tpbv_tarifs; ?></h4>
				<h4><?php echo $tpbv_durees_dates; ?></h4>
				<h4>Description / Programme</h4>
				<?php

        the_content();

			endwhile; // End of the loop.
			?>

		</main><!-- #main -->
	</section><!-- #primary -->

<?php
get_footer();
