<?php
/**
 * Template part for displaying search results
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package ap-wp-theme
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

$entryLink = get_the_permalink();
$cats      = get_the_category();

if ( $cats ) {
	$entryCategory = get_the_category()[0];
}
?>

<article class="archive__entry" data-gs-reveal="reveal_fromBottom">
	<div class="archive__entry-container">
		<p class="entry__meta rem:my-[5px] text-sm">
			<?php echo get_the_date(); ?>
		</p>
		<h2 class="title-sm" itemprop="headline"><a class="archive__link" href="<?php echo $entryLink; ?>" aria-hidden="true" tabindex="-1"><?php the_title(); ?></a></h2>
		<div class="archive__entry-content prose rem:mt-[5px]" itemprop="text">
			<?php
			if ( has_excerpt() ) {
				echo get_the_excerpt();
			} else {
				$more = '[..]';
				// echo force_balance_tags( html_entity_decode( wp_trim_words( htmlentities( get_the_content() ), 25, $more ) ) );
				echo wp_trim_words( strip_shortcodes( get_the_content() ), 25, $more );
			}
			?>
		</div>
		<?php if ( $cats ) : ?>
			<footer class="archive__entry-category text-sm text-primary rem:mt-[5px]">
				<a class="archive__link transition-all duration-500  hover:text-secondary" href="<?php echo get_category_link( $entryCategory->cat_ID ); ?>" aria-hidden="true" tabindex="-1">
					<?php echo $entryCategory->name; ?>
				</a>
			</footer>
		<?php endif; ?>
	</div>
</article>
