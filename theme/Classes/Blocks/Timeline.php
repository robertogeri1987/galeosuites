<?php
/**
 * Timeline Block
 *
 * @author Alessio Pangos
 */
namespace Classes\Blocks;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Timeline extends BaseBlock {

	public function __construct( $getField = false, $block = null ) {
		parent::__construct( $getField, $block );
	}

	public function render() {

		$this->setup();

		echo $this->container;

		if ( have_rows( 'timeline_entry' ) ) :

			?>
			<div class="relative mdd:layout-container mdd:flex mdd:flex-col-reverse w-full" x-data="timeline" data-first-active="<?php echo intval( count( get_field( 'timeline_entry' ) ) / 2 ); ?>">
				<div class="mdd:layout-containter md:w-[1373px] max-w-full md:pl-gap md:pr-[400px] mx-auto md:sticky md:top-[100px]" x-ref="container">
					<?php
					while ( have_rows( 'timeline_entry' ) ) :
						the_row();
						$anno = get_sub_field( 'anno' );
						?>
						<div data-content x-cloack x-transition x-show="active == <?php echo get_row_index(); ?>" class="flex flex-col md:flex-row items-center gap-gap">
							<?php
							new \Components\Image( 'immagine', ' flex-1', false );
							?>
							<div class="w-full md:w-[453px] md:min-w-[453px]">
								<?php
								new \Components\Title( 'titolo', '', 'title-md mb-gap', false );
								new \Components\Text( 'testo', ' mb-auto', false );
								?>
							</div>
						</div>
						<?php

					endwhile;
					?>
				</div>
				<div class="md:absolute top-0 right-0 w-full md:w-[380px]" x-ref="timeline">
					<div class="md:absolute md:w-full mdd:gap-gap md:top-0 md:right-0 flex md:flex-col items-start justify-start md:justify-end mdd:overflow-x-scroll mdd:overflow-y-clip mdd:w-full">
						<?php
						while ( have_rows( 'timeline_entry' ) ) :
							the_row();
							$anno = get_sub_field( 'anno' );
							?>
							<div data-anno class="cursor-pointer py-gap md:py-2gap title-md uppercase text-right flex items-center justify-end transition-transform duration-500 origin-center md:origin-right" :class="{ 'scale-[1.5]': active == <?php echo get_row_index(); ?> }">
								<span class="flex-1">
									<?php echo $anno; ?>
								</span>
								<span class="inline-block w-[150px] border-b border-black ml-gap mdd:hidden"></span>
							</div>
							<?php
						endwhile;
						?>
					</div>
				</div>
			</div>
			<?php

			while ( have_rows( 'timeline_entry' ) ) :
				the_row();

			endwhile;

		endif;

		echo $this->containerClose;
	}
}
