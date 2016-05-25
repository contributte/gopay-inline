<?php

namespace Markette\GopayInline\Exception;

use stdClass;

class HttpException extends GopayException
{

    /**
     * @param stdClass $error
     * @throw self
     */
    public static function format(stdClass $error)
    {
        $field = isset($error->field) ? "[{$error->field}]" : '';
        $message = $error->message ? $error->message : $error->description;

        return sprintf('#%s (%s)%s %s', $error->error_code, $error->scope, $field, $message);
    }

}
