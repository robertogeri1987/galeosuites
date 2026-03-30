<?php

/**
 * HeadingHero Block
 *
 * @author Alessio Pangos
 */

namespace Classes\Blocks;

// Se questo file viene chiamato direttamente, termina l'esecuzione.
if (! defined('WPINC')) {
	die;
}

class HeadingHero extends BaseBlock
{


	public function __construct($getField = false, $block = null)
	{
		parent::__construct($getField, $block);
	}

	public function render()
	{
		$this->setup();

		echo $this->container;

		$useSlider        = get_field('usa_slider');
		$autoplay         = get_field('autoplay');
		$fixedText        = get_field('testo_fisso');
		$fixedTextContent = get_field('testo_fisso_content');
		$blockId          = uniqid('heading-hero-');

		if ($useSlider) {
			$this->renderSlider($blockId, $autoplay, $fixedText, $fixedTextContent);
		} else {
			$this->renderHero($blockId, $fixedText, $fixedTextContent);
		}

		echo $this->containerClose;
	}

	private function renderHero($blockId, $fixedText, $fixedTextContent)
	{
?>

		<div id="<?php echo esc_attr($blockId); ?>" class="heading-hero max-w-full mx-auto min-h-screen layout-container">
			<div class="max-w-screen w-screen relative overflow-hidden z-1 md:col-span-2 left-1/2 transform -translate-x-1/2 h-screen">
				<div class="absolute w-full h-full"></div>
				<div class="w-full h-full">
					<div class="layout-container bottomCenter text-center block z-10 mb-2gap">
						<?php
						new \Components\Title('titolo_heading_hero', 'title-5xl text-white font-normal mb-2gap', '');
						new \Components\Text('testo_hero', ' title-lg text-white font-normal mb-2gap', true);

						?>

						<img class="w-[16px] h-auto max-w-full mx-auto animate-bounce" width="16" height="67" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/arrow.svg">
					</div>
					<?php new \Components\Image('hero_image', 'hero-image w-full h-full object-cover relative z-0'); ?>
				</div>
			</div>
		</div>

		<?php
	}

	private function renderSlider($blockId, $autoplay, $fixedText, $fixedTextContent)
	{
		if (have_rows('slide')) {
			$autoplayAttr = $autoplay ? 'true' : 'false';

			echo '<div id="' . esc_attr($blockId) . '" class="swiper-container heading-hero-swiper acf-slider__container h-screen" data-autoplay="' . esc_attr($autoplayAttr) . '">';
			echo '<div class="swiper-wrapper h-full overflow-hidden">';

			while (have_rows('slide')) {
				the_row();
				$slideType   = get_sub_field('tipo_slide');
				$link        = get_sub_field('link_slide');
				$duration    = get_sub_field('durata');
				$videoUrl    = get_sub_field('video');
				$coverImage  = get_sub_field('cover_video');
				$textEnabled = get_sub_field('testo');
				$textContent = get_sub_field('testo_slide');

				echo '<div class="swiper-slide  h-full" data-duration="' . esc_attr($duration) . '">';

				if ($slideType == 'immagine') {
					new \Components\Image('immagine', 'max-w-full w-full h-full object-cover', false, '', '');
				} elseif ($slideType == 'video') {
					$videoUrl      = is_string($videoUrl) ? $videoUrl : '';
					$coverImageUrl = is_array($coverImage) && isset($coverImage['url']) ? $coverImage['url'] : '';
					$videoMobile   = get_sub_field('video_diverso_mobile');

					if ($videoMobile) :
						$videoUrlM      = get_sub_field('video_mobile');
						$coverImageM    = get_sub_field('cover_video_mobile');
						$videoUrlM      = is_string($videoUrlM) ? $videoUrlM : '';
						$coverImageUrlM = is_array($coverImageM) && isset($coverImageM['url']) ? $coverImageM['url'] : '';
		?>
						<video x-data class="video-background w-full h-full object-cover" :src="$store.header.navigationMode === 'mobile' ? '<?php echo esc_url($videoUrlM); ?>' : '<?php echo esc_url($videoUrl); ?>'" autoplay loop muted playsinline controlslist="nodownload" :poster="$store.header.navigationMode === 'mobile' ? '<?php echo esc_url($coverImageUrlM); ?>' : '<?php echo esc_url($coverImageUrl); ?>'"></video>

					<?php

					else :
					?>
						<video class="video-background w-full h-full object-cover" src="<?php echo esc_url($videoUrl); ?>" autoplay loop muted playsinline controlslist="nodownload" poster="<?php echo esc_url($coverImageUrl); ?>"></video>
<?php
					endif;
				}

				if ($textEnabled && $textContent) {
					echo '<div class="absolute bottom-0 left-0 right-0 text-center text-white p-gap">';
					echo wp_kses_post($textContent);
					echo '<img class="w-[16px] h-auto mt-gap max-w-full mx-auto animate-bounce" width="16" height="67" src="' . get_stylesheet_directory_uri() . '/assets/images/arrow.svg">';
					echo '</div>';
				}

				if ($link) {
					// Aggiungi il controllo del tipo
					$link = is_string($link) ? $link : '';
					echo '<a href="' . esc_url($link) . '" class="absolute inset-0 z-10"></a>';
				}

				echo '</div>';
			}

			echo '</div>';
			if ($fixedText) {
				echo '<div class="fixed-text absolute top-0 left-0 w-full text-center p-4 text-white">';
				echo wp_kses_post($fixedTextContent);
				echo '</div>';
			}
			echo '</div>';
		}
	}
}
?>