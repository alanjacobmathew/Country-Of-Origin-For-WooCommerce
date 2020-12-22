<?php
/**
 * Plugin Name: Country Of Origin for WooCommerce
 * Description: Include the country of origin for products sold with WooCommerce
 * Version: 1.0
 * Author: Alan Jacob Mathew
 * Author URI:https://profiles.wordpress.org/alanjacobmathew/
 * Tested up to: 5.6
 * Text Domain: country-of-origin-for-woocommerce
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */
 
  if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
//Get Data from WooCommerce Product Page
add_action( 'woocommerce_product_options_general_product_data', 'wcorigin_getdata' );

function wcorigin_getdata() {

  global $woocommerce, $post;

    woocommerce_wp_text_input( 
        array( 
            'id'          => '_country_origin', 
            'label'       => __( 'Country of Manufacture', 'woocommerce' ), 
            'placeholder' => 'USA',
            'desc_tip'    => 'true',
            'description' => __( 'Please enter the country where the product is manufactured', 'woocommerce' ) 
        )
    );

}

// save fields
add_action('woocommerce_process_product_meta', 'wcorigin_savedata');
function wcorigin_savedata($post_id)
{  
    $woocommerce_country_origin = $_POST['_country_origin'];
    if (!empty($woocommerce_country_origin))
        update_post_meta($post_id, '_country_origin', esc_attr($woocommerce_country_origin));
	else
		update_post_meta($post_id, '_country_origin', esc_attr($woocommerce_country_origin));

}


// Display Field
add_action('woocommerce_single_product_summary', 'wcorigin_display', 18);
function wcorigin_display()     
{
    global $post;
    $field_value = get_post_meta( $post->ID, '_country_origin', true );
    // Displaying the custom field only when is set with a value
    if( ! empty( $field_value ) )
        echo  '<b>'  . __( ' Country of Origin is: ' , 'woocommerce') . $field_value  .  '</b>' ;
	//else 
	//	echo  '<b>'  . __( ' Country of Origin is: ' , 'woocommerce') . WC()->countries->get_base_country(). '</b>';
}


/** 
 * Create a custom links in plugin page
 **/
function wcorigin_settings_link($links) {
  $settings_link = __( '<a href="admin.php?page=wc-settings&tab=products&section=wcorigin">Settings</a>', 'country-of-origin-for-woocommerce' );
	array_unshift($links, $settings_link); 
  return $links; 
}
$wcorigin_plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$wcorigin_plugin", 'wcorigin_settings_link' );


/**
 * Create the section beneath the WooCommerce products tab
 **/

add_filter( 'woocommerce_get_sections_products', 'wcorigin_add_section' );
function wcorigin_add_section( $sections ) {
	
	$sections['wcorigin'] = __( 'Country Of Origin', 'country-of-origin-for-woocommerce' );
	return $sections;
	
}	

/** 
 * Adding Content to the above created section
**/


add_filter( 'woocommerce_get_settings_products' , 'origin_get_settings' , 10, 2 );

function origin_get_settings( $settings, $current_section ) {
         $custom_settings = array();
         if( 'wcorigin' == $current_section ) {

              $custom_settings =  array(

					array(
					        'name' => __( 'Country Of Origin For WooCommerce ' ),
					        'type' => 'title',
					        'desc' => __( 'Show the Country Of Manufacture of the products to your Customers. <br><br><strong>Country Of Origin with the product description creates an advantage in influencing consumers purchase intention towards brands. For private brands this is a must have plugin as it increases the value positioning in consumers mind.</strong><br><br></a><div style="width:500px"> <span style="font-size:15px;"></span>&#127911; <a target="_blank" style="color:#d64e07;font-weight:bold;" href="https://profiles.wordpress.org/alanjacobmathew/">  Alan Jacob Mathew</p></div><hr>', 'country-of-origin-for-woocommerce' ),
					        'id'   => 'wc_origin' 
				       )

		);

	       return $custom_settings;
       } else {
        	return $settings;
       }

}

