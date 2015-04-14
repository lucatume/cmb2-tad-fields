<?php

	function tad_extract_keys_from_list( $post_id, $meta_key, $key = 'id', $key_value = null ) {
		return tad_cmb2_NestableList::extract_elements_from_list( $post_id, $meta_key, $key, $key_value );
	}
