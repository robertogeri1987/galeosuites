<?php
/**
 * PostsSelection Block
 *
 * @author Alessio Pangos
 */
namespace Classes\Blocks;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class PostsSelection extends BaseBlock {

	public $relPosts;

	public function __construct( $getField = false, $block = null ) {
		parent::__construct( $getField, $block );
		$this->relPosts = parent::ACF( 'racconti_selezionati' );
	}

	public function render() {

		$this->setup();

		echo $this->container;

		if ( $this->relPosts ) :
			$relProds = new \WP_Query(
				array(
					'posts_per_page' => -1,
					'post_type'      => 'post',
					'orderby'        => 'post__in',
					'post__in'       => $this->relPosts,
				)
			);

			if ( $relProds->have_posts() ) :

				new \Components\Title( 'titolo', ' mb-2gap', 'title-lg' );
				?>
				<ul class="grid grid-cols-2 gap-gap auto-rows-min auto-cols-min">
					<?php
					while ( $relProds->have_posts() ) :
						$relProds->the_post();
						echo get_template_part( 'template-parts/related', 'post' );
					endwhile;
					?>
				</ul>
				<?php
			endif;

		endif;

		wp_reset_postdata();

		echo $this->containerClose;
	}
}
