<?php

namespace Helvetiapps\WireTables\DataTables;

use Exception;
use Helvetiapps\WireTables\Enums\Casts;

class DataTable{

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

    public function setColumn(int $idx, string $label, Casts $cast = Casts::None){
        $this->columns[$idx] = [
            'label' => $label,
            'cast' => $cast
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

    public function toArray(): array{
        return [
            'headers' => $this->columns,
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
                array_push($columns, ['label' => $fillable, 'cast' => Casts::None]);
            }
        }
        $models = $model::all();
        
        foreach($columns as $column){
            if(!in_array($column, $ignoredColumns)){
                array_push($table->columns, ['label' => \Illuminate\Support\Str::camel($column['label']), 'cast' => $column['cast']]);
            }
        }

        foreach($models as $model){
            $row = [];
            foreach($table->columns as $column){
                $row[count($row)] = $model->{$column};
            }
            $table->addRow($row);
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