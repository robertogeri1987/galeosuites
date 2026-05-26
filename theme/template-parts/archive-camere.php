<?php

/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package ap-wp-theme
 */
// If this file is called directly, abort.
if (! defined('WPINC')) {
	die;
}

$entry_link = get_the_permalink();
$terms      = get_the_terms(get_the_ID(), 'categorie_camere');
$more       = '&hellip;';

?>

<article class="block relative md:col-span-6 gap-gap">

	<a class="archive-post__link transition-all duration-500 hover:opacity-70"
		href="<?php echo esc_url($entry_link); ?>"
		aria-hidden="true"
		tabindex="-1">
		<?php \Classes\Core\Utils::SimpleFeaturedImg('full'); ?>
	</a>

	<section class="archive-post__entry-container">

		<?php if (! is_wp_error($terms) && ! empty($terms)) : ?>
			<div class="archive-post__categories block text-sm mt-gap">

			</div>
		<?php endif; ?>

		<h2 class="archive-post__entry-title title-xl mt-gap" itemprop="headline">
			<a class="archive-post__link transition-all duration-500 hover:text-primary"
				href="<?php echo esc_url($entry_link); ?>"
				aria-hidden="true"
				tabindex="-1">
				<?php the_title(); ?>
			</a>
		</h2>
		<div class="flex gap-gap mt-gap">
			<?php
			if (have_rows('dettagli')):
				while (have_rows('dettagli')) : the_row();
					$icon = get_sub_field('icona');
					$text = get_sub_field('testo');
			?>
					<div class="archive-post__details flex items-center gap-2 text-sm mb-gap">
						<?php echo ap_svg($icon, '', 'w-[22px] h-[22px] fill-primary'); ?>
						<span class="text-[16px]"><?php echo esc_html($text); ?></span>
					</div>
			<?php
				endwhile;
			endif;
			?>
		</div>
		<div class="archive-post__entry-content prose rem:mt-[5px]" itemprop="text">
			<?php
			$terms = get_the_terms(get_the_ID(), 'categoria_camere');

			if (! empty($terms) && ! is_wp_error($terms)) {
				$links = array();

				foreach ($terms as $term) {
					$links[] = '<a href="' . esc_url(get_term_link($term)) . '">' . esc_html($term->name) . '</a>';
				}

				echo implode(', ', $links);
			}
			?>
		</div>
		<div>
			<a class="base-button base-button--invert mt-gap"
				href="/contatti/">
				<?php esc_html_e('Richiedi', 'ap-wp-theme'); ?>
			</a>
			<a class="base-button mt-gap"
				href="/contatti/">
				<?php esc_html_e('Prenota', 'ap-wp-theme'); ?>
			</a>
		</div>

	</section>

</article>