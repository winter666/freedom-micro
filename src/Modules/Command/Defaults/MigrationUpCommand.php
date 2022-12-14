<?php


namespace Freedom\Modules\Command\Defaults;


use Freedom\Modules\Command\CommandDispatcher;
use Freedom\Modules\DB\Migration\Master;
use Freedom\Modules\DB\Migration\Register;
use Freedom\Modules\DB\Migration\Schema;
use Freedom\Modules\Helpers\Arrays\Arr;
use Freedom\Modules\Storage\Session;

class MigrationUpCommand extends CommandDispatcher
{
    public function handle()
    {
        try {

            Schema::makeIfNotExists('migrations', function (Master $table) {
                $table->id();
                $table->string('name');
                $table->timestamps();
            });

            $connection = $this->app->get('connection_resolver')->resolve('default');
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
            echo '[ERROR]: ' . $e->getMessage() . "\n";
        }
    }
}
