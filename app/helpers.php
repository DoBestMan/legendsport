<?php
//FORMS *******************************************************************
function error($input, $errors)
{
    return $errors->has($input) ? 'is-invalid' : '';
}
