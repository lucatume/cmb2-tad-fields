<?php

interface tad_cmb2_FieldInterface
{

    /**
     * Returns a non initialized instance of the field.
     *
     * @return tad_cmb2_FieldInterface
     */
    public static function instance();

    /**
     * Initializes and echoes the field to the page.
     *
     * @param CMB2_Field $field
     * @param            $escaped_value
     * @param            $object_id
     * @param            $object_type
     * @param CMB2_Types $types
     *
     * @return void Echoes to the page.
     */
    public function render(CMB2_Field $field, $escaped_value, $object_id, $object_type, CMB2_Types $types);
}