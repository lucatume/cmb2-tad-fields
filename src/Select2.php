<?php

class tad_cmb2_Select2 implements tad_cmb2_FieldInterface
{
    public static function instance()
    {
        return new self;
    }

    public function render(CMB2_Field $field, $escaped_value, $object_id, $object_type, CMB2_Types $types)
    {
        add_action('admin_enqueue_scripts', array($this, 'queue'));

        $args = array('class' => 'select2');

        echo $types->select($args);
    }

    public function queue()
    {
        wp_enqueue_script('select2-js');
        wp_enqueue_style('select2-css');
    }
}