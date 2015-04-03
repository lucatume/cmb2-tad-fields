<?php


	abstract class tad_cmb2_AbstractField implements tad_cmb2_FieldInterface {

		/**
		 * @var CMB2_Field
		 */
		protected $field;
		/**
		 * @var string
		 */
		protected $escaped_value;
		/**
		 * @var string|int
		 */
		protected $object_id;
		/**
		 * @var string
		 */
		protected $object_type;
		/**
		 * @var CMB2_Types
		 */
		protected $types;

		public function render( CMB2_Field $field, $escaped_value, $object_id, $object_type, CMB2_Types $types ) {
			$this->initialize( $field, $escaped_value, $object_id, $object_type, $types );
			$this->queue();
			$this->output();
		}

		protected function initialize( CMB2_Field $field, $escaped_value, $object_id, $object_type, CMB2_Types $types ) {
			$this->field         = $field;
			$this->escaped_value = $escaped_value;
			$this->object_id     = $object_id;
			$this->object_type   = $object_type;
			$this->types         = $types;
		}

		/**
		 * @param $element_attrs
		 *
		 * @return string
		 */
		protected function concat_attrs( $element_attrs ) {
			return $this->types->concat_attrs( $element_attrs );
		}

		protected function queue() {
			// no op;
		}

		protected function output($return = false) {
			// no op
		}
	}