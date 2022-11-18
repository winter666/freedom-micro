<?php


namespace Freedom\Modules\Command\Defaults;


use Freedom\Modules\Command\CommandDispatcher;
use Freedom\Modules\DB\Connection;
use Freedom\Modules\DB\Migration\Master;
use Freedom\Modules\DB\Migration\Register;
use Freedom\Modules\DB\Migration\Schema;
use Freedom\Modules\Helpers\Arrays\Arr;
use Freedom\Modules\Storage\Session;

class MigrationCommand extends CommandDispatcher
{
    public function handle()
    {
        Schema::makeIfNotExists('migrations', function(Master $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        try {
            $connection = Connection::getInstance();
            $statements = $connection
                ->getConnection()
                ->prepare("SELECT * FROM migrations");
            $statements->execute();
            $completed = Arr::pluck($statements->fetchAll(), 'name');

            $migrations_path = Session::i()->get('project_path') . '/migrations/';
            $file_list = array_values(array_diff(scandir($migrations_path), array('.', '..')));
            $migrations = preg_replace('/.php/', '', $file_list);
            if (count(array_diff($migrations, $completed)) === 0) {
                echo 'There are nothing to migrate.';
                return;
            }

            foreach ($migrations as $migration) {
                if (!in_array($migration, $completed)) {
                    require_once $migrations_path . $migration . '.php';
                    $className = lcfirst($migration) . 'Migration';
                    /**
                     * @var Register $object
                     */
                    $object = new $className;
                    $object->up();
                }
            }

        } catch (\Exception $e) {
            echo 'ERROR: ' . $e->getMessage() . "\n";
        }
    }
}
