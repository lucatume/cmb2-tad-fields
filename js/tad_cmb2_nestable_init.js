(function ( $, window, undefined ) {
	'use strict';

	var tad = window.tad || {};
	var bus = tad.bus || _.extend( {}, Backbone.Events );

	var NestableList = Backbone.View.extend( {
		getLIstValue: function () {
			var newValue = this.$el.nestable( 'serialize' );
			return newValue;
		},
		dispatchChange: function () {
			var newValue = this.getLIstValue();
			bus.trigger( 'tad-cmb2:nestable-list-change', newValue, this.$el );
			this.model.set( 'value', newValue );
		},
		initialize: function () {
			this.$el.nestable().on( 'change', _.bind( this.dispatchChange, this ) );
			this.dispatchChange();
		}
	} );

	var NestableInput = Backbone.View.extend( {
		render: function () {
			var value = JSON.stringify( this.model.get( 'value' ) );
			this.$el.val( value );
		},
		initialize: function () {
			this.listenTo( this.model, 'change', this.render );
		}
	} );

	var NestableListValue = Backbone.Model.extend( {
		value: ''
	} );

	$( document ).ready( function () {
		_.each( $( '.dd' ), function ( list ) {
			var $list = $( list );
			var $input = $list.siblings( 'input[type="hidden"]' );
			var listValue = new NestableListValue();
			new NestableInput( {el: $input, model: listValue} );
			new NestableList( {el: $list, model: listValue} );
		} );
	} );
})( jQuery, window );