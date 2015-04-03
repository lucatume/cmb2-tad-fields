(function ( $, undefined ) {
	'use strict';

	var tad = window.tad || {};
	var bus = tad.bus || _.extend( {}, Backbone.Events );

	var Select2Field = Backbone.View.extend( {
		render: function () {
			this.$el.select2();
		},
		maybeRerender: function ( evt ) {
			if ( evt.target === this.$el.closest( '.cmb-repeat-table' )[0] ) {
				this.$el.select2( 'destroy' );
			}
			this.render();
		},
		initialize: function () {
			bus.on( 'cmb2:add_row', _.bind( this.maybeRerender, this ) );
			this.render();
		}
	} );

	function busUpdate( $row ) {
		return bus.trigger( 'cmb2:add_row', $row );
	}

	$( document ).ready( function () {
		_.each( $( '.select2' ), function ( el ) {
			new Select2Field( {el: $( el )} );
		} );

		$( 'body' ).on( 'cmb2_add_row', busUpdate );
	} );
})( jQuery );