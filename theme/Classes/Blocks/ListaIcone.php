<?php
/**
 * ListaIcone Block
 *
 * @author Alessio Pangos
 */
namespace Classes\Blocks;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class ListaIcone extends BaseBlock {

	public function __construct( $getField = false, $block = null ) {
		parent::__construct( $getField, $block );
	}

	public function render() {

		$this->setup();

		echo $this->container;

		new \Components\Title( 'titolo', ' text-center', 'title-xl' );

		if ( have_rows( 'icone' ) ) :

			?>
			<div class="mt-gap border-y border-black p-gap flex flex-wrap items-center justify-center gap-4gap md:gap-[120px]">
				<?php
				while ( have_rows( 'icone' ) ) :
					the_row();
					$link = Utils::GetLinkAndTarget( 'link_icona', false );
					Utils::LinkOpen( $link, 'w-full' );
						Utils::SimpleACFImg( get_sub_field( 'icona' ), 'full', ' w-auto h-[84px]' );
					Utils::LinkClose( $link );
				endwhile;
				?>
			</div>
			<?php
		endif;

		echo $this->containerClose;
	}
}
