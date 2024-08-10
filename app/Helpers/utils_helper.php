<?php
function uuid()
{
    $uuid = service('uuid');
    $uuid4 = $uuid->uuid4();
    $string = $uuid4->toString();
    return $string;
}
