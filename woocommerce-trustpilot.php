<?php
/*
Plugin Name: WooCommerce Trustpilot
Depends: WooCommerce
Plugin URI: https://github.com/bassjobsen/woocommerce-trustpilot
Description: Send the Trustpilot's BCC email after order processing
Version: 1.0
Author: Bass Jobsen
Author URI: http://bassjobsen.weblogs.fm/
License: GPLv2
*/

/*  Copyright 2013 Bass Jobsen (email : bass@w3masters.nl)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
if(file_exists( ABSPATH . 'wp-content/plugins/woocommerce/classes/')){$class_path  =  ABSPATH . 'wp-content/plugins/woocommerce/classes/';}
else $class_path  =  ABSPATH . 'wp-content/plugins/woocommerce/includes/';
$processclass = 'emails/class-wc-email-customer-processing-order.php';
$completeclass = 'emails/class-wc-email-customer-completed-order.php';
$emailclass = 'abstracts/abstract-wc-email.php';
$settingsapi = 'abstracts/abstract-wc-settings-api.php';
require_once(ABSPATH . 'wp-content/plugins/woocommerce/woocommerce.php');
require_once($class_path.$settingsapi);
require_once($class_path.$emailclass);
require_once($class_path.$processclass);  
require_once($class_path.$completeclass);

class WC_Email_Customer_Processing_Order_BCC extends WC_Email_Customer_Processing_Order
{
	function get_subject()
	{ 
		return WC_Email_Customer_Completed_Order::get_subject().' ('.__('Order ','woocommercetrustpilot').' #'.(string)$this->object->id.')';
	}	
	
	function get_headers()
	{ 
		return WC_Email::get_headers().'BCC: '.get_option('trustpilotemail')."\r\n";
	}
}	

class WC_Email_Customer_Completed_Order_BCC extends WC_Email_Customer_Completed_Order
{
	
	function get_subject()
	{ 
		return WC_Email_Customer_Completed_Order::get_subject().' ('.__('Order ','woocommercetrustpilot').' #'.(string)$this->object->id.')';
	}	
	
	
	function get_headers()
	{ 
		return WC_Email::get_headers().'BCC: '.get_option('trustpilotemail')."\r\n";
	}
}	


/**
 * Check if WooCommerce is active
 **/
if ( !in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) 
{
// Put your plugin code here
//die('install Woocommerce First');
}

if(!class_exists('WooCommerce_Trustpilot')) 
{ 
	
class WooCommerce_Trustpilot 
{ 
/*
* Construct the plugin object 
*/ 
public function __construct() 
{ 
	load_plugin_textdomain( 'woocommercetrustpilot', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
 	// register actions 
	add_action('admin_init', array(&$this, 'admin_init')); 
	add_action('admin_menu', array(&$this, 'add_menu')); 
	
	
	
	add_filter( 'init', array( $this, 'init' ) );
} 
// END public 

/** 
 * Activate the plugin 
**/ 
public static function activate() 
{ 
	// Do nothing 
} 
// END public static function activate 

/** 
 * Deactivate the plugin 
 * 
**/ 
public static function deactivate() 

{ // Do nothing 
} 
// END public static function deactivate 

/** 
 * hook into WP's admin_init action hook 
 * */ 
 
public function admin_init() 
{ 
	// Set up the settings for this plugin 
	
	$this->init_settings(); 
	// Possibly do additional admin_init tasks 
} 
// END public static function activate - See more at: http://www.yaconiello.com/blog/how-to-write-wordpress-plugin/#sthash.mhyfhl3r.JacOJxrL.dpuf

/** * Initialize some custom settings */ 
public function init_settings() 
{ 
	// register the settings for this plugin 
	register_setting('woocommerce-trustpilot-group', 'trustpilotemail'); 
	register_setting('woocommerce-trustpilot-group', 'sendwhen'); 
} // END public function init_custom_settings()


/** * add a menu */ 
public function add_menu() 
{
	 
	 add_options_page('WooCommerce TrustPilot Settings', 'WooCommerce TrustPilot', 'manage_options', 'woocommerce-trustpilot', array(&$this, 'woocommercetrustpilot_settings_page'));
} // END public function add_menu() 

/** * Menu Callback */ 
public function woocommercetrustpilot_settings_page() 
{ 
	if(!current_user_can('manage_options')) 
	{ 
		wp_die(__('You do not have sufficient permissions to access this page.')); 
	
	} 
// Render the settings template 

include(sprintf("%s/templates/settings.php", dirname(__FILE__))); 

} 
// END public function plugin_settings_page() 

	



function init()
{

add_action( 'woocommerce_email', 'bccemail' );

function bccemail($email_class)
{
   //see: http://docs.woothemes.com/document/unhookremove-woocommerce-emails/
   
   if(get_option('sendwhen','complete')==='complete')
   {
   $newemail_class = new WC_Email_Customer_Completed_Order_BCC();
   remove_action('woocommerce_order_status_completed_notification', array(&$email_class, 'trigger'));
   add_action('woocommerce_order_status_completed_notification', array(&$newemail_class, 'trigger'));	
   }
   else
   {
			$newemail_class = new WC_Email_Customer_Processing_Order_BCC();
			remove_action('woocommerce_order_status_completed_notification', array(&$email_class, 'trigger'));
			add_action('woocommerce_order_status_completed_notification', array(&$newemail_class, 'trigger'));	
   }	   
   
}	

}



} // END class 

}

if(class_exists('WooCommerce_Trustpilot')) 
{ // Installation and uninstallation hooks 
	register_activation_hook(__FILE__, array('WooCommerce_Trustpilot', 'activate')); 
	register_deactivation_hook(__FILE__, array('WooCommerce_Trustpilot', 'deactivate')); 
	
	$woocommercetrustpilot = new WooCommerce_Trustpilot();
	// Add a link to the settings page onto the plugin page 
	if(isset($woocommercetrustpilot))
	{
		
		 function woocommercetrustpilot_settings_link($links) 
		 { 
			 $settings_link = '<a href="options-general.php?page=woocommerce-trustpilot">'.__('Settings','woocommercetrustpilot').'</a>';
			 array_unshift($links, $settings_link); 
			
			 return $links; 
		 } 	
		 $plugin = plugin_basename(__FILE__); 
		 	
		
		 add_filter("plugin_action_links_$plugin", 'woocommercetrustpilot_settings_link'); 
	}
	
}

