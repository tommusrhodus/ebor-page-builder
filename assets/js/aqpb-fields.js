/**
 * AQPB Fields JS
 *
 * JS functionalities for some of the default fields
 */

jQuery.noConflict();

/** Fire up jQuery - let's dance! 
 */
jQuery(document).ready(function($){

	/** Colorpicker Field
	----------------------------------------------- */
	function aqpb_colorpicker() {
		$('#page-builder .input-color-picker').each(function(){
			var $this	= $(this),
				parent	= $this.parent();
				
			$this.wpColorPicker();
		});
	}
	
	aqpb_colorpicker();
	
	$('ul.blocks').bind('sortstop', function() {
		aqpb_colorpicker();
	});
	
	/** Media Uploader
	----------------------------------------------- */	
	$(document).on('click', '.aq_upload_button', function(event) {
		var $clicked = $(this), frame,
			input_id = $clicked.prev().attr('id'),
			media_type = $clicked.attr('rel');
			
		event.preventDefault();
		
		// If the media frame already exists, reopen it.
		if ( frame ) {
			frame.open();
			return;
		}
		
		// Create the media frame.
		frame = wp.media.frames.aq_media_uploader = wp.media({
			// Set the media type
			library: {
				type: media_type
			},
			view: {
				
			}
		});
		
		// When an image is selected, run a callback.
		frame.on( 'select', function() {
			// Grab the selected attachment.
			var attachment = frame.state().get('selection').first();
			
			$('#' + input_id).val(attachment.attributes.url);
			
			if(media_type == 'image') $('#' + input_id).parent().parent().parent().find('.screenshot img').attr('src', attachment.attributes.url);
			
		});

		frame.open();
	
	});
			
	/** Sortable Lists
	----------------------------------------------- */
	// AJAX Add New <list-item>
	function aq_sortable_list_add_item(action_id, items) {
		
		var blockID = items.attr('rel'),
			numArr = items.find('li').map(function(i, e){
				return $(e).attr("rel");
			});
		
		var maxNum = Math.max.apply(Math, numArr);
		if (maxNum < 1 ) { maxNum = 0};
		var newNum = maxNum + 1;
		
		var data = {
			action: 'aq_block_'+action_id+'_add_new',
			security: $('#aqpb-nonce').val(),
			count: newNum,
			block_id: blockID
		};
		
		$.post(ajaxurl, data, function(response) {
			var check = response.charAt(response.length - 1);
			
			//check nonce
			if(check == '-1') {
				alert('An unknown error has occurred');
			} else {
				items.append(response);
			}
						
		});
	};
	
	// Initialise sortable list fields
	function aq_sortable_list_init() {
		$('.aq-sortable-list').sortable({
			containment: "parent",
			placeholder: "ui-state-highlight"
		});
	}
	aq_sortable_list_init();
	
	$('ul.blocks').bind('sortstop', function() {
		aq_sortable_list_init();
	});
	
	
	$(document).on('click', 'a.aq-sortable-add-new', function() {
		var action_id = $(this).attr('rel'),
			items = $(this).parent().children('ul.aq-sortable-list');
			
		aq_sortable_list_add_item(action_id, items);
		aq_sortable_list_init
		return false;
	});
	
	// Delete Sortable Item
	$(document).on('click', '.aq-sortable-list a.sortable-delete', function() {
		var $parent = $(this.parentNode.parentNode.parentNode);
		$parent.children('.block-tabs-tab-head').css('background', 'red');
		$parent.slideUp(function() {
			$(this).remove();
		}).fadeOut('fast');
		return false;
	});
	
	// Open/Close Sortable Item
	$(document).on('click', '.aq-sortable-list .sortable-handle a', function() {
		var $clicked = $(this);
		
		$clicked.addClass('sortable-clicked');
		
		$clicked.parents('.aq-sortable-list').find('.sortable-body').each(function(i, el) {
			if($(el).is(':visible') && $(el).prev().find('a').hasClass('sortable-clicked') == false) {
				$(el).slideUp();
			}
		});
		$(this.parentNode.parentNode.parentNode).children('.sortable-body').slideToggle();
		
		$clicked.removeClass('sortable-clicked');
		
		return false;
	});
	
	$(document).on('click', '.block-settings button', function(){
		var thisName = $(this).attr('name');
		var output = '';
		
		switch (thisName) {
			case 'B':
				output = '<b>Bold Text</b>';
			break;
			case 'I':
				output = '<em>Italic Text</em>';
			break;
			case 'BQ':
				output = '<blockquote>Quote Text</blockquote>';
			break;
			case 'LINK':
				output = '<a href=\"Your Link Here\">Link Text</a>';
			break;
			case 'OL':
				output = '<ol><li>List Item</li><li>List Item</li></ol>';
			break;
			case 'UL':
				output = '<ul><li>List Item</li><li>List Item</li></ul>';
			break;
			case 'IMG':
				output = '<img src=\"Your Image URL\" alt=\"Alt Text\" />';
			break;
			case 'H1':
				output = '<h1>Heading 1</h1>';
			break;
			case 'H2':
				output = '<h2>Heading 2</h2>';
			break;
			case 'H3':
				output = '<h3>Heading 3</h3>';
			break;
			case 'H4':
				output = '<h4>Heading 4</h4>';
			break;
			case 'H5':
				output = '<h5>Heading 5</h5>';
			break;
			case 'H6':
				output = '<h6>Heading 6</h6>';
			break;
		}
		
		jQuery(this).parent().find('textarea').val( jQuery(this).parent().find('textarea').val()+output);
		
		return false;
	});	
	
	$(window).scroll(function(){
		if( $(window).scrollTop() > $('#page-builder-column.metabox-holder').offset().top && $(window).height() >  $('#page-builder-column.metabox-holder').height() ){
			$('#page-builder-column.metabox-holder').stop().animate({
				'padding-top' : $(window).scrollTop() - 45
			}, 100);
		}
		if( $(window).scrollTop() == 0 ) {
			$('#page-builder-column.metabox-holder').stop().animate({
				'padding-top' : 0
			}, 100);
		}
	});	
		
});