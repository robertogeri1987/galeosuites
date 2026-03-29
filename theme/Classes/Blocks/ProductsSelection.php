<?php
/**
 * ProductsSelection Block
 *
 * @author Alessio Pangos
 */
namespace Classes\Blocks;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class ProductsSelection extends BaseBlock {

	protected $customChoice;

	public function __construct( $getField = false, $block = null ) {
		parent::__construct( $getField, $block );
		$this->customChoice = parent::ACF( 'scegli_prodotti' );
	}

	public function render() {

		$this->setup();

		echo $this->container;

		new \Components\Title( 'titolo', ' text-center uppercase', 'title-xl' );
		new \Components\TextWithTag( 'sottotitolo', ' title-xl mb-svgap text-center' );

		if ( $this->customChoice ) {

			$relProds = new \WP_Query(
				array(
					'post_type'      => array( 'product' ),
					'post__in'       => parent::ACF( 'prodotti_selezionati' ),
					'posts_per_page' => -1,
					'orderby'        => 'post__in',
				)
			);

		} else {
			$relProds = new \WP_Query(
				array(
					'posts_per_page' => -1,
					'post_type'      => 'product',
					'posts_per_page' => 4,
					'orderby'        => 'date',
					'order'          => 'DESC',
				)
			);
		}

		if ( $relProds->have_posts() ) :

			?>
			<ul class="grid grid-cols-2 md:grid-cols-4 gap-gap products">
				<?php
				while ( $relProds->have_posts() ) :
					$relProds->the_post();
					wc_get_template_part( 'content', 'product' );
				endwhile;
				?>
			</ul>
			<?php
		endif;

		wp_reset_postdata();
		?>
		<?php

		echo $this->containerClose;
	}
}
