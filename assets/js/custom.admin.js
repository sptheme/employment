jQuery( document ).ready( function($) {

	var	$quoteSettings = $('#post-quote-settings').hide(),
		$statusSettings = $('#post-status-settings').hide(),
		$videoSettings = $('#post-video-settings').hide(),
		$audioSettings = $('#post-audio-settings').hide(),
		$postFormat    = $('#post-formats-select input[name="post_format"]'),
		
		//var for change template
		$pageTempalte = $('#page_template');
	
	$postFormat.each(function() {
		
		var $this = $(this);

		if( $this.is(':checked') )
			changePostFormat( $this.val() );

	});

	$postFormat.change(function() {

		changePostFormat( $(this).val() );

	});

	function changePostFormat( val ) {
		
		$quoteSettings.hide();
		$statusSettings.hide();
		$videoSettings.hide();
		$audioSettings.hide();

		if( val === 'quote' ) {
			$quoteSettings.show();
		} else if( val === 'status' ) {
			$statusSettings.show();
		} else if( val === 'video' ) {
			$videoSettings.show();
		} else if( val === 'audio' ) {
			$audioSettings.show();
		}

	}
	

});