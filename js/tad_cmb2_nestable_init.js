(function ( $, window, undefined ) {
	'use strict';

	var tad = window.tad || {};
	var bus = tad.bus || _.extend( {}, Backbone.Events );

	var NestableList = Backbone.View.extend( {
		initNestableList: function () {
			var group = this.$el.data( 'group' ) || 0;
			var maxDepth= this.$el.data( 'max-depth' ) || 0;
			this.$el.nestable( {
				'group': group,
				'maxdepth': maxDepth
			} );
		},
		initialize: function () {
			this.initNestableList();
		}
	} );

	var UpdatingNestableList = NestableList.extend( {
		getLIstValue: function () {
			var newValue = this.$el.nestable( 'serialize' );
			return newValue;
		},
		dispatchChange: function () {
			var newValue = this.getLIstValue();
			bus.trigger( 'tad-cmb2:nestable-list-change', newValue, this.$el );
			this.model.set( 'value', newValue );
		},
		bindEvents: function () {
			this.$el.on( 'change', _.bind( this.dispatchChange, this ) );
		}, initialize: function () {
			this.initNestableList();
			this.bindEvents();
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
			if ( $list.hasClass( 'updating-list' ) ) {
				new UpdatingNestableList( {el: $list, model: listValue} );
			} else {
				new NestableList( {el: $list} );
			}
		} );
	} );
})
( jQuery, window );