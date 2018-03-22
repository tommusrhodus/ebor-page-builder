<?php
/**
 * Aqua Page Builder functions
 *
 * This holds the external functions which can be used by the theme
 * Requires the AQ_Page_Builder class
 *
 * @todo - multicheck, image checkbox, better colorpicker
**/

if(class_exists('AQ_Page_Builder')) {
	
	/** 
	 * Core functions
	*******************/
	 
	/* Register a block */
	function aq_register_block($block_class) {
		global $aq_registered_blocks;
		$aq_registered_blocks[strtolower($block_class)] = new $block_class;
	}
	
	/** Un-register a block **/
	function aq_unregister_block($block_class) {
		global $aq_registered_blocks;
		$block_class = strtolower($block_class);
		foreach($aq_registered_blocks as $block) {
			if($block->id_base == $block_class) unset($aq_registered_blocks[$block_class]);
		}
	}
	
	/** Get list of all blocks * */
	function aq_get_blocks( $template_id ) {
		global $aq_page_builder;
		if ( is_a( $aq_page_builder, 'AQ_Page_Builder' ) ) {
			$blocks = $aq_page_builder->get_blocks( $template_id );
		} else {
			return null;
		}
		return $blocks;
	}

	function array_htmlspecialchars(&$input)
	{
	    if (is_array($input))
	    {
	        foreach ($input as $key => $value)
	        {
	            if (is_array($value)) $input[$key] = array_htmlspecialchars($value);
	            else $input[$key] = htmlspecialchars($value);
	        }
	        return $input;
	    }
	    return htmlspecialchars($input);
	}
	
	/**
	 * Page builder CSS Class
	 */
	add_action ('after_setup_theme','ebor_page_builder_pluggable', 10);
	function ebor_page_builder_pluggable(){ 
		
		/**
		 * CSS Size Class
		 */
		if(!( function_exists('aq_css_classes') )){
			function aq_css_classes($block){
				$block = str_replace('span', '', $block);
				$output = 'aq_span' . $block;
				return $output;
			}
		}
		
		/**
		 * Use clearfix class?
		 * true / false
		 */
		if(!( function_exists('aq_css_clearfix') )){
			function aq_css_clearfix(){
				return true;
			}
		}
		
		/**
		 * Shortcode start
		 */
		if(!( function_exists('aq_template_wrapper_start') )){
			function aq_template_wrapper_start($template_id){
				return '<div id="aq-template-wrapper-'.$template_id.'" class="aq-template-wrapper aq_row">';
			}
		}
		
		/**
		 * Shortcode end
		 */
		if(!( function_exists('aq_template_wrapper_end') )){
			function aq_template_wrapper_end(){
				return '</div>';
			}
		}
		
		/**
		 * Individual block start
		 */
		if(!( function_exists('ebor_before_block') )){
			function ebor_before_block($instance){
				extract($instance);
				
				$size = aq_css_classes($size);
				$clearfix = ( aq_css_clearfix() ) ? 'clearfix' : '';
				
				$column_class = $first ? 'aq-first' : '';
				
				echo '<div id="aq-block-'.$template_id.'-'.$number.'" class="aq-block aq-block-'.$id_base.' '.$size.' '.$column_class.' '. $clearfix.'">';
			}
		}
		
		/**
		 * Individual block end
		 */
		if(!( function_exists('ebor_after_block') )){
			function ebor_after_block($instance){
				extract($instance);
				echo '</div>';
			}
		}
		
	}
	
	/** 
	 * Form Field Helper functions
	 *
	 * Provides some default fields for use in the blocks
	********************************************************/
	
	/* Input field - Options: $size = min, small, full */
	function aq_field_input($field_id, $block_id, $input, $size = 'full', $type = 'text') {
		$output = '<input type="'.$type.'" id="'. $block_id .'_'.$field_id.'" class="input-'.$size.'" value="'.$input.'" name="aq_blocks['.$block_id.']['.$field_id.']">';
		
		return $output;
	}
	
	/* Textarea field */
	function aq_field_textarea($field_id, $block_id, $text, $size = 'full', $buttons = false) {
		$output = '';
		if( $buttons == true ){
			$output .= '
				<div class="clear"></div>
		        <button name="B">B</button>
		        <button name="I">I</button>
		        <button name="BQ">Quote</button>
		        <button name="LINK">Link</button>
		        <button name="OL">OL</button>
		        <button name="UL">UL</button>
		        <button name="IMG">IMG</button>
		        <button name="H1">H1</button>
		        <button name="H2">H2</button>
		        <button name="H3">H3</button>
		        <button name="H4">H4</button>
		        <button name="H5">H5</button>
		        <button name="H6">H6</button>
			';
		}
		$output .= '<textarea id="'. $block_id .'_'.$field_id.'" class="textarea-'.$size.'" name="aq_blocks['.$block_id.']['.$field_id.']" rows="5">'.$text.'</textarea>';
		
		return $output;
	}
	
	
	/* Select field */
	function aq_field_select($field_id, $block_id, $options, $selected) {
		$options = is_array($options) ? $options : array();
		$output = '<select id="'. $block_id .'_'.$field_id.'" name="aq_blocks['.$block_id.']['.$field_id.']">';
		foreach($options as $key=>$value) {
			$output .= '<option value="'.$key.'" '.selected( $selected, $key, false ).'>'.htmlspecialchars($value).'</option>';
		}
		$output .= '</select>';
		
		return $output;
	}
	
	/* Multiselect field */
	function aq_field_multiselect($field_id, $block_id, $options, $selected_keys = array()) {
		$output = '<select id="'. $block_id .'_'.$field_id.'" multiple="multiple" class="select of-input" name="aq_blocks['.$block_id.']['.$field_id.'][]">';
		foreach ($options as $key => $option) {
			$selected = (is_array($selected_keys) && in_array($key, $selected_keys)) ? $selected = 'selected="selected"' : '';			
			$output .= '<option id="'. $block_id .'_'.$field_id.'_'. $key .'" value="'.$key.'" '. $selected .' />'.$option.'</option>';
		}
		$output .= '</select>';
		
		return $output;
	}
	
	/* Color picker field */
	function aq_field_color_picker($field_id, $block_id, $color, $default = '') {
		$output = '<div class="aqpb-color-picker">';
			$output .= '<input type="text" id="'. $block_id .'_'.$field_id.'" class="input-color-picker" value="'. $color .'" name="aq_blocks['.$block_id.']['.$field_id.']" data-default-color="'. $default .'"/>';
		$output .= '</div>';
		
		return $output;
	}
	
	/* Single Checkbox */
	function aq_field_checkbox($field_id, $block_id, $check) {
		$output = '<input type="hidden" name="aq_blocks['.$block_id.']['.$field_id.']" value="0" />';
		$output .= '<input type="checkbox" id="'. $block_id .'_'.$field_id.'" class="input-checkbox" name="aq_blocks['.$block_id.']['.$field_id.']" '. checked( 1, $check, false ) .' value="1"/>';
		
		return $output;
	}
	
	/* Multi Checkbox */
	function aq_field_multicheck($field_id, $block_id, $fields = array(), $selected = array()) {
	
	}
	
	/* Media Uploader */
	function aq_field_upload($field_id, $block_id, $media, $media_type = 'image') {
		$output = '<input type="text" id="'. $block_id .'_'.$field_id.'" class="input-full input-upload" value="'.$media.'" name="aq_blocks['.$block_id.']['.$field_id.']">';
		$output .= '<a href="#" class="aq_upload_button button" rel="'.$media_type.'">Upload</a><p></p>';
		
		return $output;
	}
	
	/** 
	 * Misc Helper Functions
	**************************/
	
	/** Get column width
	 * @parameters - $size (column size), $grid (grid size e.g 940), $margin
	 */
	function aq_get_column_width($size, $grid = 940, $margin = 20) {
		
		$columns = range(1,12);
		$widths = array();
		foreach($columns as $column) {
			$width = (( $grid + $margin ) / 12 * $column) - $margin;
			$width = round($width);
			$widths[$column] = $width;
		}
		
		$column_id = absint(preg_replace("/[^0-9]/", '', $size));
		$column_width = $widths[$column_id];
		return $column_width;
	}
	
	/** Recursive sanitize
	 * For those complex multidim arrays 
	 * Has impact on server load on template save, so use only where necessary 
	 */
	function aq_recursive_sanitize($value) {
		if(is_array($value)) {
			$value = array_map('aq_recursive_sanitize', $value);
		} else {
			$value = htmlspecialchars(stripslashes($value));
		}
		return $value;
	}
	
}