<?php

// exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


// check if class already exists
if( !class_exists('c5_acf_field_ai') ) :


class c5_acf_field_ai extends acf_field {
	
	
	/*
	*  __construct
	*
	*  This function will setup the field type data
	*
	*  @type	function
	*  @date	5/03/2014
	*  @since	5.0.0
	*
	*  @param	n/a
	*  @return	n/a
	*/
	
	function __construct( $settings ) {
		
		/*
		*  name (string) Single word, no spaces. Underscores allowed
		*/
		
		$this->name = 'ai';
		
		
		/*
		*  label (string) Multiple words, can include spaces, visible when selecting a field type
		*/
		
		$this->label = __('Purchase Order AI', 'acf');
		
		
		/*
		*  category (string) basic | content | choice | relational | jquery | layout | CUSTOM GROUP NAME
		*/
		
		$this->category = 'basic';
		
		
		/*
		*  defaults (array) Array of default settings which are merged into the field object. These are used later in settings
		*/
		
		$this->defaults = array(
			// 'font_size'	=> 14,
			// 'default_value'	=> '',
		);
		
		
		/*
		*  l10n (array) Array of strings that are used in JavaScript. This allows JS strings to be translated in PHP and loaded via:
		*  var message = acf._e('ai', 'error');
		*/
		
		$this->l10n = array(
			'error'	=> __('Error! Please enter a higher value', 'acf'),
		);
		
		
		/*
		*  settings (array) Store plugin settings (url, path, version) as a reference for later use with assets
		*/
		
		$this->settings = $settings;
		
		
		// do not delete!
    	parent::__construct();
    	
	}
	
	
	function render_field_settings( $field ) {}
	
	
	function render_field( $field ) {
		global $wpdb;
		if (empty($field['value'])){
			$get = $wpdb->get_results(" 
			SELECT 
				* 
			FROM 
				".$wpdb->prefix."posts as posts 
			LEFT OUTER JOIN 
				".$wpdb->prefix."postmeta as meta 
			ON 
				posts.ID = meta.post_ID 
			AND 
				meta.meta_key='purchase_order' 
			WHERE 
				post_type='purchase_orders' 
			AND 
				posts.post_status='publish' 
			ORDER BY 
				meta_value 
			DESC
			LIMIT 1
			");
			foreach ($get as $values){
				$order_num = $values->meta_value;
				$new_num = $order_num + 1;
				$field['value'] = $new_num;
			}
		}
		
		?>
		<input type="number" name="<?php echo esc_attr($field['name']) ?>" 
			value="<?php echo $field['value'] ? esc_attr($field['value']) : 1 ?>"/>
		<?php
		
	}
	
	function validate_value( $valid, $value, $field, $input ){

		// min value
		if( $value < 1 ) {
			$valid = __('Value must be equal to or higher than 1', 'acf');
			
		}
		
		return $valid;
		
	}
	
	
}


// initialize
new c5_acf_field_ai( $this->settings );


// class_exists check
endif;

?>