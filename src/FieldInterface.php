<?php

interface tad_cmb2_FieldInterface
{
    public static function instance();

    public function render(CMB2_Field $field, $escaped_value, $object_id, $object_type, CMB2_Types $types);
}