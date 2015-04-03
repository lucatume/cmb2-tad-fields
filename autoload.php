<?php
spl_autoload_register('tad_cmb2_autoload');
function tad_cmb2_autoload($class){
    $map = array(
'tad_cmb2_FieldInterface' => '/src/FieldInterface.php',
'tad_cmb2_Fields' => '/src/Fields.php',
'tad_cmb2_NestableList' => '/src/NestableList.php',
'tad_cmb2_Scripts' => '/src/Scripts.php',
'tad_cmb2_Select2' => '/src/Select2.php',
);
    if(isset($map[$class]) && file_exists($file = dirname(__FILE__) . $map[$class])){
        include $file;
    }
}