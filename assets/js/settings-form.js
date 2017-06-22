( function( $ ) {

	$( 'body' ).on( 'change', '.fl-builder-settings-fields select[name="use_pods"]', function ( e ) {

		var $parentTab               = $( this ).closest( '#fl-builder-settings-tab-content' );
		var $sourceSelectField       = $parentTab.find( 'select[name="data_source"]' );
		var $sourceSelectFieldParent = $sourceSelectField.closest( 'tbody' );

		if ( 'no' !== e.target.value ) {
			$sourceSelectField.val( 'custom_query' );
			$sourceSelectField.trigger( 'change' );

			$sourceSelectFieldParent.hide();
		}
		else {
			$sourceSelectFieldParent.show();
		}

	} );

} )( jQuery );
