<?php

namespace Helvetiapps\WireTables\DataTables;

use Exception;
use Illuminate\Support\Facades\Schema;

class DataTable{

    public array $columns;
    public array $rows;

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
        $columns = Schema::getColumnListing((new $model())->getTable());
        $models = $model::all();
        
        foreach($columns as $column){
            if(!in_array($column, $ignoredColumns)){
                array_push($table->columns, $column);
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