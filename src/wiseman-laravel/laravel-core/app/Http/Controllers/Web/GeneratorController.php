<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use ReflectionClass;

class GeneratorController extends Controller
{
    public function index(): Factory|\Illuminate\Foundation\Application|View|Application
    {
        $relations = [
            "hasOne",
            "hasMany",
        ];

        $filters = [
            "like",
            "where",
            "whereNot",
            "whereIn",
            "whereNotIn",
            "whereNull",
            "whereNotNull",
        ];

        $attributes = [
            "nullable" => [
                "option" => false,
                "type" => "",
                "placeholder" => ""
            ],
            "default" => [
                "option" => true,
                "type" => "text",
                "placeholder" => "contoh : laki-laki"
            ],
        ];

        $dataTypes = [
            "bigInteger" => [
                "option" => false,
                "type" => "",
                "placeholder" => ""
            ],
            "binary" => [
                "option" => false,
                "type" => "",
                "placeholder" => ""
            ],
            "boolean" => [
                "option" => false,
                "type" => "",
                "placeholder" => ""
            ],
            "char" => [
                "option" => true,
                "type" => "number",
                "placeholder" => "ukuran : 5"
            ],
            "date" => [
                "option" => false,
                "type" => "",
                "placeholder" => ""
            ],
            "dateTime" => [
                "option" => false,
                "type" => "",
                "placeholder" => ""
            ],
            "dateTimeTz" => [
                "option" => false,
                "type" => "",
                "placeholder" => ""
            ],
            "decimal" => [
                "option" => true,
                "type" => "number",
                "placeholder" => "ukuran desimal : 8,2"
            ],
            "double" => [
                "option" => true,
                "type" => "number",
                "placeholder" => "ukuran desimal : 8,2"
            ],
            "enum" => [
                "option" => true,
                "type" => "text",
                "placeholder" => "nilai : easy,hard"
            ],
            "float" => [
                "option" => true,
                "type" => "number",
                "placeholder" => "ukuran desimal : 8,2"
            ],
            "integer" => [
                "option" => false,
                "type" => "",
                "placeholder" => ""
            ],
            "longText" => [
                "option" => false,
                "type" => "",
                "placeholder" => ""
            ],
            "mediumInteger" => [
                "option" => false,
                "type" => "",
                "placeholder" => ""
            ],
            "mediumText" => [
                "option" => false,
                "type" => "",
                "placeholder" => ""
            ],
            "nullableTimestamps" => [
                "option" => false,
                "type" => "",
                "placeholder" => ""
            ],
            "set" => [
                "option" => true,
                "type" => "text",
                "placeholder" => "nilai : easy,hard"
            ],
            "smallInteger" => [
                "option" => false,
                "type" => "",
                "placeholder" => ""
            ],
            "string" => [
                "option" => true,
                "type" => "number",
                "placeholder" => "ukuran : 255"
            ],
            "text" => [
                "option" => false,
                "type" => "",
                "placeholder" => ""
            ],
            "time" => [
                "option" => false,
                "type" => "",
                "placeholder" => ""
            ],
            "timeTz" => [
                "option" => false,
                "type" => "",
                "placeholder" => ""
            ],
            "timestamp" => [
                "option" => false,
                "type" => "",
                "placeholder" => ""
            ],
            "timestampTz" => [
                "option" => false,
                "type" => "",
                "placeholder" => ""
            ],
            "tinyIncrements" => [
                "option" => false,
                "type" => "",
                "placeholder" => ""
            ],
            "tinyInteger" => [
                "option" => false,
                "type" => "",
                "placeholder" => ""
            ],
            "unsignedBigInteger" => [
                "option" => false,
                "type" => "",
                "placeholder" => ""
            ],
            "unsignedDecimal" => [
                "option" => true,
                "type" => "number",
                "placeholder" => "8.2"
            ],
            "uuid" => [
                "option" => false,
                "type" => "",
                "placeholder" => ""
            ],
            "year" => [
                "option" => false,
                "type" => "",
                "placeholder" => ""
            ],
        ];

        $requestValidations = [
            "accepted" => [
                "option" => false,
                "type" => "",
                "placeholder" => ""
            ],
            "accepted_if" => [
                "option" => false,
                "type" => "",
                "placeholder" => ""
            ],
            "active_url" => [
                "option" => false,
                "type" => "",
                "placeholder" => ""
            ],
            "after" => [
                "option" => true,
                "type" => "date",
                "placeholder" => "contoh : 2024-01-01"
            ],
            "after_or_equal" => [
                "option" => true,
                "type" => "date",
                "placeholder" => "contoh : 2024-06-27"
            ],
            "alpha" => [
                "option" => false,
                "type" => "",
                "placeholder" => ""
            ],
            "alpha_dash" => [
                "option" => false,
                "type" => "",
                "placeholder" => ""
            ],
            "alpha_numeric" => [
                "option" => false,
                "type" => "",
                "placeholder" => ""
            ],
            "array" => [
                "option" => false,
                "type" => "",
                "placeholder" => ""
            ],
            "ascii" => [
                "option" => false,
                "type" => "",
                "placeholder" => ""
            ],
            "bail" => [
                "option" => false,
                "type" => "",
                "placeholder" => ""
            ],
            "before" => [
                "option" => true,
                "type" => "date",
                "placeholder" => "contoh : 2004-01-01"
            ],
            "before_or_equal" => [
                "option" => true,
                "type" => "date",
                "placeholder" => "contoh : 2024-12-31"
            ],
            "between" => [
                "option" => true,
                "type" => "number",
                "placeholder" => "contoh : 18,60"
            ],
            "boolean" => [
                "option" => false,
                "type" => "",
                "placeholder" => ""
            ],
            "confirmed" => [
                "option" => false,
                "type" => "",
                "placeholder" => ""
            ],
            "contains" => [
                "option" => true,
                "type" => "text",
                "placeholder" => "contoh : http"
            ],
            "current_password" => [
                "option" => false,
                "type" => "",
                "placeholder" => ""
            ],
            "date" => [
                "option" => false,
                "type" => "",
                "placeholder" => ""
            ],
            "date_equals" => [
                "option" => true,
                "type" => "date",
                "placeholder" => "contoh : 2024-12-25"
            ],
            "date_format" => [
                "option" => true,
                "type" => "text",
                "placeholder" => "contoh : Y-m-d"
            ],
            "decimal" => [
                "option" => false,
                "type" => "",
                "placeholder" => ""
            ],
            "declined" => [
                "option" => false,
                "type" => "",
                "placeholder" => ""
            ],
            "declined_if" => [
                "option" => true,
                "type" => "text",
                "placeholder" => "contoh : amount,100.00"
            ],
            "different" => [
                "option" => true,
                "type" => "text",
                "placeholder" => "contoh : second_email"
            ],
            "digits" => [
                "option" => true,
                "type" => "text",
                "placeholder" => "contoh : 4"
            ],
            "digits_between" => [
                "option" => true,
                "type" => "number",
                "placeholder" => "contoh : 10,12"
            ],
            "dimensions" => [
                "option" => true,
                "type" => "text",
                "placeholder" => "min_width=100,min_height=200,max_width=500,max_height=600"
            ],
            "distinct" => [
                "option" => false,
                "type" => "",
                "placeholder" => ""
            ],
            "not_starts_with" => [
                "option" => true,
                "type" => "text",
                "placeholder" => "contoh : http"
            ],
            "not_end_with" => [
                "option" => true,
                "type" => "text",
                "placeholder" => "contoh : .pdf"
            ],
            "email" => [
                "option" => false,
                "type" => "",
                "placeholder" => ""
            ],
            "ends_with" => [
                "option" => true,
                "type" => "text",
                "placeholder" => "contoh : .pdf"
            ],
            "enum" => [
                "option" => true,
                "type" => "text",
                "placeholder" => "contoh : admin,editor,author"
            ],
            "exclude" => [
                "option" => true,
                "type" => "text",
                "placeholder" => "contoh : admin,user,test"
            ],
            "exclude_if" => [
                "option" => true,
                "type" => "text",
                "placeholder" => "contoh : role,admin,john.doe@example.com,jane.doe@example.com"
            ],
            "exclude_unless" => [
                "option" => true,
                "type" => "text",
                "placeholder" => "contoh : role,admin,USA,Canada"
            ],
            "exclude_with" => [
                "option" => true,
                "type" => "text",
                "placeholder" => "contoh : role,admin,user,test"
            ],
            "exclude_without" => [
                "option" => true,
                "type" => "text",
                "placeholder" => "contoh : role,admin,user"
            ],
            "exists_database" => [
                "option" => true,
                "type" => "text",
                "placeholder" => "contoh : users,id"
            ],
            "extensions" => [
                "option" => true,
                "type" => "text",
                "placeholder" => "contoh : png,jpg,gif"
            ],
            "file" => [
                "option" => false,
                "type" => "",
                "placeholder" => ""
            ],
            "filled" => [
                "option" => false,
                "type" => "",
                "placeholder" => ""
            ],
            "gt" => [
                "option" => true,
                "type" => "number",
                "placeholder" => "contoh : 0"
            ],
            "gte" => [
                "option" => true,
                "type" => "number",
                "placeholder" => "contoh : 1"
            ],
            "hex_color" => [
                "option" => false,
                "type" => "",
                "placeholder" => ""
            ],
            "image_file" => [
                "option" => false,
                "type" => "",
                "placeholder" => ""
            ],
            "in" => [
                "option" => true,
                "type" => "text",
                "placeholder" => "contoh : admin,editor,author"
            ],
            "in_array" => [
                "option" => true,
                "type" => "text",
                "placeholder" => "contoh : news,sports,tech"
            ],
            "integer" => [
                "option" => false,
                "type" => "",
                "placeholder" => ""
            ],
            "ip_address" => [
                "option" => false,
                "type" => "",
                "placeholder" => ""
            ],
            "json" => [
                "option" => false,
                "type" => "",
                "placeholder" => ""
            ],
            "lt" => [
                "option" => true,
                "type" => "number",
                "placeholder" => "contoh : 100"
            ],
            "lte" => [
                "option" => true,
                "type" => "number",
                "placeholder" => "contoh : 10"
            ],
            "list" => [
                "option" => true,
                "type" => "text",
                "placeholder" => "contoh : apple,banana,orange"
            ],
            "lowercase" => [
                "option" => false,
                "type" => "",
                "placeholder" => ""
            ],
            "mac_address" => [
                "option" => false,
                "type" => "",
                "placeholder" => ""
            ],
            "max" => [
                "option" => true,
                "type" => "number",
                "placeholder" => "contoh : 255"
            ],
            "max_digits" => [
                "option" => true,
                "type" => "number",
                "placeholder" => "contoh : 16"
            ],
            "mimes" => [
                "option" => true,
                "type" => "text",
                "placeholder" => "contoh : image/jpeg,image/png,application/pdf"
            ],
            "mimetypes" => [
                "option" => false,
                "type" => "",
                "placeholder" => ""
            ],
            "min" => [
                "option" => true,
                "type" => "number",
                "placeholder" => "contoh : 8"
            ],
            "min_digits" => [
                "option" => true,
                "type" => "number",
                "placeholder" => "contoh : 4"
            ],
            "missing" => [
                "option" => false,
                "type" => "",
                "placeholder" => ""
            ],
            "missing_if" => [
                "option" => true,
                "type" => "text",
                "placeholder" => "contoh : role,admin"
            ],
            "missing_unless" => [
                "option" => true,
                "type" => "text",
                "placeholder" => "contoh : subscription_type,premium"
            ],
            "missing_with" => [
                "option" => true,
                "type" => "text",
                "placeholder" => "contoh : username,email"
            ],
            "missing_with_all" => [
                "option" => true,
                "type" => "text",
                "placeholder" => "contoh : key1,key2,key3"
            ],
            "multiple_of" => [
                "option" => true,
                "type" => "number",
                "placeholder" => "contoh : 10"
            ],
            "not_in" => [
                "option" => true,
                "type" => "text",
                "placeholder" => "contoh : pending,cancelled"
            ],
            "not_regex" => [
                "option" => true,
                "type" => "text",
                "placeholder" => "contoh : /^[a-zA-Z]*$/"
            ],
            "null" => [
                "option" => false,
                "type" => "",
                "placeholder" => ""
            ],
            "numeric" => [
                "option" => false,
                "type" => "",
                "placeholder" => ""
            ],
            "password" => [
                "option" => false,
                "type" => "",
                "placeholder" => ""
            ],
            "present" => [
                "option" => false,
                "type" => "",
                "placeholder" => ""
            ],
            "present_if" => [
                "option" => true,
                "type" => "text",
                "placeholder" => "contoh : subscription_type,premium"
            ],
            "prohibited" => [
                "option" => false,
                "type" => "",
                "placeholder" => ""
            ],
            "prohibited_if" => [
                "option" => true,
                "type" => "text",
                "placeholder" => "contoh : role,admin"
            ],
            "prohibited_unless" => [
                "option" => true,
                "type" => "text",
                "placeholder" => "contoh : role,admin"
            ],
            "prohibits" => [
                "option" => true,
                "type" => "text",
                "placeholder" => "contoh : user_type"
            ],
            "regex" => [
                "option" => true,
                "type" => "text",
                "placeholder" => "contoh : /^[a-zA-Z]*$/"
            ],
            "required" => [
                "option" => false,
                "type" => "",
                "placeholder" => ""
            ],
            "required_if" => [
                "option" => true,
                "type" => "text",
                "placeholder" => "contoh : payment_method,credit_card'"
            ],
            "required_unless" => [
                "option" => true,
                "type" => "text",
                "placeholder" => "contoh : role,admin"
            ],
            "required_with" => [
                "option" => true,
                "type" => "text",
                "placeholder" => "contoh : country_code"
            ],
            "required_with_all" => [
                "option" => true,
                "type" => "text",
                "placeholder" => "contoh : street,city,state"
            ],
            "required_without" => [
                "option" => true,
                "type" => "text",
                "placeholder" => "contoh : email"
            ],
            "required_without_all" => [
                "option" => true,
                "type" => "text",
                "placeholder" => "contoh : street,city,state"
            ],
            "required_array_keys" => [
                "option" => true,
                "type" => "text",
                "placeholder" => "contoh : first_name,last_name"
            ],
            "required_if_accepted" => [
                "option" => true,
                "type" => "text",
                "placeholder" => "contoh : terms"
            ],
            "required_if_declined" => [
                "option" => true,
                "type" => "text",
                "placeholder" => "contoh : payment_status,failed"
            ],
            "same" => [
                "option" => true,
                "type" => "text",
                "placeholder" => "contoh : password"
            ],
            "size" => [
                "option" => true,
                "type" => "number",
                "placeholder" => "contoh : 1024"
            ],
            "sometimes" => [
                "option" => false,
                "type" => "",
                "placeholder" => ""
            ],
            "starts_with" => [
                "option" => true,
                "type" => "text",
                "placeholder" => "contoh : http"
            ],
            "string" => [
                "option" => false,
                "type" => "",
                "placeholder" => ""
            ],
            "timezone" => [
                "option" => false,
                "type" => "",
                "placeholder" => ""
            ],
            "unique" => [
                "option" => true,
                "type" => "text",
                "placeholder" => "contoh : users,email"
            ],
            "uppercase" => [
                "option" => false,
                "type" => "",
                "placeholder" => ""
            ],
            "ulid" => [
                "option" => false,
                "type" => "",
                "placeholder" => ""
            ],
            "url" => [
                "option" => false,
                "type" => "",
                "placeholder" => ""
            ],
            "uuid" => [
                "option" => false,
                "type" => "",
                "placeholder" => ""
            ],
        ];

        $modelDirectory = app_path('Models');
        $models = [];

        if (is_dir($modelDirectory)) {
            $files = File::allFiles($modelDirectory);

            foreach ($files as $file) {
                $namespace = 'App\Models';
                $class = $namespace . '\\' . Str::replaceLast('.php', '', $file->getFilename());
                if ($this->isModel($class)) {
                    $models[] = str_replace('App\Models\\', '', $class);
                }
            }
        }
        $data = [
            'tables' =>$this->getAllTables(),
            'filters' => $filters,
            'dataTypes' => $dataTypes,
            'requestValidations' => $requestValidations,
            'relations' => $relations,
            'models' => $models,
            'attributes' => $attributes,
        ];
        return view('crud-generator')->with($data);
    }

    protected function isModel($class): bool
    {
        if (class_exists($class)) {
            $reflection = new ReflectionClass($class);
            return $reflection->isSubclassOf('Illuminate\Database\Eloquent\Model') && !$reflection->isAbstract();
        }

        return false;
    }

    public function getAllTables(): array
    {
        $tables = DB::select('SHOW TABLES');
        $result = [];
        $ignoredColumns = ['id', 'created_at', 'updated_at', 'deleted_at', 'updated_security', 'created_by', 'updated_by', 'deleted_by'];

        foreach ($tables as $table) {
            $tableArray = (array) $table;
            $tableName = reset($tableArray);
            $columns = Schema::getColumnListing($tableName);
            $filteredColumns = array_filter($columns, function($column) use ($ignoredColumns) {
                return !in_array($column, $ignoredColumns);
            });
            $result[$tableName] = array_values($filteredColumns);
        }

        return $result;
    }

    public function generate(Request $request): void
    {
        $data = [
            "model_name" => str_replace(' ', '', str_replace('Model', '', ucwords($request->model_name))),
            "created_files" => $request->created_files,
            "table" => $request->table,
            "fields" => $request->fields
        ];

        $fields = [];
        $filters = [];
        $createRules = [];
        $updateRules = [];
        $relations = [];
        $attributes = [];
        $include = '';
        $files = $data['created_files'];

        if (count($files) > 0) {
            $files[] = 'model';
            if (!empty($data['table']) || $data['table'] !== '') {
                $files[] ='migration';
            }
        }

        foreach ($data['fields'] as $field) {
            $fields[] = $field['column_name'] . '=' . $field['data_type']['name'] . ($field['data_type']['option'] ? ':' . $field['data_type']['option'] : '');
            $filters[] = $field['column_name'] . '=' . $field['filter'];

            $createRuleParts = [];
            foreach ($field['create_request'] as $rule) {
                if (!empty($rule['name'])) {
                    $createRuleParts[] = $rule['name'] . (isset($rule['option']) && $rule['option'] !== '' ? ':' . $rule['option'] : '');
                }
            }
            $createRules[] = $field['column_name'] . '=' . implode('|', $createRuleParts);

            $updateRuleParts = [];
            foreach ($field['update_request'] as $rule) {
                if (!empty($rule['name'])) {
                    $updateRuleParts[] = $rule['name'] . (isset($rule['option']) && $rule['option'] !== '' ? ':' . $rule['option'] : '');
                }
            }
            $updateRules[] = $field['column_name'] . '=' . implode('|', $updateRuleParts);

            if (!empty($field['relation']['relation']) && !empty($field['relation']['name'])) {
                $relations[] = 'belongsTo,'. $field['relation']['relation'].',' . $field['relation']['name'] . ',' . $field['column_name'] . ',id';
            }
            if (!empty($field['relation']['include-crud']) && $field['relation']['include-crud'] == 'yes') {
                $include = $field['relation']['name'];
            }

            $attributeParts = [];
            foreach ($field['attribute'] as $attribute) {
                if (!empty($attribute['name'])) {
                    $attributeParts[] = $attribute['name'] . (isset($attribute['option']) && $attribute['option'] !== '' ? ':' . $attribute['option'] : '');
                }
            }
            if (!empty($attributeParts)) {
                $attributes[] = $field['column_name'] . '=' . implode(',', $attributeParts);
            }
        }

        $fieldsString = implode(';', $fields);
        $filtersString = implode(';', $filters);
        $createRulesString = implode(';', $createRules);
        $updateRulesString = implode(';', $updateRules);
        $relationsString = implode(';', $relations);
        $attributesString = implode(';', $attributes);
        $filesString = implode(',', $files);

        $command = 'make:crud ' . $data['model_name'] .
            ' --fields="' . $fieldsString . '"' .
            ' --filters="' . $filtersString . '"' .
            ' --create-rule="' . $createRulesString . '"' .
            ' --update-rule="' . $updateRulesString . '"' .
            ' --relations="' . $relationsString . '"' .
            ' --attributes="' . $attributesString . '"' .
            ' --files="' . $filesString . '"' .
            ' --include="' . $include . '"' .
            ' --table="' . $data['table'] . '"' ;
        Artisan::call($command);
        echo $command;
    }
}
