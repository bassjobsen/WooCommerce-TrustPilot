<div class="wrap"> 

<h2>WooCommerce TrustPilot <?php echo __('Settings','woocommercetrustpilot');?></h2> 

<form method="post" action="options.php"> 
<?php @settings_fields('woocommerce-trustpilot-group'); ?> 
<?php @do_settings_fields('woocommerce-trustpilot-group'); ?> 
<table class="form-table"> 
<tr valign="top"> 
<th scope="row">
<label for="trustpilotemail"><?php echo __('Your unique Trustpilot e-mail-address','woocommercetrustpilot');?></label></th> 
<td>
	
	
	<?php
	
	$trustpilotemail = get_option('trustpilotemail');
	?>
	<input type="text" name="trustpilotemail" value="<?php  echo $trustpilotemail; ?>" />
	
</td> 
</tr> 

<tr valign="top"> 
<th scope="row">
<label for="sendwhen"><?php echo __('Sending','woocommercetrustpilot');?></label></th> 
<td>
	<?php
	$sendwhen = get_option('sendwhen','complete');
	?>
	<input type="radio" value="process" name="sendwhen" <?php echo ($sendwhen==='process')?' checked="checked"':''?>><?php echo __('After processing','woocommercetrustpilot');?><br>
	<input type="radio" value="complete" name="sendwhen"<?php echo ($sendwhen==='complete')?' checked="checked"':''?>><?php echo __('After complete','woocommercetrustpilot');?><br>
</td> 
</tr> 

</table> 
<?php @submit_button(); ?> </form> 
</div>
