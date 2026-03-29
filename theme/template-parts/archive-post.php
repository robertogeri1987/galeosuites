<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package ap-wp-theme
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

$entryLink     = get_the_permalink();
$entryCategory = get_the_category()[0];
?>

<article class="block relative md:col-span-3" data-gs-reveal="reveal_fromBottom">
	<a class="archive-post__link transition-all duration-500 hover:opacity-70" href="<?php echo $entryLink; ?>" aria-hidden="true" tabindex="-1">
		<?php \Classes\Core\Utils::SimpleFeaturedImg( 'thumbnail' ); ?>
	</a>
	<section class="archive-post__entry-container">
		<a class="archive-post__link transition-all duration-500 text-black hover:text-primary uppercase block text-sm mt-gap" href="<?php echo get_category_link( $entryCategory->cat_ID ); ?>" aria-hidden="true" tabindex="-1">
			<?php echo $entryCategory->name; ?>
		</a>
		<h2 class="archive-post__entry-title title-md" itemprop="headline"><a class="archive-post__link transition-all duration-500  hover:text-primary" href="<?php echo $entryLink; ?>" aria-hidden="true" tabindex="-1"><?php the_title(); ?></a></h2>
		<div class="archive-post__entry-content prose rem:mt-[5px]" itemprop="text">
			<?php
			$more = '...';
			if ( has_excerpt() ) {
				echo wp_trim_words( strip_shortcodes( get_the_excerpt() ), 20, $more );
			} else {
				// echo force_balance_tags( html_entity_decode( wp_trim_words( htmlentities( get_the_content() ), 20, $more ) ) );
				echo wp_trim_words( strip_shortcodes( get_the_content() ), 20, $more );
			}
			?>
		</div>
	</section>
</article>
