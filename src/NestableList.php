<?php


	class tad_cmb2_NestableList extends tad_cmb2_AbstractField {

		public static function instance() {
			return new self;
		}

		private function get_nested_list( $list_class = 'dd single' ) {

			$elements = $this->get_aligned_field_list_elements();

			$id  = $this->field->args['id'];
			$out = sprintf( '<div class="dd single" id="%s" data-output="%s">', $id, $id );
			$out .= $this->get_list_markup( $elements );
			$out .= '</div>';
			$out .= sprintf( '<input type="hidden" name="%s" id="%s" value="%s">', $id, $id, $this->field->value );

			return $out;
		}

		private function get_list_markup( $elements ) {
			$out                     = '';
			$element_attrs_whitelist = array();
			if ( ! empty( $elements ) ) {
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
			}

			return $out;
		}

		private function align_items( $key, array $legit, array $items = null ) {
			if ( empty( $items ) ) {
				return $legit;
			}
			$legit_keys = array();
			foreach ( $legit as $entry => $data ) {
				if ( empty( $data[ $key ] ) ) {
					continue;
				}
				$legit_keys[] = $data[ $key ];
			}

			// any item that's not in the options remove
			$found    = array();
			$filtered = $this->prune( $key, $legit_keys, $items, $found );
			// any item that's in the options and not in the items append to the items
			$to_add = array_diff( $legit_keys, $found );

			foreach ( $legit as $value ) {
				if ( in_array( $value[ $key ], $to_add ) ) {
					$filtered[] = $value;
				}
			}

			return $filtered;
		}

		private function prune( $key, $legit_keys, $items, &$found_keys ) {
			$pruned = array();
			foreach ( $items as $item => $data ) {
				if ( isset( $data['children'] ) ) {
					$data['children'] = $this->prune( $key, $legit_keys, $data['children'], $found_keys );
				}
				if ( isset( $data[ $key ] ) && in_array( $data[ $key ], $legit_keys ) ) {
					$pruned[]     = $data;
					$found_keys[] = $data[ $key ];
					continue;
				}
				// if not saving data save the detached children
				if ( isset( $data['children'] ) ) {
					$pruned = array_merge( $pruned, $data['children'] );
				}
			}

			return $pruned;
		}

		private function get_aligned_field_list_elements() {
			if ( isset( $this->field->args['primary_key'] ) && isset( $this->field->value ) ) {
				$items = $this->align_items( $this->field->args['primary_key'], $this->field->args['options'], json_decode( $this->field->value, true ) );

				return $items;
			} else {
				$items = $this->field->args['options'];

				return $items;
			}
		}

		protected function queue() {
			wp_enqueue_script( 'tad-cmb2-nestable-init' );
			wp_enqueue_style( 'tad-cmb2-nestable-css' );
		}

		protected function output() {
			echo $this->get_nested_list();
		}

	}