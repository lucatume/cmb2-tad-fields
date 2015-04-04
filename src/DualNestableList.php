<?php


	class tad_cmb2_DualNestableList extends tad_cmb2_NestableList {

		public static function instance() {
			return new self;
		}

		/**
		 * @return string
		 */
		protected function output( $return = false ) {
			$primary_key = $this->field->args( 'primary_key' );

			if ( ! $primary_key ) {
				return '';
			}
			// get from elements from the field
			$from_elements = $this->field->args( 'options' );
			// get to elements from the field
			$to_elements = json_decode( $this->field->value, true );
			// get the source keys
			$from_keys = $this->get_elements_key_values( $from_elements, $primary_key );
			// prune the to elements removing those that are not in the from elements
			$found       = array();
			$to_elements = $this->prune( $primary_key, $from_keys, $to_elements, $found );
			// remove pruned to elements from from elements
			$to_keys       = $this->get_elements_key_values( $to_elements, $primary_key );
			$from_elements = $this->diff( $primary_key, $to_keys, $from_elements, $found );
			// render the to list
			$to_list = $this->get_list_markup( $to_elements );
			// render the from list
			$from_lists = $this->get_list_markup( $from_elements );

			$id         = $this->field->args( 'id' );
			$list_attrs = array( 'data-group' => $this->field->args( 'list_group' ) );
			$out        = sprintf( '<div class="dd single" data-output="%s" %s>', $id, $this->concat_attrs( $list_attrs ) );
			$out .= $to_list;
			$out .= '</div>';
			$out .= sprintf( '<div class="dd single" %s>', $this->concat_attrs( $list_attrs ) );
			$out .= $from_lists;
			$out .= '</div>';
			$out .= sprintf( '<input type="hidden" name="%s" id="%s" value="%s">', $id, $id, $this->field->value );
			if ( $this->field->args( 'desc' ) ) {
				$out .= sprintf( '<p class="cmb2-metabox-description">%s</p>', $this->field->args( 'description' ) );
			}

			if ( $return ) {
				return $out;
			}

			echo $out;
		}

	}