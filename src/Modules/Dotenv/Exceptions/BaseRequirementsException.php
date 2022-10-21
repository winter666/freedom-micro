<?php


namespace Winter666\Freedom\Modules\Dotenv\Exceptions;


use Throwable;

class BaseRequirementsException extends DotenvException
{
    protected const STATUSES = [
        self::STATUS_CODE_ONE => "Need to set public_path config in /config/server.php"
    ];
}
