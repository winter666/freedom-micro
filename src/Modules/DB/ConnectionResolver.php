<?php


namespace Freedom\Modules\DB;



class ConnectionResolver
{
    protected array $targets = [];

    public function resolve($key)
    {
        return $this->targets[$key];
    }

    public function push($key, Connection $target)
    {
        $this->targets[$key] = $target;
    }

    public function has($key): bool
    {
        return isset($this->targets[$key]);
    }
}
