<?php

class tad_cmb2_Scripts
{

    public static function instance()
    {
        return new self;
    }

    public function hook()
    {
        add_action('init', array($this, 'register_components'));
        add_action('init', array($this, 'register_scripts'));
        add_action('init', array($this, 'register_styles'));
    }

    function register_components()
    {
        $this->register_select2();
        $this->register_jquery_nestable();
    }

    public function register_styles()
    {
        wp_register_style('tad-cmb2-nestable-css', plugins_url('/css/nestable.css', TAD_CMB2_ROOT));
        wp_register_style('tad-cmb2-styles', plugins_url('/css/tad_cmb2_styles.css', TAD_CMB2_ROOT));
    }

    public function register_select2()
    {
        wp_register_script('select2-js', plugins_url('/bower_components/select2-dist/dist/js/select2.min.js', TAD_CMB2_ROOT), array('jquery'));
        wp_register_style('select2-css', plugins_url('/bower_components/select2-dist/dist/css/select2.min.css', TAD_CMB2_ROOT));
    }

    public function register_jquery_nestable()
    {
        wp_register_script('jquery-nestable', plugins_url('/bower_components/nestable/jquery.nestable.js', TAD_CMB2_ROOT), array('jquery'));
    }

    public function register_scripts()
    {
        wp_register_script('tad-cmb2-select2-init', plugins_url('/js/tad_cmb2_select2_init.js', TAD_CMB2_ROOT), array('select2-js'));
        wp_register_script('tad-cmb2-nestable-init', plugins_url('/js/tad_cmb2_nestable_init.js', TAD_CMB2_ROOT), array('jquery-nestable'));
    }
}