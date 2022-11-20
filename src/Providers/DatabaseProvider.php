<?php


namespace Freedom\Providers;


use Freedom\Modules\DB\Connection;
use Freedom\Modules\DB\ConnectionResolver;
use Freedom\Modules\DB\Migration\Schema;
use Freedom\Modules\DB\Model;

class DatabaseProvider extends Provider
{
    public function register()
    {
        $env = $this->application->get('env');

        $connection = new Connection(
            (string) $env->get('DB'),
            (string) $env->get('DB_HOST'),
            (string) $env->get('DB_NAME'),
            (string) $env->get('DB_USERNAME'),
            (string) $env->get('DB_PASSWORD'),
        );

        /**
         * @var ConnectionResolver $connectionResolver
         */
        $connectionResolver = $this->application
            ->singleton('connection_resolver', new ConnectionResolver());
        $connectionResolver->push('default', $connection);
        Model::setConnectionResolver($connectionResolver);
        Schema::setConnectionResolver($connectionResolver);
    }
}
