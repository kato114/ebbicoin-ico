<?php

if (!function_exists('validation_errors_array')) {

    function validation_errors_array($prefix = '<p class="text-danger">', $suffix = '</p>') {

        if (FALSE === ($OBJ = & _get_validation_object())) {
            return '';
        }

        return $OBJ->error_array($prefix, $suffix);
    }
}