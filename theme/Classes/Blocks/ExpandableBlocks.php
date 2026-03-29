<?php
/**
 * ExpandableBlocks Block
 *
 * @author Alessio Pangos
 */
namespace Classes\Blocks;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class ExpandableBlocks extends BaseBlock {

	public function __construct( $getField = false, $block = null ) {

		parent::__construct( $getField, $block );
	}

	public function render() {

		$this->setup();

		echo $this->container;

		if ( have_rows( 'blocchi_espandibili' ) ) :

			$counter = 0;

			while ( have_rows( 'blocchi_espandibili' ) ) :
				the_row();

				ob_start();
				new \Components\TextWithTag( 'titolo', ' base-text uppercase my-hhgap md:my-gap', false, ' itemprop="name"' );
				$title = ob_get_clean();

				ob_start();
				if ( have_rows( 'loghi_con_link' ) ) :
					?>
					<div class="grid grid-cols-4 gap-gap md:grid-cols-12 my-gap">

						<?php
						while ( have_rows( 'loghi_con_link' ) ) :
							the_row();
							$linkArray = Utils::GetLinkAndTarget( 'Link_Logo', false );

							?>
							<div class="flex items-center justify-center">
								<?php
								Utils::LinkOpen( $linkArray, ' block w-full', '', true );

								Utils::SimpleACFImg( get_sub_field( 'logo' ) );

								Utils::LinkClose( $linkArray );
								?>
							</div>
							<?php
						endwhile;
						?>

					</div>
					<?php
				endif;
				new \Components\Text( 'testo', ' small-text', false, false, ' itemprop="text"' );

				if ( have_rows( 'griglia' ) ) :
					?>
					<div class="grid grid-cols-2 md:grid-cols-4 gap-gap">
						<div class="grid gap-gap grid-cols-2 md:col-start-2 md:grid-cols-3 md:col-span-3">
							<?php
							while ( have_rows( 'griglia' ) ) :
								the_row();
								?>
								<span><?php echo get_sub_field( 'chiave' ); ?></span>
								<?php
							endwhile;
							?>
						</div>
					</div>
					<?php
				endif;
				$text = ob_get_clean();

				new \Components\ExpandableContent(
					$title,
					$text,
					'border-b border-black py-hgap px-hgap md:px-gap' . ( $counter === 0 ? ' border-t' : '' )
				);

				++$counter;

			endwhile;

		endif;

		?>

		<?php

		echo $this->containerClose;
	}
}
