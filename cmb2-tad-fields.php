<?php
/**
 * Plugin Name: CMB2 theAverageDev additional fields
 * Plugin URI: http://theAverageDev.com
 * Description: Custom Meta Boxes 2 additional fields.
 * Version: 1.0
 * Author: theAverageDev
 * Author URI: http://theAverageDev.com
 * License: GPL2
 */

// autoloading
require dirname(__FILE__) . '/vendor/autoload_52.php';

add_action('init', 'tad_cmb2_register_scripts_and_styles');
function tad_cmb2_register_scripts_and_styles()
{
    // Bower components
    wp_register_script('select2-js', plugins_url('/bower_components/select2-dist/dist/js/select2.min.js', __FILE__), array('jquery'));
    wp_register_style('select2-css', plugins_url('/bower_components/select2-dist/dist/css/select2.min.css', __FILE__));
    wp_register_script('jquery-nestable', plugins_url('/bower_components/nestable/jquery.nestable.js', __FILE__), array('jquery'));

    // Plugin styles
    wp_register_style('tad-cmb2-nestable-css', plugins_url('/css/nestable.css', __FILE__));
    wp_register_style('tad-cmb2-styles', plugins_url('/css/tad_cmb2_styles.css', __FILE__));

    // Plugin scripts
    wp_register_script('tad-cmb2-select2-init', plugins_url('/js/tad_cmb2_select2_init.js', __FILE__), array('select2-js'));
    wp_register_script('tad-cmb2-nestable-init', plugins_url('/js/tad_cmb2_nestable_init.js', __FILE__), array('jquery-nestable'));
}

function tad_cmb2_default_scripts_styles()
{
    wp_enqueue_style('tad-cmb2-styles');
}


/**
 * Select2 powered select
 */
add_action('cmb2_render_tad_select2', 'tad_cmb2_the_select2', 10, 5);
function tad_cmb2_get_select2(CMB2_Field $field, $escaped_value, $object_id, $object_type, CMB2_Types $types)
{
    $args = array('class' => 'select2');

    return $types->select($args);
}

function tad_cmb2_the_select2(CMB2_Field $field, $escaped_value, $object_id, $object_type, CMB2_Types $types)
{
    tad_cmb2_default_scripts_styles();
    wp_enqueue_style('select2-css');
    wp_enqueue_script('tad-cmb2-select2-init');
    echo tad_cmb2_get_select2($field, $escaped_value, $object_id, $object_type, $types);
}

/**
 * Select2 powered select and a button beside it
 */
add_action('cmb2_render_tad_select2_add', 'tad_cmb2_the_select2_action', 10, 5);
function tad_cmb2_get_select2_action(CMB2_Field $field, $escaped_value, $object_id, $object_type, CMB2_Types $types)
{
    $args = array('class' => 'select2');

    $button_args = $field->args['button'];
    $button_text = empty($button_args['text']) ? 'Ok' : $button_args['text'];
    $default_button_attrs = array('class' => 'button left-margin');
    $button_attrs = empty($button_args['attrs']) ? array() : $button_args['attrs'];
    $button_attrs = $types->concat_attrs(array_merge($default_button_attrs, $button_attrs));
    $button_template = '<input type="button" %s value="%s">';
    $button = sprintf($button_template, $button_attrs, $button_text);

    $doc = phpQuery::newDocument($types->select($args));
    pq('select.select2')->after($button);

    return $doc->htmlOuter();
}

function tad_cmb2_the_select2_action(CMB2_Field $field, $escaped_value, $object_id, $object_type, CMB2_Types $types)
{
    tad_cmb2_default_scripts_styles();
    wp_enqueue_style('select2-css');
    wp_enqueue_script('tad-cmb2-select2-init');
    echo tad_cmb2_get_select2_action($field, $escaped_value, $object_id, $object_type, $types);
}

/**
 * Sortable list of content
 */
add_action('cmb2_render_tad_nested_list', 'tad_cmb2_the_nested_list', 10, 5);
function tad_cmb2_the_nested_list(CMB2_Field $field, $escaped_value, $object_id, $object_type, CMB2_Types $types)
{
    wp_enqueue_script('tad-cmb2-nestable-init');
    wp_enqueue_style('tad-cmb2-nestable-css');
    echo tad_cmb2_get_nested_list($field, $escaped_value, $object_id, $object_type, $types);
}

function tad_cmb2_get_nested_list(CMB2_Field $field, $escaped_value, $object_id, $object_type, CMB2_Types $types, $list_class = 'dd single')
{
    $items = empty($field->value) ? $field->args['options'] : json_decode($field->value);

    $id = $field->args['id'];
    $out = sprintf('<div class="dd single" id="%s" data-output="%s">', $id, $id);
    $out .= tad_cmb2_render_nested_list($items, $field, $types);
    $out .= '</div>';
    $out .= sprintf('<input type="hidden" name="%s" id="%s" value="%s">', $id, $id, $field->value);

    return $out;
}

function tad_cmb2_render_nested_list($options, CMB2_Field $field, CMB2_Types $types)
{
    $out = '<ol class="dd-list">';
    if (!empty($options)) {
        foreach ($options as $key => $data) {
            $children = '';
            $element_attrs = array();
            foreach ($data as $sub_key => $sub_value) {
                if ($sub_key != 'children') {
                    // add the key as a data attribute for the list element
                    $element_attrs['data-' . $sub_key] = $sub_value;
                } else {
                    $children = tad_cmb2_render_nested_list($sub_value, $field, $types);
                }
            }
            // render the element
            ksort($element_attrs);
            $text = empty($element_attrs['data-text']) ? '' . reset($element_attrs) : $element_attrs['data-text'];
            $element_attrs['class'] = empty($element_attrs['class']) ? 'dd-item' : 'dd-item ' . $element_attrs['class'];
            $out .= sprintf('<li %s><div class="dd-handle">%s</div>%s', $types->concat_attrs($element_attrs), $text, $children);
            // close the list element
            $out .= '</li>';
        }
    }
    $out .= '</ol>';

    return $out;
}
