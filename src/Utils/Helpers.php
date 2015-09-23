<?php

namespace Markette\GopayInline\Utils;

final class Helpers
{

    /**
     * @param mixed $obj
     * @param array $mapping
     * @param array $data
     * @return object
     */
    public static function map($obj, array $mapping, array $data)
    {
        foreach ($mapping as $from => $to) {
            if (isset($data[$from])) {
                $obj->{$to} = $data[$from];
            }
        }
        return $obj;
    }
}