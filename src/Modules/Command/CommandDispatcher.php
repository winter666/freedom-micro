<?php


namespace Freedom\Modules\Command;


use Freedom\Modules\Application;

abstract class CommandDispatcher
{
    public function __construct(protected Application $application) {}
    abstract public function handle();
}
