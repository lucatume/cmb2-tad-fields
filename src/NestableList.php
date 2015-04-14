<?php


	class tad_cmb2_NestableList extends tad_cmb2_AbstractField {

		public static function instance() {
			return new self;
		}

		protected function get_nested_list( $list_class = 'dd single' ) {

			$elements = $this->get_aligned_field_list_elements();

			$id         = $this->field->args['id'];
			$list_attrs = array( 'data-group' => $this->field->args( 'list_group' ) );
			$out        = sprintf( '<div class="dd single updating-list" data-output="%s" %s>', $id, $this->concat_attrs( $list_attrs ) );
			$out .= $this->get_list_markup( $elements );
			$out .= '</div>';
			$out .= sprintf( '<input type="hidden" name="%s" id="%s" value="">', $id, $id );
			if ( $this->field->args( 'desc' ) ) {
				$out .= sprintf( '<p class="cmb2-metabox-description">%s</p>', $this->field->args( 'description' ) );
			}

			return $out;
		}

		protected function get_list_markup( $elements ) {
			$element_attrs_whitelist = array();

			$out = empty( $elements ) ? $this->get_emtpy_element_markup() : $this->get_list_elements_markup( $elements, $element_attrs_whitelist );

			return $out;
		}

		protected function align_items( $key, array $legit, array $items = null ) {
			if ( empty( $items ) ) {
				return $legit;
			}

			$legit_elements = $this->get_flattened_elements( $legit, $key );

			// any item that hasn't got a legit key remove
			$found    = array();
			$filtered = $this->prune( $key, $legit_elements, $items, $found );

			// any item that's in the options and not in the items append to the items
			$to_add = array_diff( $legit_elements, $found );

			foreach ( $legit as $value ) {
				if ( in_array( $value[ $key ], $to_add ) ) {
					$filtered[] = $value;
				}
			}

			return $filtered;
		}

		protected function prune( $key, array $legit_elements, $items = array(), array &$found ) {
			$legits_keys = $this->get_elements_keys( $legit_elements, $key );
			if ( empty( $items ) ) {
				return array();
			}
			$pruned = array();
			foreach ( $items as $item => $data ) {
				if ( isset( $data['children'] ) ) {
					$data['children'] = $this->prune( $key, $legit_elements, $data['children'], $found );
				}
				if ( isset( $data[ $key ] ) && in_array( $data[ $key ], $legits_keys ) ) {
					$pruned[] = $data;
					$found[]  = $data;
					continue;
				}
				// if not saving data save the detached children
				if ( isset( $data['children'] ) ) {
					$pruned = array_merge( $pruned, $data['children'] );
				}
			}

			return $pruned;
		}

		protected function get_aligned_field_list_elements() {
			$items = array();
			if ( isset( $this->field->args['primary_key'] ) && isset( $this->field->value ) ) {
				$items = $this->align_items( $this->field->args['primary_key'], $this->field->args['options'], json_decode( $this->field->value, true ) );
			} else {
				$items = $this->field->args['options'];
			}

			return $items;
		}

		protected function queue() {
			wp_enqueue_script( 'tad-cmb2-nestable-init' );
			wp_enqueue_style( 'tad-cmb2-nestable-css' );
		}

		protected function output( $return = false ) {
			$out = $this->get_nested_list();
			if ( $return ) {
				return $out;
			}

			echo $out;
		}

		/**
		 * @param array $elements
		 * @param       $key
		 *
		 * @return array
		 */
		protected function get_flattened_elements( array $elements, $key ) {
			$legit_keys = array();
			foreach ( $elements as $entry => $data ) {
				if ( isset( $data['children'] ) ) {
					$ret        = $this->get_flattened_elements( $data['children'], $key );
					$legit_keys = array_merge( $legit_keys, $ret );
				}
				if ( empty( $data[ $key ] ) ) {
					continue;
				}
				$legit_keys[] = $data;
			}

			return $legit_keys;
		}

		protected function diff( $key, array $diff_elements, array $items = null, array &$found_keys ) {
			$diffed             = array();
			$diff_elements_keys = $this->get_elements_keys( $diff_elements, $key );
			foreach ( $items as $item => $element ) {
				if ( isset( $element['children'] ) ) {
					$element['children'] = $this->diff( $key, $diff_elements, $element['children'], $found_keys );
				}
				if ( isset( $element[ $key ] ) && in_array( $element[ $key ], $diff_elements_keys ) ) {
					// if not saving data save the detached children
					if ( isset( $element['children'] ) ) {
						$diffed = array_merge( $diffed, $element['children'] );
					}
					continue;
				}
				$diffed[]     = $element;
				$found_keys[] = $element[ $key ];
			}

			return $diffed;
		}

		/**
		 * @param array $elements
		 * @param       $key
		 */
		protected function get_elements_keys( array $elements, $key ) {
			$legit_keys = array();
			foreach ( $elements as $element ) {
				$legit_keys[] = $element[ $key ];
			}

			return $legit_keys;
		}

		/**
		 * @param $elements
		 * @param $element_attrs_whitelist
		 *
		 * @return string
		 */
		protected function get_list_elements_markup( $elements, $element_attrs_whitelist ) {
			$out = '<ol class="dd-list">';

			foreach ( $elements as $key => $data ) {
				$children      = '';
				$element_attrs = array();
				foreach ( $data as $sub_key => $sub_value ) {
					if ( $sub_key != 'children' ) {
						// add the key as a data attribute for the list element
						$attr                   = in_array( $sub_key, $element_attrs_whitelist ) ? $sub_key : 'data-' . $sub_key;
						$element_attrs[ $attr ] = $sub_value;
					} else {
						$children = $this->get_list_markup( $sub_value );
					}
				}
				// render the element
				ksort( $element_attrs );
				$text                   = empty( $element_attrs['data-text'] ) ? '' . reset( $element_attrs ) : $element_attrs['data-text'];
				$element_attrs['class'] = empty( $element_attrs['class'] ) ? 'dd-item' : 'dd-item ' . $element_attrs['class'];
				$out .= sprintf( '<li %s><div class="dd-handle">%s</div>%s', $this->concat_attrs( $element_attrs ), $text, $children );
				// close the list element
				$out .= '</li>';
			}

			$out .= '</ol>';

			return $out;
		}

		protected function get_emtpy_element_markup( $attrs = array( 'class' => 'dd-empty' ) ) {
			return sprintf( '<div %s></div>', $this->concat_attrs( $attrs ) );
		}

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