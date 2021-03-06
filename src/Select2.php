<?php


	class tad_cmb2_Select2 extends tad_cmb2_AbstractField {

		public static function instance() {
			return new self;
		}

		protected function queue() {
			wp_enqueue_script( 'select2-js' );
			wp_enqueue_script( 'tad-cmb2-select2-init' );
			wp_enqueue_style( 'select2-css' );
		}

		protected function output( $return = false ) {
			$args = array( 'class' => 'select2' );
			$out  = $this->types->select( $args );
			if ( $return ) {
				return $out;
			}

			echo $out;
		}
	}