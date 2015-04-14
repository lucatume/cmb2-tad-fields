<?php


	class tad_cmb2_NestableListUtils {

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
		public static function extract_elements_from_list( $post_id, $meta_key, $key = 'id', $key_value = null ) {
			$elements = json_decode( get_post_meta( $post_id, $meta_key, true ) );

			if ( empty( $elements ) ) {
				return array();
			}
			$_this = new self;
			if ( ! empty( $key_value ) ) {
				array_walk_recursive( $elements, array(
					$_this,
					'extract_subelements'
				), $key, $key_value, $elements );
			}

			return array_map( array( $_this, 'extract_element_key_value' ), $elements );
		}

		private function extract_subelements( $value, $index, $key, $key_value, &$elements ) {
			if ( $value->$key == $key_value ) {
				$elements = $value;
			}
		}

		private function extract_element_key_value( stdClass $el, $key = 'id' ) {
			return $el->$key;
		}
	}