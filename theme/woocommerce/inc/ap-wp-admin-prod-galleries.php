<?php
// Adds a custom meta box for product variations images
add_action( 'woocommerce_product_after_variable_attributes', 'wc_add_variation_image_gallery_meta_box', 10, 3 );
function wc_add_variation_image_gallery_meta_box( $loop, $variation_data, $variation ) {
	$images         = get_post_meta( $variation->ID, '_variation_image_gallery', true );
	$attachment_ids = explode( ',', $images );
	$attachment_ids = array_filter( $attachment_ids );
	?>
	<div class="form-field form-row form-row-full">
		<label for="variation_image_gallery_<?php echo esc_attr( $loop ); ?>"><?php echo __( 'Galleria immagini', 'ap-wp-theme-tw' ); ?></label>
		<input type="hidden" name="variation_image_gallery[<?php echo esc_attr( $loop ); ?>]" id="variation_image_gallery_<?php echo esc_attr( $loop ); ?>" value="<?php echo esc_attr( $images ); ?>" />
		<div class="variation_image_gallery_container grid grid-cols-3 gap-[10px] my-[20px] w-[200px]" id="variation_image_gallery_container_<?php echo esc_attr( $loop ); ?>">
			<?php
			if ( ! empty( $attachment_ids ) ) {
				foreach ( $attachment_ids as $attachment_id ) {
					$media_type = get_post_mime_type( $attachment_id );
					if ( $media_type === 'image/jpeg' || $media_type === 'image/png' ) {
						// echo '<img style="max-width:100%;" src="' . esc_url( wp_get_attachment_url( $attachment_id ) ) . '" />';
						echo wp_get_attachment_image( $attachment_id, 'thumbnail' );
						// echo '<li class="image" data-attachment_id="' . esc_attr( $attachment_id ) . '">
						// <img src="' . esc_url( wp_get_attachment_url( $attachment_id ) ) . '" />
						// <ul class="actions">
						// <li><a href="#" class="delete tips" data-tip="' . __( 'Delete image', 'woocommerce' ) . '">' . __( 'Delete', 'woocommerce' ) . '</a></li>
						// </ul>
						// </li>';
					} elseif ( $media_type === 'video/mp4' || $media_type === 'video/webm' ) {
						echo '<video src="' . esc_url( wp_get_attachment_url( $attachment_id ) ) . '"></video>';
						// echo '<li class="video" data-attachment_id="' . esc_attr( $attachment_id ) . '">
						// <video src="' . esc_url( wp_get_attachment_url( $attachment_id ) ) . '" controls></video>
						// <ul class="actions">
						// <li><a href="#" class="delete tips" data-tip="' . __( 'Delete video', 'woocommerce' ) . '">' . __( 'Delete', 'woocommerce' ) . '</a></li>
						// </ul>
						// </li>';
					}
				}
			}
			// if ( ! empty( $images ) ) {
			// $images = explode( ',', $images );
			// foreach ( $images as $image_id ) {
			// echo wp_get_attachment_image( $image_id, 'thumbnail' );
			// }
			// }
			?>
		</div>
		<p class="description">
			<a href="#" class="button add_variation_images" data-uploader-title="<?php echo __( 'Add images', 'ap-wp-theme-tw' ); ?>" data-uploader-button-text="Add" data-target="#variation_image_gallery_<?php echo esc_attr( $loop ); ?>"><?php echo __( 'Add images', 'ap-wp-theme-tw' ); ?></a>
		</p>
	</div>
	<?php
}

// Save variation image gallery
add_action( 'woocommerce_save_product_variation', 'wc_save_variation_image_gallery', 10, 2 );
function wc_save_variation_image_gallery( $variation_id, $i ) {
	if ( isset( $_POST['variation_image_gallery'][ $i ] ) ) {
		update_post_meta( $variation_id, '_variation_image_gallery', wc_clean( $_POST['variation_image_gallery'][ $i ] ) );
	}
}

// Enqueue variation image gallery script and stylesheet
add_action( 'admin_enqueue_scripts', 'wc_variation_image_gallery_scripts_and_styles', 99 );
function wc_variation_image_gallery_scripts_and_styles( $hook ) {
	if ( 'post.php' != $hook && 'post-new.php' != $hook ) {
		return;
	}

	wp_enqueue_media();
	wp_enqueue_style( 'wc-variation-image-gallery-admin', get_template_directory_uri() . '/wc-editor.css' );
	wp_enqueue_script( 'wc-variation-image-gallery-admin', get_template_directory_uri() . '/js/wc-admin-scripts.min.js', array( 'jquery' ), '1.0', true );
}
