<?php

	/**
	 * Here are some example functions using the fields the plugin adds.
	 */

	add_action( 'cmb2_init', 'cmb2_sample_metaboxes' );

	/**
	 * Define the metabox and field configurations.
	 */
	function cmb2_sample_metaboxes( $meta_boxes = null ) {

		// Start with an underscore to hide fields from custom fields list
		$prefix = '_yourprefix_';

		/**
		 * Initiate the metabox
		 */
		$cmb = new_cmb2_box( array(
			'id'           => 'test_metabox',
			'title'        => __( 'theAverageDev fields test Metabox', 'cmb2' ),
			'object_types' => array(
				'page'
			),
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true
		) );

		// Single nestable list
		$cmb->add_field( array(
			'name'        => __( 'Nestable list', 'tad_cmb2' ),
			'desc'        => __( 'Click and drag the elements to sort and nest them!', 'tad_cmb2' ),
			'id'          => $prefix . 'nestable_list',
			'type'        => 'nestable_list',
			'list_group'  => 1,
			'options'     => array(

				// any attribute below will be translated in a `data-*` attribute.
				// the `text` attribute will also be used as the element label
				array(
					'id'   => 1,
					'text' => 'One'
				),
				array(
					'id'   => 2,
					'text' => 'Two'
				),
				array(
					'id'   => 3,
					'text' => 'Three'
				),
				array(
					'id'   => 4,
					'text' => 'Four'
				),
				array(
					'id'   => 5,
					'text' => 'Five'
				),
				array(
					'id'   => 6,
					'text' => 'Six'
				)
			),
			// the attribute to use to sort the elements
			// has to be defined in all the list elements
			// has to be unique in all the list elements
			'primary_key' => 'id'
		) );

		// Select2 powered select
		$cmb->add_field( array(
			'name'    => __( 'Select2', 'tad_cmb2' ),
			'desc'    => __( 'Type some letters to have select2 autocomplete kick in!', 'tad_cmb2' ),
			'id'      => $prefix . 'select2',
			'type'    => 'select2',
			'options' => array(
				'Tony Penrod',
				'Columbus Stiffler',
				'Kelsi Bucklin',
				'Leticia Beecher',
				'Jeanmarie Ehrlich',
				'Samatha Woolverton',
				'Angelita Alleyne',
				'Tabitha Roberds',
				'Lorelei Vaccaro',
				'Camelia Marzette',
				'Anjanette Laroche',
				'Cole Fortenberry',
				'Rachell Marbury',
				'Edwardo Debonis',
				'Hiroko Merck',
				'Maragret Cromartie',
				'Arletta Brice',
				'Walter Jonason',
				'Ashanti Constante',
				'Lakiesha Bruening',
				'Sunday Bahl',
				'Abraham Perry',
				'Lauri Hanson',
				'Russel Pfeil',
				'Margarito Davey',
				'Judith Chan',
				'Darcy Polanco',
				'Nobuko Abarca',
				'Bernetta Plamondon',
				'Neva Saari',
				'Nell Stradley',
				'Clark Palm',
				'Erica Via',
				'Marth Korb',
				'Stewart Fang',
				'Selina Rhodus',
				'Daniell Quesenberry',
				'Boris Ashburn',
				'Cruz Alvey',
				'Floria Eidt',
				'Joselyn Schurman',
				'Sonja Watford',
				'Krista Chaffin',
				'Brooks Egerton',
				'Jeni Wheatley',
				'Rachelle Kinzel',
				'Shantelle Villagomez',
				'Winona Brocato',
				'Annalee Falgoust',
				'Devora Gearing'
			)
		) );

		// Dual nestable list
		$cmb->add_field( array(
			'name'        => __( 'Dual nestable list', 'tad_cmb2' ),
			'desc'        => __( 'Click and drag the elements to move them from one list to another, sort and nest them!', 'tad_cmb2' ),
			'id'          => $prefix . 'dual_nestable_list',
			'type'        => 'dual_nestable_list',
			'list_group'  => 2,
			'options'     => array(
				// any attribute below will be translated in a `data-*` attribute.
				// the `text` attribute will also be used as the element label
				array(
					'id'   => 1,
					'text' => 'One'
				),
				array(
					'id'   => 2,
					'text' => 'Two'
				),
				array(
					'id'   => 3,
					'text' => 'Three'
				),
				array(
					'id'   => 4,
					'text' => 'Four'
				),
				array(
					'id'   => 5,
					'text' => 'Five'
				),
				array(
					'id'   => 6,
					'text' => 'Six'
				)
			),
			// the attribute to use to sort the elements
			// has to be defined in all the list elements
			// has to be unique in all the list elements
			'primary_key' => 'id'
		) );
	}
