# Freedom micro-framework

## Easy to configure your custom project settings with Config module

- path ```/config/<config_name>.php```
- get ```Config::getInstance()->get('<config_name>')``` or ```config('<config_name>')```
- syntax in <config_name>.php ```
  return [
  "param 1" => "value1",
  "param 2" => "value2",
  ]```

## Using .env file with Env Module
- path ```/.env```
- get ```Env::getInstance()->get('PARAM_NAME')``` or ```env('PARAM_NAME')```
- syntax in .env ```
  PARAM_NAME=PARAM_VALUE
  PARAM_2NAME=PARAM2_VALUE```
  
### Setup (requirements)
- create ```/config/server.php```
- add `public_path` with path to your public directory

## DB Query Builder with Model concept
- need to extend your table from ```Freedom\Modules\DB\Model```
- set table name into your model ```protected static string $table = '<table_name>';```
