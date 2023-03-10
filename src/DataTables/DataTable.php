<?php

namespace Helvetiapps\WireTables\DataTables;

use Exception;
use Helvetiapps\WireTables\Enums\Casts;

class DataTable{

    public array $ids = [];
    public array $columns = [];
    public array $rows = [];

    public function __construct(string ...$columns)
    {
        $this->columns = $columns;
    }

    public function addRow(array $row){
        if(!$this->validRow($row)){
            throw new Exception('Invalid number of columns in row!');
        }

        array_push($this->rows, $row);
    }

    public function removeRow(int $idx){
        $count = 0;
        $newRows = [];
        foreach($this->rows as $row){
            if($count != $idx){
                array_push($newRows, $row);
            }
            $count++;
        }
        $this->rows = $newRows;
    }

    public function setColumn(int $idx, string $label, Casts $cast = null){
        $this->columns[$idx] = [
            'name' => $this->columns[$idx]["name"],
            'label' => $label,
            'cast' => is_null($cast) ? $this->columns[$idx]["cast"] : $cast
        ];
    }

    public function validRow(array $row): bool{
        return count($row) == count($this->columns);
    }

    public function getColumns(): array{
        return $this->columns;
    }

    public function getRows(): array{
        return $this->rows;
    }

    public function getIds(): array{
        return $this->ids;
    }
    
    public function toArray(): array{
        return [
            'columns' => $this->columns,
            'rows' => $this->rows
        ];
    }

    //STATICS
    public static function fromModel($model, array $ignoredColumns): DataTable{
        if(!(new $model() instanceof \Illuminate\Database\Eloquent\Model)){
            throw new Exception('Model "'.$model.'" is not a valid Illuminate\\Database\\Eloquent\\Model!');
        }
        $table = new DataTable();
        $columns = [];
        $m = new $model();
        foreach($m->getFillable() as $fillable){
            if(!in_array($fillable, $m->getHidden())){
                array_push($columns, ['name' => $fillable, 'label' => $fillable, 'cast' => Casts::None]);
            }
        }
        $models = $model::all();
        
        foreach($columns as $column){
            if(!in_array($column, $ignoredColumns)){
                array_push($table->columns, ['name' => $column['name'], 'label' => \Illuminate\Support\Str::camel($column['label']), 'cast' => $column['cast']]);
            }
        }

        foreach($models as $model){
            $row = [];
            foreach($table->columns as $column){
                $row[count($row)] = $model->{$column['name']};
            }
            $table->addRow($row);
            array_push($table->ids, $model->id);
        }
        return $table;
    }

    public static function fromArray(array $array): DataTable{
        $table = new DataTable();

        $rowCount = 0;
        foreach($array as $row){
            if($rowCount == 0){
                $table->columns = $row;
                continue;        
            }
            $table->addRow($row);
        }

        return $table;
    }
}