#Custom Meta Boxes 2 additional fields

A very much draft code for some [CMB2](https://github.com/WebDevStudios/CMB2) addtional field types; right now:

* `select2` - a [select2](http://select2.github.io/) based select control.
* `nestable_list` - a [jquery-nestable](https://github.com/thesabbir/jquery-nestable) based sortable and nestable list.
* `dual_nestable_list` - a [jquery-nestable](https://github.com/thesabbir/jquery-nestable) based sortable and nestable combination of two lists that allows dragging elements from a "source" list to the "destination" list.

## Installation

### GitHub clone
Go the WordPress installation plugin folder and clone the repository there:

	git clone https://github.com/lucatume/cmb2-tad-fields.git
	
and remove the `.git` folder
	
	cd cmb2-tad-fields && rm -rf .git
	
### Manual installation
Download the latest master version [from GitHub](https://github.com/lucatume/cmb2-tad-fields/archive/master.zip) and manually move the folder to the WordPress plugins folder.

## Must-use compatibility
Right now the plugin is not relying on any activation or deactivation hook and it is hence usable as a WordPress must-use plugin.

## Field Types
A code example of the usage and set up of each additional field type can be found in the `examples.php` file. Uncomment its inclusion in the main plugin file to see a demo of the fields in the `page` post type edit screen.

### Select2
This is just a `select`	field powered by the [Select2](https://select2.github.io/) plugin and requires no special set up for it to work but the specification of the `select2` field type.  

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
			'Devora Gearing'
		)
	) );
	
### Nestable List
A [jquery-nestable](https://github.com/thesabbir/jquery-nestable) powered nestable list that will allow any feature allowed by the jQuery plugin, will save the state of order and nesting created by the user.  
To allow for the nestable list to properly work some options must be provided:

* `list_group` - specifying this value allows for elements to be moved from different lists. Defaults to `0`.
* `primary_key` - an attribute of each element in the list that must be set and unique.

To set the list options must be provided in a specific format: each element in the list is an array, each array contains a different value for the `primary_key`; if a `text` value is set the list element will sport that text as label. Each additional field will be stored as a `data-*` attribute on the list element and saved along with it.

	$cmb->add_field( array(
		'name'        => __( 'Nestable list', 'tad_cmb2' ),
		'desc'        => __( 'Click and drag the elements to sort and nest them!', 'tad_cmb2' ),
		'id'          => $prefix . 'nestable_list',
		'type'        => 'nestable_list',
		'list_group'  => 1,
		'primary_key' => 'id',
		'options'     => array(
			// any attribute below will be translated in a `data-*` attribute.
			// the `text` attribute will also be used as the element label
			array( 'id'   => 1, 'text' => 'One' ),
			array( 'id'   => 2, 'text' => 'Two' ),
			array( 'id'   => 3, 'text' => 'Three' ),
			array( 'id'   => 4, 'text' => 'Four' ),
			array( 'id'   => 5, 'text' => 'Five' ),
			array( 'id'   => 6, 'text' => 'Six' )
		)
	) );
	
#### Elements alignment
While the example above might be the case hardcoded lists will be a rare sight; to cope with the dynamic nature of lists elements will be "aligned" on each refresh. This means that if a list was provided 8 elements from a function that's providing 6 now the no more existing two elements will be removed and the list will make the best effort to keep coherency:

* elements will be removed
* child elements of a removed element will be assigned to the parent of the removed element

The best way to understand this is to "play" with the `examples.php` file.

### Dual Nestable List
A [jquery-nestable](https://github.com/thesabbir/jquery-nestable) powered dual nestable list replicating the functions of the single nestable list above with a twist. The idea behind the list is to have a "source" list of elements, the one set using the `options` and a "destination" list, initially empty, the user will "fill" with chosen elements from the "source" one.  
The saved value will be the one of the "destination" list; play with the `examples.php` file for better understanding.
