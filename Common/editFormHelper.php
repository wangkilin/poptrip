<?php
class editFormHelper
{
    static public function printElementAttributeInEditForm($editInfo, $fieldName)
    {
        if(isset($editInfo['columnAttributes'], $editInfo['columnAttributes'][$fieldName])) {
            $attributes = $editInfo['columnAttributes'][$fieldName];
            foreach($attributes as $key=>$value) {
                if(is_numeric($key)) {
                    echo ' ' . $value . '="' . I($value,'') . '" ';
                    continue;
                }
                switch($key) {
                    case 'url':
                    case 'ajaxUrl':
                        $param = isset($attributes['urlParam']) ? $attributes['urlParam'] : array();
                        $value = U($value, $param);
                        break;

                    default:
                }
                if(is_string($value) || is_numeric($value)) {
                    echo ' ' . $key . '="' . $value . '" ';
                }
            }
        }
    }
}
/* EOF */