<?php

	/**
	 * Extracts the elements unique keys for a list level.
	 *
	 * @param        $post_id   The ID of the post to which the nestable list is a meta.
	 * @param        $meta_key  The meta key the nestable list is saved in
	 * @param string $key       The name of the uniquey key used in the list, defaults to 'id'
	 * @param null   $key_value The optional uniquey key value to grab deeper than root elements.
	 *
	 * @return array An array of unique key values.
	 */
	function tad_extract_keys_from_list( $post_id, $meta_key, $key = 'id', $key_value = null ) {
		return tad_cmb2_NestableListUtils::extract_elements_from_list( $post_id, $meta_key, $key, $key_value );
	}
