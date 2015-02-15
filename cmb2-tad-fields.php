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

// register autoloading
spl_autoload_register('tad_cmb2_autoload');
function tad_cmb2_autoload($class_name)
{
    $classes = array(
        'phpQuery' => '/vendor/phpQuery/phpQuery/phpQuery.php'
    );
    if (in_array($class_name, array_keys($classes))) {
        $path = dirname(__FILE__) . $classes[$class_name];
        if (file_exists($path)) {
            include $path;
        }
    }
}

add_action('init', 'tad_cmb2_register_scripts_and_styles');
function tad_cmb2_register_scripts_and_styles()
{
    wp_register_script('select2-js', plugins_url('/vendor/select2/dist/js/select2.min.js', __FILE__), array('jquery'));
    wp_register_style('select2-css', plugins_url('/vendor/select2/dist/css/select2.min.css', __FILE__));
    wp_register_script('jquery-nestable', plugins_url('/vendor/nestable/js/jquery.nestable.js', __FILE__), array('jquery'));
    wp_register_style('tad_cmb2_nestable_css', plugins_url('/vendor/nestable/css/nestable.css', __FILE__));
    wp_register_style('tad_cmb2_styles', plugins_url('/css/tad_cmb2_styles.css', __FILE__));

    wp_register_script('tad_cmb2_select2_init', plugins_url('/js/tad_cmb2_select2_init.js', __FILE__), array('select2-js'));
    wp_register_script('tad_cmb2_nestable_init', plugins_url('/js/tad_cmb2_nestable_init.js', __FILE__), array('jquery-nestable'));
}

function tad_cmb2_default_scripts_styles()
{
    wp_enqueue_style('tad_cmb2_styles');
}


/**
 * Select2 powered select
 */
add_action('cmb2_render_tad_select2', 'tad_cmb2_the_select2', 10, 5);
function tad_cmb2_get_select2(CMB2_Field $field, $escaped_value, $object_id, $object_type, CMB2_Types $types)
{
    tad_cmb2_default_scripts_styles();
    wp_enqueue_style('select2-css');
    wp_enqueue_script('tad_cmb2_select2_init');
    $args = array('class' => 'select2');

    return $types->select($args);
}

function tad_cmb2_the_select2(CMB2_Field $field, $escaped_value, $object_id, $object_type, CMB2_Types $types)
{
    echo tad_cmb2_get_select2($field, $escaped_value, $object_id, $object_type, $types);
}

/**
 * Select2 powered select and a button beside it
 */
add_action('cmb2_render_tad_select2_add', 'tad_cmb2_the_select2_action', 10, 5);
function tad_cmb2_get_select2_action(CMB2_Field $field, $escaped_value, $object_id, $object_type, CMB2_Types $types)
{
    tad_cmb2_default_scripts_styles();
    wp_enqueue_style('select2-css');
    wp_enqueue_script('tad_cmb2_select2_init');
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
    echo tad_cmb2_get_select2_action($field, $escaped_value, $object_id, $object_type, $types);
}

/**
 * Sortable list of content
 */
add_action('cmb2_render_tad_nested_list', 'tad_cmb2_the_nested_list', 10, 5);
function tad_cmb2_the_nested_list(CMB2_Field $field, $escaped_value, $object_id, $object_type, CMB2_Types $types)
{
    echo tad_cmb2_get_nested_list($field, $types);
}

function tad_cmb2_get_nested_list(CMB2_Field $field, CMB2_Types $types, $list_class = 'dd single')
{
    wp_enqueue_script('tad_cmb2_nestable_init');
    wp_enqueue_style('tad_cmb2_nestable_css');

    $wrapper = tad_cmb2_get_nested_list_wrapper($field, $types, $list_class);

    $open = tad_cmb2_get_nested_list_open($field, $types);

    $element_attrs = tad_cmb2_get_nested_list_element_attrs($field, $types);

    $options = $field->args['options'];

    $out = tad_cmb2_get_nestable_list_markup($element_attrs, $wrapper, $options, $open);

    return $out;
}

/**
 * @param CMB2_Field $field
 * @param CMB2_Types $types
 * @return array|string
 */
function tad_cmb2_get_nested_list_element_attrs(CMB2_Field $field, CMB2_Types $types)
{
    $element_default_attrs = array('class' => 'dd-item');
    $element_attrs = empty($field->args['element_attrs']) ? array() : $field->args['element_attrs'];
    $element_attrs = $types->concat_attrs(array_merge($element_default_attrs, $element_attrs));
    return $element_attrs;
}

/**
 * @param CMB2_Field $field
 * @param CMB2_Types $types
 * @return string
 */
function tad_cmb2_get_nested_list_open(CMB2_Field $field, CMB2_Types $types)
{
    $open = '<ol %s>';
    $open_attrs = empty($field->args['open_attrs']) ? array() : $field->args['open_attrs'];
    $open_default_args = array('class' => 'dd-list');
    $open = sprintf($open, $types->concat_attrs(array_merge($open_default_args, $open_attrs)));
    return $open;
}

/**
 * @param CMB2_Field $field
 * @param CMB2_Types $types
 * @param $list_class
 * @return string
 */
function tad_cmb2_get_nested_list_wrapper(CMB2_Field $field, CMB2_Types $types, $list_class)
{
    $wrapper = '<div %s>';
    $wrapper_attrs = empty($field->args['wrapper_attrs']) ? array() : $field->args['wrapper_attrs'];
    $wrapper_default_args = array('class' => $list_class);
    $wrapper = sprintf($wrapper, $types->concat_attrs(array_merge($wrapper_default_args, $wrapper_attrs)));
    return $wrapper;
}

/**
 * @param $element_attrs
 * @param $wrapper
 * @param $options
 * @param $open
 * @return string
 */
function tad_cmb2_get_nestable_list_markup($element_attrs, $wrapper, $options, $open)
{
    $element_template = '<li' . $element_attrs . ' data-id="%s"><div class="dd-handle">%s</div></li>';

    // open the wrapper
    $out = $wrapper;
    // build the nested list
    $out .= tad_cmb2_render_nested_list($options, $element_template, $open);
    // close list wrapper
    $out .= '</div>';
    return $out;
}

function tad_cmb2_render_nested_list($options, $element_template, $open = null, $close = null)
{
    $out = empty($open) ? '<ol class="dd-list">' : $open;

    foreach ($options as $key => $value) {
        if (!is_array($value)) {
            $out .= sprintf($element_template, $key, $value);
            continue;
        }
        $out .= tad_cmb2_render_nested_list($value, $element_template);
    }

    $out .= empty($close) ? '</ol>' : $close;

    return $out;
}

add_action('cmb2_render_tad_dual_nested_list', 'tad_cmb2_the_dual_nestable_list', 10, 5);
function tad_cmb2_get_dual_nestable_list(CMB2_Field $field, $escaped_value, $object_id, $object_type, CMB2_Types $types)
{
    wp_enqueue_script('tad_cmb2_nestable_init');
    wp_enqueue_style('tad_cmb2_nestable_css');

    $included_wrapper = tad_cmb2_get_nested_list_wrapper($field, $types, 'dd dual');
    $excluded_wrapper = tad_cmb2_get_nested_list_wrapper($field, $types, 'dd dual flat-list');

    $open = tad_cmb2_get_nested_list_open($field, $types);

    $element_attrs = tad_cmb2_get_nested_list_element_attrs($field, $types);

    // @todo: get the options using the meta value
    $included_options = $field->args['options'];
    $excluded_options = array();
    tad_cmb2_flatten_options($field->args['options'], $excluded_options);

    $out = tad_cmb2_get_nestable_list_markup($element_attrs, $included_wrapper, $included_options, $open);
    $out .= tad_cmb2_get_nestable_list_markup($element_attrs, $excluded_wrapper, $excluded_options, $open);

    return $out;
}

function tad_cmb2_the_dual_nestable_list(CMB2_Field $field, $escaped_value, $object_id, $object_type, CMB2_Types $types)
{
    echo tad_cmb2_get_dual_nestable_list($field, $escaped_value, $object_id, $object_type, $types);
}

function tad_cmb2_flatten_options(array $options, array &$flat)
{
    foreach ($options as $option => $value) {
        if (is_array($value)) {
            tad_cmb2_flatten_options($value, $flat);
            continue;
        }
        $flat[] = $value;
    }
}
