<?php
/**
 * MappaTerritorio Block
 *
 * @author Alessio Pangos
 */

namespace Classes\Blocks;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class MappaTerritorio extends BaseBlock {

	public function __construct( $getField = false, $block = null ) {
		parent::__construct( $getField, $block );
	}

	public function render() {

		$this->setup();

		echo $this->container;

		new \Components\Title( 'titolo', ' uppercase', 'title-xl text-center' );
		new \Components\TextWithTag( 'sottotitolo', 'block mb-2gap text-center' );
		new \Components\Text( 'testo', 'block w-[350px] max-w-full text-center mx-auto' );
		?>

		<div
			x-data="{
				width: 0,
				height: 0,
				setSize() {
					this.width = this.$refs.imageRef.getBoundingClientRect().width;
					this.height = this.$refs.imageRef.getBoundingClientRect().height
					this.$root.style.height = `${this.height}px`;
				}
			}"
			x-cloack
			x-init="setSize();"
			x-resize.window.throttle="setSize"
			class="min-h-[460px] md:min-h-screen alignfull flex flex-1 relative justify-start items-center overflow-x-auto max-w-none">
			<img src="<?php echo get_stylesheet_directory_uri() . '/assets/images/mappa-territorio.svg'; ?>" alt="" class="absolute w-auto h-full md:w-full md:h-auto max-w-none left-0 top-0 pointer-events-none" x-ref="imageRef"/>

			<?php if ( have_rows( 'pin' ) ) : ?>
				<?php
				while ( have_rows( 'pin' ) ) :
					the_row();
					?>
					<div x-data="{active: false}" class="absolute block h-[20px] w-[20px]" :style="`left: ${width / 100 * <?php echo get_sub_field( 'posizione_x' ); ?>}px; top: ${height / 100 * <?php echo get_sub_field( 'posizione_y' ); ?>}px;`" @mouseenter="active = true" @mouseleave="active = false" @click="active = ! active" @click.outside="active = false">
						<div class="absolute left-1/2 transform -translate-x-[60%] bottom-[-40px] z-[3] pointer-events-none opacity-0 transition-opacity duration-300 w-[300px] md:w-[520px] grid grid-cols-2 gap-hgap" :class="{ 'opacity-100': active }">
							<?php Utils::SimpleACFImg( get_sub_field( 'immagine' ) ); ?>
							<div class="text-xs">
								<span class="relative block z-[3] uppercase text-left">
									<?php echo acf_esc_html( get_sub_field( 'titolo' ) ); ?>
								</span>
								<span class="relative block z-[3] pb-hgap text-left">
									<?php echo acf_esc_html( get_sub_field( 'sottotitolo' ) ); ?>
								</span>
								<span class="text-left block z-[3]">
									<?php echo acf_esc_html( get_sub_field( 'testo_espanso' ) ); ?>
								</span>
							</div>
						</div>
						<div class="absolute opacity-60 top-0 left-0 h-[20px] w-[20px] bg-primary rounded-full transition-transform duration-300 block z-[1]"></div>
					</div>
				<?php endwhile; ?>
			<?php endif; ?>
		</div>
		<?php
		echo $this->containerClose;
	}
}
