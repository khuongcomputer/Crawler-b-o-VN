<?php
function json_to_object($data){
        return json_decode($data);
    }

function json_to_array($data){
    return json_decode($data,true);
}
/**
 * Encode Json
 */
function json_from_object($data){
    $tmp_array=object_to_array($data);
    return json_from_array($tmp_array);
}
function json_from_array($data){
    return json_encode($data, JSON_NUMERIC_CHECK);
}
/**
 * Error return
 */
function json_error(){
    return json_last_error_msg();
}
function object_to_array($object) {
    if (is_object($object)) {
        // Gets the properties of the given object
        // with get_object_vars function
        $d = get_object_vars( $object );
    }

    if (is_array($object)) {
        /*
        * Return array converted to object
        * Using __FUNCTION__ (Magic constant)
        * for recursive call
        */
        return array_map(array(__CLASS__, 'array_to_object'), $object);
    }
    else {
        // Return array
        return $object;
    }
}

function array_to_object($array) {
    if  (is_array($array) ) {
        /*
        * Return array converted to object
        * Using __FUNCTION__ (Magic constant)
        * for recursive call
        */
        return (object) array_map(array(__CLASS__, 'array_to_object'), $array);
    }
    else {
        // Return object
        return $array;
    }
}