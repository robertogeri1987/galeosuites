<?php

/**
 * Template part for displaying the footer content
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package ap-wp-theme-tw
 */

?>

<footer id="colophon" class="bg-black text-white" data-main-footer>
	<div class="layout-container mx-auto grid md:grid-cols-12 gap-gap pt-2gap md:pt-3gap">

		<?php do_action( 'ap_wp_before_footer_widgets_area' ); ?>

		<div class="grid grid-cols-1 md:grid-cols-7 gap-gap md:pb-4gap md:col-span-9">
			<div class="md:col-span-2">
				<img width="143" height="63" class="mdd:w-[120px] h-auto max-w-[128px]" alt="Gramigna Logo Black" src="<?php echo get_stylesheet_directory_uri() . '/assets/images/logo-dark.svg'; ?>">
			</div>
			<div class="grid grid-cols-2 md:grid-cols-4 gap-gap md:col-span-5">
				<?php
				if ( have_rows( 'sezione_footer', 'options' ) ) :
					while ( have_rows( 'sezione_footer', 'options' ) ) :
						the_row();
						?>
						<div class="flex flex-col items-start justify-start gap-hgap">
							<?php
							if ( have_rows( 'voce_sezione', 'options' ) ) :
								while ( have_rows( 'voce_sezione', 'options' ) ) :
									the_row();
									$classes = ' text-xs hover:text-primary transition-all duration-300';
									if ( get_sub_field( 'testo_grigio' ) ) {
										$classes .= ' opacity-70';
									}
									if ( get_sub_field( 'testo_in_maiuscolo' ) ) {
										$classes .= ' uppercase';
									}
									new \Components\Link( 'link_footer', $classes, false );

								endwhile;
							endif;
							?>
						</div>
						<?php
					endwhile;
				endif;
				?>
			</div>
		</div>

		<div class="md:col-span-3 flex items-center justify-start md:flex-col md:items-end md:justify-start gap-gap">
			<a href="<?php echo get_field( 'facebook_link', 'options' ); ?>" rel="noopener" target="_blank"><?php ap_svg( 'facebook', 'Facebook', 'w-[18px] h-[18px] fill-white hover:fill-primary transition-all duration-300' ); ?></a>
			<a href="<?php echo get_field( 'instagram_link', 'options' ); ?>" rel="noopener" target="_blank"><?php ap_svg( 'instagram', 'instagram', 'w-[18px] h-[18px] fill-white hover:fill-primary transition-all duration-300' ); ?></a>
		</div>

		<?php
		$mailchimp = get_field( 'shortcode_mailchimp', 'options' );
		if ( $mailchimp ) :
			?>

			<div class="md:col-span-9 md:grid md:grid-cols-7 gap-gap custom-cf7-checkboxes mdd:pt-2gap">
				<div class="md:col-span-7 md:col-start-3 md:pl-hgap">
					<h3 class="base-text uppercase font-bold"><?php echo __( 'Iscriviti alla newsletter', 'ap-wp-theme' ); ?><span class="text-xs uppercase font-normal ml-hgap"> <?php echo __( 'Resta aggiornato su novità e racconti', 'ap-wp-theme' ); ?></span></h3>
					<?php echo do_shortcode( $mailchimp ); ?>
				</div>
			</div>

			<?php
		endif;
		?>

		<div class="footer__credits md:col-span-12 py-gap text-xs flex flex-col items-center justify-center md:flex-row mdd:text-center md:justify-between md:items-start gap-gap pt-2gap md:pt-4gap">
			<div>
				&copy;
				<?php
				echo date( 'Y' );
				echo 'AZIENDA AGRICOLA LA GRAMIGNA di Renata Conti<span class="mr-gap"></span>Via di Gricigliano 43, 50065 Pontassieve FI<span class="mr-gap"></span>P.IVA 05786730480';
				?>
			</div>
			<div>
				designed by <a href="https://www.lottstudio.com/" rel="noopener" target="_blank" class="font-bold">Lott Studio</a> developed by <a href="https://www.infloweb.it/" rel="noopener" target="_blank" class="font-bold">Infloweb</a>
			</div>
		</div>

	</div>
</footer><!-- #colophon -->
