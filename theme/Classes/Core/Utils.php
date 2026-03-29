<?php
/**
 * Utility Classes
 *
 * @author Alessio Pangos
 */
namespace Classes\Core;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Utils {

	/**
	 * Outputs the post featured IMG
	 */
	public static function SimpleFeaturedImg( $size = 'full', $additionalClasses = '', $lazyload = true, $additionaAttributes = '' ) {

		$lazyload ? $loading = 'loading="lazy" ' : $loading = '';
		$id                  = get_the_ID();

		if ( $size !== 'full' ) {
			$imgW = get_option( $size . '_size_w' );
			$imgH = get_option( $size . '_size_h' );
		} else {
			$imgFull = wp_get_attachment_image_src( get_post_thumbnail_id( $id ), 'full' );
			if ( $imgFull ) {
				$imgW = $imgFull[1];
				$imgH = $imgFull[2];
			}
		}

		$imgSrc = get_the_post_thumbnail_url( $id, $size );

		if ( $imgSrc ) :
			?>
			<img <?php echo $loading; ?>alt="<?php echo esc_html( get_the_title() ); ?>" class="w-full<?php echo $additionalClasses; ?>" width="<?php echo $imgW; ?>" height="<?php echo $imgH; ?>" <?php \ap_wp_the_retina_srcset( $imgSrc ); ?>src="<?php echo $imgSrc; ?>"<?php echo $additionaAttributes; ?> />
			<?php
		endif;
	}

	/**
	 * Outputs a retina image from an ACF Image field
	 */
	public static function SimpleACFImg( $img = null, $size = 'full', $additionalClasses = '', $lazyload = true, $additionaAttributes = '' ) {
		if ( $img && is_array( $img ) ) :
			$lazyload ? $loading = 'loading="lazy" ' : $loading = '';
			if ( $size === 'full' ) {
				$width  = $img['width'];
				$height = $img['height'];
				$src    = $img['url'];
			} else {
				$width  = $img['sizes'][ $size . '-width' ];
				$height = $img['sizes'][ $size . '-height' ];
				$src    = $img['sizes'][ $size ];
			}
			$title = $img['title'];
			$alt   = $img['alt'];
			?>
			<img <?php echo $loading; ?>class="<?php echo $additionalClasses; ?>" <?php \ap_wp_the_retina_srcset( $src ); ?>src="<?php echo $src; ?>" alt="<?php echo $alt; ?>" width="<?php echo $width; ?>" height="<?php echo $height; ?>"<?php echo $additionaAttributes; ?> />
			<?php
		endif;
	}

	public static function ResponsiveACFPicture( $deskImg = null, $imgClasses = '', $tabImg = null, $mobileImg = null, $lazyload = true, $additionaAttributes = '', $pitcureClasses = '' ) {

		$lazyload ? $loading = 'loading="lazy" ' : $loading = '';
		if ( $pitcureClasses ) {
			$pitcureClasses = ' class="' . $pitcureClasses . '"';
		}
		if ( $deskImg ) :
			?>
			<picture<?php echo $pitcureClasses; ?>>
				<?php if ( $mobileImg ) : ?>
					<source media="(max-width: 32em)" srcset="<?php echo $mobileImg['url']; ?>">
				<?php endif; ?>
				<?php if ( $tabImg ) : ?>
					<source media="(max-width: 56em)" srcset="<?php echo $tabImg['url']; ?>">
				<?php endif; ?>
				<img width="<?php echo $deskImg['width']; ?>" height="<?php echo $deskImg['height']; ?>" <?php echo $loading; ?>class="<?php echo $imgClasses; ?>" src="<?php echo $deskImg['url']; ?>" alt="<?php echo $deskImg['alt']; ?>"<?php echo $additionaAttributes; ?> />
			</picture>
			<?php
		endif;
	}

	public static function ResponsivePicture( $deskImgUrl = null, $imgClass = '', $tabImgUrl = null, $mobileImgUrl = null, $lazyload = true, $alt = '', $width = '', $height = '' ) {

		$lazyload ? $loading = 'loading="lazy" ' : $loading = '';
		$w                   = '';
		if ( $width && $height ) {
			$w = ' width="' . $width . '" height="' . $height . '" ';
		}
		if ( $deskImgUrl ) :
			?>
			<picture>
				<?php if ( $mobileImgUrl ) : ?>
					<source media="(max-width: 32em)" srcset="<?php echo $mobileImgUrl; ?>">
				<?php endif; ?>
				<?php if ( $tabImgUrl ) : ?>
					<source media="(max-width: 56em)" srcset="<?php echo $tabImgUrl; ?>">
				<?php endif; ?>
				<img <?php echo $loading; ?><?php echo $w; ?>class="<?php echo $imgClass; ?>" src="<?php echo $deskImgUrl; ?>" alt="<?php echo $alt; ?>">
			</picture>
			<?php
		endif;
	}

	/**
	 * Returns the target of an ACF link field
	 */
	public static function GetLinkTargetACF( $link ) {
		$target = '';
		( $link && array_key_exists( 'target', $link ) && $link['target'] !== '' ) ? $target = ' target="_blank" rel="noopener"' : $target = '';
		return $target;
	}

	/**
	 * Return a dynamic HTML5 tag
	 */
	public static function DynamicTag( $tag = 'span', $content = null, $classes = '', $additionaAttributes = '' ) {

		if ( $content ) {
			echo '<' . $tag . ' class="' . $classes . '"' . $additionaAttributes . '>' . $content . '</' . $tag . '>';
		}
	}

	/**
	 * Return a dynamic HTML5 tag from a grouped ACF field
	 */
	public static function DynamicTagGroupACF( $fieldGroup, string $tagName, string $contentName, string $classes, string $additionaAttributes = '' ) {

		if ( $fieldGroup && array_key_exists( $tagName, $fieldGroup ) && array_key_exists( $contentName, $fieldGroup ) ) {

			if ( $fieldGroup[ $contentName ] ) {
				echo '<' . $fieldGroup[ $tagName ] . ' class="' . $classes . '"' . $additionaAttributes . '>' . $fieldGroup[ $contentName ] . '</' . $fieldGroup[ $tagName ] . '>';
			}
		}
	}

	public static function titleGroupACF( $fieldGroup, string $additionalClasses, string $defaultSize = 'title-md', $additionaAttributes = '' ) {

		if ( $fieldGroup && array_key_exists( 'tag_titolo_blocco', $fieldGroup ) && array_key_exists( 'titolo_blocco', $fieldGroup ) ) {
			$size = $defaultSize;

			if ( array_key_exists( 'modifica_dimensione_titolo', $fieldGroup ) ) {
				if ( $fieldGroup['modifica_dimensione_titolo'] ) {
					$size = $fieldGroup['dimensione_titolo'];
					if ( $size === 'xs' ) {
						$size = 'title-xs';
					}
					if ( $size === 'sm' ) {
						$size = 'title-sm';
					}
					if ( $size === 'md' ) {
						$size = 'title-md';
					}
					if ( $size === 'lg' ) {
						$size = 'title-lg';
					}
					if ( $size === 'xl' ) {
						$size = 'title-lg';
					}
				}
			}

			if ( $fieldGroup['titolo_blocco'] ) {
				echo '<' . $fieldGroup['tag_titolo_blocco'] . ' class="' . $size . $additionalClasses . '"' . $additionaAttributes . '>' . $fieldGroup['titolo_blocco'] . '</' . $fieldGroup['tag_titolo_blocco'] . '>';
			}
		}
	}

	public static function ProseText( $text, $additionalClasses = '', $id = '', $additionaAttributes = '' ) {

		$id ? $id = ' id="' . $id . '"' : $id = '';
		if ( $text ) :
			?>
			<div class="prose<?php echo $additionalClasses; ?>"<?php echo $id; ?><?php echo $additionaAttributes; ?>>
				<?php echo $text; ?>
			</div>
			<?php
		endif;
	}

	public static function Button( $content = '', $additionalClasses = '', $id = '', $additionaAttributes = '' ) {

		$id ? $id = ' id="' . $id . '"' : $id = '';
		?>
		<span class="base-button<?php echo $additionalClasses; ?>"<?php echo $id; ?><?php echo $additionaAttributes; ?>>
			<?php echo $content; ?>
		</span>
		<?php
	}

	public static function ButtonLinkACF( $link = array(), $additionalClasses = '', $forcedContent = '', $additionaAttributes = '' ) {

		self::LinkOpen( $link, 'base-button' . $additionalClasses, $forcedContent, $additionaAttributes );
		self::LinkClose( $link );
	}

	public static function LinkOpen( $link = array(), $classes = '', $forcedContent = '', $noContent = false, $additionaAttributes = '', $contentAfterTitle = '' ) {

		if ( $link && array_key_exists( 'url', $link ) ) :

			$target   = self::GetLinkTargetACF( $link );
			$download = array_key_exists( 'download', $link ) ? ' download' : '';
			?>
			<a class="<?php echo $classes; ?>" href="<?php echo $link['url']; ?>"
			<?php
				echo $target;
				echo $download;
				echo $additionaAttributes;
			?>
			>
				<?php if ( ! $noContent ) : ?>
					<?php if ( $forcedContent ) : ?>
						<?php echo $forcedContent; ?>
					<?php else : ?>
						<?php echo array_key_exists( 'title', $link ) ? $link['title'] : ''; ?>
					<?php endif; ?>
					<?php echo $contentAfterTitle; ?>
				<?php endif; ?>
			<?php
		endif;
	}

	public static function LinkClose( $link = array() ) {

		if ( $link && array_key_exists( 'url', $link ) ) :
			echo '</a>';
		endif;
	}

	/**
	 *
	 * Returns an array containing the URL and the link target
	 *
	 * @param boolean $getField determines if get_field or get_sub_field functions are used
	 * @param string  $prefix if ACF field is cloned in grouped mode, gets the group value array instead of separate fields
	 */
	public static function GetLinkAndTarget( $prefix = '', $getField = true, $forcedPrefix = false ) {

		if ( ! $prefix ) {
			$linkType = $getField ? get_field( 'link_type' ) : get_sub_field( 'link_type' );
		} else {
			if ( $forcedPrefix ) {
				$prefix = $forcedPrefix;
			} else {
				$prefix = $getField ? get_field( $prefix ) : get_sub_field( $prefix );
				if ( ! is_array( $prefix ) ) {
					return;
				}
			}
			$linkType = $prefix['link_type'];
		}

		switch ( $linkType ) :
			case 'nessuno':
				return false;
			case 'interno':
				if ( $prefix && array_key_exists( 'link_interno', $prefix ) ) {
					return array(
						'url'    => $prefix['link_interno'],
						'target' => '',
						'title'  => array_key_exists( 'testo_link', $prefix ) ? $prefix['testo_link'] : '',
					);
				}
				return array(
					'url'    => $getField ? get_field( 'link_interno' ) : get_sub_field( 'link_interno' ),
					'target' => '',
					'title'  => $getField ? get_field( 'testo_link' ) : get_sub_field( 'testo_link' ),
				);
			case 'download':
				if ( $prefix && array_key_exists( 'file_da_scaricare', $prefix ) ) {
					return array(
						'url'      => $prefix['file_da_scaricare'],
						'target'   => ' target="_blank" rel="noopener"',
						'title'    => array_key_exists( 'testo_link', $prefix ) ? $prefix['testo_link'] : '',
						'download' => true,
					);
				}
				return array(
					'url'      => $getField ? get_field( 'file_da_scaricare' ) : get_sub_field( 'file_da_scaricare' ),
					'target'   => ' target="_blank" rel="noopener"',
					'title'    => $getField ? get_field( 'testo_link' ) : get_sub_field( 'testo_link' ),
					'download' => true,
				);
			case 'libero':
				if ( $prefix && array_key_exists( 'link_esterno', $prefix ) && array_key_exists( 'url', $prefix['link_esterno'] ) ) {
					return array(
						'url'    => $prefix['link_esterno']['url'],
						'target' => self::GetLinkTargetACF( $prefix['link_esterno'] ),
						'title'  => array_key_exists( 'title', $prefix['link_esterno'] ) ? $prefix['link_esterno']['title'] : '',
					);
				}
				$linkEsterno = $getField ? get_field( 'link_esterno' ) : get_sub_field( 'link_esterno' );
				return array(
					'url'    => $linkEsterno['url'],
					'target' => self::GetLinkTargetACF( $linkEsterno ),
					'title'  => $linkEsterno['title'],
				);
			case 'tassonomia':
				if ( $prefix && array_key_exists( 'tipo_di_tassonomia', $prefix ) ) {
					$type = $prefix['tipo_di_tassonomia'];
				} else {
					$type = $getField ? get_field( 'tipo_di_tassonomia' ) : get_sub_field( 'tipo_di_tassonomia' );

				}
				$id = null;
				switch ( $type ) :
					case 'category':
						if ( $prefix && array_key_exists( 'link_tassonomia_cat', $prefix ) ) {
							$id = $prefix['link_tassonomia_cat'];
							break;
						}
						$id = $getField ? get_field( 'link_tassonomia_cat' ) : get_sub_field( 'link_tassonomia_cat' );
						break;
					case 'tag':
						if ( $prefix && array_key_exists( 'link_tassonomia_tag', $prefix ) ) {
							$id = $prefix['link_tassonomia_tag'];
							break;
						}
						$id = $getField ? get_field( 'link_tassonomia_tag' ) : get_sub_field( 'link_tassonomia_tag' );
						break;
					case 'product_cat':
						if ( $prefix && array_key_exists( 'link_tassonomia_product_cat', $prefix ) ) {
							$id = $prefix['link_tassonomia_product_cat'];
							break;
						}
						$id = $getField ? get_field( 'link_tassonomia_product_cat' ) : get_sub_field( 'link_tassonomia_product_cat' );
						break;
					case 'product_tag':
						if ( $prefix && array_key_exists( 'link_tassonomia_product_tag', $prefix ) ) {
							$id = $prefix['link_tassonomia_product_tag'];
							break;
						}
						$id = $getField ? get_field( 'link_tassonomia_product_tag' ) : get_sub_field( 'link_tassonomia_product_tag' );
						break;
				endswitch;
				$link = get_term_link( $id, $type );
				if ( is_wp_error( $link ) ) {
					return array(
						'url'    => '',
						'target' => '',
						'title'  => '',
					);
				}
				if ( $prefix && array_key_exists( 'testo_link', $prefix ) ) {
					return array(
						'url'    => $link,
						'target' => '',
						'title'  => $prefix['testo_link'],
					);
				}
				return array(
					'url'    => $link,
					'target' => '',
					'title'  => $getField ? get_field( 'testo_link' ) : get_sub_field( 'testo_link' ),
				);
			break;
		endswitch;
	}
}
