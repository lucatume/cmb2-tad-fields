<?php

class tad_cmb2_Fields
{
    public static function instance()
    {
        return new self;
    }

    public function hook()
    {
        add_action('cmb2_render_select2', array(tad_cmb2_Select2::instance(), 'render'), 10, 5);
        add_action('cmb2_render_nestable_list', array(tad_cmb2_NestableList::instance(), 'render'), 10, 5);
    }
}
