# A list of all available Woocommerce Events (https://wordpress.stackexchange.com/questions/342148/list-of-js-events-in-the-woocommerce-frontend)

## Woocommerce Javascript events
## Woocommerce Checkout JS events

```
$( document.body ).trigger( 'init_checkout' );
$( document.body ).trigger( 'payment_method_selected' );
$( document.body ).trigger( 'update_checkout' );
$( document.body ).trigger( 'updated_checkout' );
$( document.body ).trigger( 'checkout_error' );
$( document.body ).trigger( 'applied_coupon_in_checkout' );
$( document.body ).trigger( 'removed_coupon_in_checkout' );
```

## Woocommerce cart page JS events

```
$( document.body ).trigger( 'wc_cart_emptied' );
$( document.body ).trigger( 'update_checkout' );
$( document.body ).trigger( 'updated_wc_div' );
$( document.body ).trigger( 'updated_cart_totals' );
$( document.body ).trigger( 'country_to_state_changed' );
$( document.body ).trigger( 'updated_shipping_method' );
$( document.body ).trigger( 'applied_coupon', [ coupon_code ] );
$( document.body ).trigger( 'removed_coupon', [ coupon ] );
```

## Woocommerce Single product page JS events

```
$( '.wc-tabs-wrapper, .woocommerce-tabs, #rating' ).trigger( 'init' );
```

## Woocommerce Variable product page JS events

```$( document.body ).trigger( 'found_variation', [variation] );```

## Woocommerce Add to cart JS events

```
$( document.body ).trigger( 'adding_to_cart', [ $thisbutton, data ] );
$( document.body ).trigger( 'added_to_cart', [ response.fragments, response.cart_hash, $thisbutton ] );
$( document.body ).trigger( 'removed_from_cart', [ response.fragments, response.cart_hash, $thisbutton ] );
$( document.body ).trigger( 'wc_cart_button_updated', [ $button ] );
$( document.body ).trigger( 'cart_page_refreshed' );
$( document.body ).trigger( 'cart_totals_refreshed' );
$( document.body ).trigger( 'wc_fragments_loaded' );
```

## Woocommerce Add payment method JS events

```
$( document.body ).trigger( 'init_add_payment_method' );
```

## To bind listener to these events, use:

```
jQuery('<event_target>').on('<event_name>', function(){
    console.log('<event_name> triggered');
});

F. ex.

jQuery('body').on('init_checkout', function(){
    console.log('init_checkout triggered');
    // now.do.whatever();
});
```

## VARIATIONS
```
    hide_variation triggered when displayed variation data is reset
    show_variation triggered when a variation has been found which matches all attributes
    woocommerce_variation_select_change triggered when an attribute field changes
    woocommerce_variation_has_changed triggered when variation selection has been changed
    check_variations triggered:
        when an attribute field changes
        when reload variation data from the DOM
    woocommerce_update_variation_values triggered when variations have been updated
    woocommerce_gallery_reset_slide_position reset the slide position if the variation has a different image than the current one
    woocommerce_gallery_init_zoom sets product images for the chosen variation
```

## CART FRAGMENTS

```
    wc_fragments_refreshed triggered when refreshing of cart fragments via Ajax was successful
    wc_fragments_ajax_error triggered when refreshing of cart fragments via Ajax has failed
    wc_fragment_refresh refresh when page is shown after back button (safari)
    wc_fragments_loaded triggered after the cart fragments have been loaded
```

## COUNTRY SELECT (CHECKOUT)

```
    country_to_state_changed triggered when the country changes from the select field
    country_to_state_changing and wc_address_i18n_ready handle locale
```

## SINGLE PRODUCT

```
    wc-product-gallery-before-init triggered before initializing all the galleries on the page
    wc-product-gallery-after-init triggered after initializing all the galleries on the page
```

## PRICE SLIDER

```
    price_slider_updated triggered after price slider updated
    price_slider_create triggered after price slider create
    price_slider_slide triggered after price slider slide
    price_slider_change triggered after price slider change
```
