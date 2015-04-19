<?php


	class tad_cmb2_NestableListUtils {

		/**
		 * @var array
		 */
		protected $elements;

		/**
		 * @var string|int
		 */
		protected $key_value;

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
			$_this            = new self;
			$_this->elements  = $elements;
			$_this->key_value = $key_value;
			if ( ! empty( $key_value ) ) {
				array_walk_recursive( $elements, array(
					$_this,
					'extract_subelements'
				), $key );
			}

			$return = array_map( array( $_this, 'extract_element_key_value' ), (array) $_this->elements );

			return $return;
		}

		private function extract_subelements( $value, $index, $key ) {
			if ( $value->$key == $this->key_value ) {
				$this->elements = isset( $value->children ) ? $value->children : array();
			}
		}

		private function extract_element_key_value( stdClass $el, $key = 'id' ) {
			return $el->$key;
		}
	}
