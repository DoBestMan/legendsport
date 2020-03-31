<?php
//FORMS *******************************************************************
function error($input, $errors)
{
    return $errors->has($input) ? 'is-invalid' : '';
}

function american_to_decimal(int $odd): float
{
    return $odd < 0 ? 100 / -$odd : $odd / 100;
}
