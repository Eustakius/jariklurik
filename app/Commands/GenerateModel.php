<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Database;

class GenerateModel extends BaseCommand
{
    //php spark make:model "table" "model"
    protected $group       = 'Generators';
    protected $name        = 'make:model';
    protected $description = 'Generate model dari tabel database';
    protected $usage       = 'make:model [table_name]';

    public function run(array $params)
    {
        $tableName = $params[0] ?? null;
        if (! $tableName) {
            CLI::error('Nama tabel wajib diisi!');
            return;
        }

        $db = Database::connect();
        $fields = $db->getFieldData($tableName);
        $primaryKey = 'id';

        foreach ($fields as $field) {
            if ($field->primary_key == 1) {
                $primaryKey = $field->name;
                break;
            }
        }
        $className = $params[1] ?? $this->generateClassName($tableName);
        $modelTemplate = <<<PHP
<?php

namespace App\Models;

use CodeIgniter\Model;

class {$className}Model extends Model
{
    protected \$table      = '{$tableName}';
    protected \$primaryKey = '{$primaryKey}';

    protected \$allowedFields = [
        {$this->formatAllowedFields($fields)}
    ];
}
PHP;

        $path = APPPATH . "Models/{$className}Model.php";
        if (file_exists($path)) {
            CLI::error("Model {$className}Model sudah ada!");
            return;
        }

        file_put_contents($path, $modelTemplate);
        CLI::write("Model {$className}Model berhasil dibuat!", 'green');
    }

    private function formatAllowedFields($fields)
    {
        $arr = [];
        foreach ($fields as $field) {
            $arr[] = "        '{$field->name}'";
        }
        return implode(",\n", $arr);
    }

    private function generateClassName(string $tableName): string
    {
        // convert snake_case ke PascalCase
        $parts = explode('_', $tableName);
        $parts = array_map('ucfirst', $parts);
        return implode('', $parts);
    }
}
