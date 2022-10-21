# Freedom micro-framework

## Easy to configure your custom project settings with Config module

- path ```/config/<config_name>.php```
- get ```Config::getInstance()->get('<config_name>')```
- syntax in <config_name>.php ```
  return [
  "param 1" => "value1",
  "param 2" => "value2",
  ]```

## Using .env file with Env Module
- path ```/.env```
- get ```Env::getInstance()->get('PARAM_NAME')```
- syntax in .env ```
  PARAM_NAME=PARAM_VALUE
  PARAM_2NAME=PARAM2_VALUE```

## DB Query Builder with Model concept
- need to extend your table from ```Winter666\Freedom\Modules\DB\Model```
- set table name into your model ```protected static string $table = '<table_name>';```
