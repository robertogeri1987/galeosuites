<?php
/**
 * MappaArno Block
 *
 * @author Alessio Pangos
 */

namespace Classes\Blocks;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class MappaArno extends BaseBlock {

	public function __construct( $getField = false, $block = null ) {
		parent::__construct( $getField, $block );
	}

	public function render() {

		$this->setup();

		echo $this->container;

		new \Components\Title( 'titolo', ' uppercase', 'title-xl' );
		new \Components\TextWithTag( 'sottotitolo', 'block mb-gap' );
		new \Components\Text( 'testo', 'block w-[350px] max-w-full' );
		?>
		<div class="group">
			<?php
			new \Components\Button( 'cta', 'mt-4 uppercase mr-gap' );
			new \Components\LinkWithImage( 'cta', ' mt-2gap', true, ap_svg( 'arrow-button', null, 'rem:h-[74px] rem:w-[74px] fill-primary hover:fill-hover stroke-current inline transform transition-transform duration-300 group-hover:translate-x-2', true ) );
			?>
		</div>

		<div
			x-data="{
				width: 0,
				height: 0,
				setSize() {
					this.width = this.$refs.imageRef.getBoundingClientRect().width;
					this.height = this.$refs.imageRef.getBoundingClientRect().height;
					this.$root.style.height = `${this.height}px`;
				}
			}"
			x-cloack
			x-init="setSize();"
			x-resize.window.throttle="setSize"
			class="mdd:hidden min-h-[300px] md:min-h-[460px] alignfull flex flex-1 relative justify-start items-center overflow-visible max-w-none">
			<img src="<?php echo get_stylesheet_directory_uri() . '/assets/images/mappa-arno.svg'; ?>" alt="" class="absolute w-auto h-full md:w-full md:h-auto max-w-none left-0 top-0 pointer-events-none" x-ref="imageRef"/>

			<?php if ( have_rows( 'pin' ) ) : ?>
				<?php
				while ( have_rows( 'pin' ) ) :
					the_row();
					?>
					<div x-data="{active: false}" class="absolute block h-[20px] w-[20px]" :style="`left: ${width / 100 * <?php echo get_sub_field( 'posizione_x' ); ?>}px; top: ${height / 100 * <?php echo get_sub_field( 'posizione_y' ); ?>}px;`" @mouseenter="active = true" @mouseleave="active = false" @click="active = ! active" @click.outside="active = false">
						<div class="xCenter bottom-full block z-[3] min-w-[250px]">
							<span class="relative block z-[3] uppercase text-left font-medium">
								<?php echo acf_esc_html( get_sub_field( 'titolo' ) ); ?>
							</span>
							<span class="relative block z-[3] pb-hgap text-left leading-[15px]">
								<?php echo acf_esc_html( get_sub_field( 'sottotitolo' ) ); ?>
							</span>
							<span class="xCenter top-full text-left block z-[3] pointer-events-none opacity-0 transition-opacity duration-300 w-[250px]" :class="{ 'opacity-100': active }">
								<?php echo acf_esc_html( get_sub_field( 'testo_espanso' ) ); ?>
							</span>
						</div>
						<div class="absolute opacity-60 top-0 left-0 h-[20px] w-[20px] bg-primary rounded-full transition-transform duration-300 block z-[1]" :class="{ 'scale-[10]': active }"></div>
					</div>
				<?php endwhile; ?>
			<?php endif; ?>
		</div>
		<?php
		echo $this->containerClose;
	}
}
