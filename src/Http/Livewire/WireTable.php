<?php

namespace Helvetiapps\WireTables\Http\Livewire;

use Carbon\Carbon;
use Exception;
use Helvetiapps\WireTables\DataTables\DataTable;
use Livewire\Component;
use Illuminate\Support\Str;


class WireTable extends Component
{
    public $content; //Can either be array or model

    //Table settings
    public $responsiveTable = true; //If true, the table will be set to responsive
    public $tableClasses = 'table-hover'; //The classes for the table
    public $firstBold = false; //If true, the first column will be bold

    //Optional functions
    public $searchBar = true; //Is the searchbar on the top visible?
    public $searchables = []; //The columns that are searchable, if null all are searchable
    public $sortables = []; //The columns that are sortable, if null all are searchable
    public $ignoredColumns = []; //THe columns that should be hidden in the table

    //Routes
    public $createRoute = null; //The route to create a new item, will be ignored if not set
    public $editRoute = null; //The route to edit an existing item, will be ignored if not set
    public $deleteRoute = null; //The route to remove an existing item, will be ignored if not set

    //Deletion
    public $confirmDelete = true; //If set to true, a confirmation popup will be shown when user clicks on delete
    public $showDeleteConfirmation = false; //If set to true, delete confirmation modal will be shown

    public function render()
    {   
        if(is_array($this->content)){
            $dataTable = DataTable::fromArray($this->content);
        }elseif(is_subclass_of($this->content, Illuminate\Database\Eloquent\Model::class)){
            $dataTable = DataTable::fromModel($this->content, $this->ignoredColumns);
        }else{
            throw new Exception('Invalid content format. Needs to be either Model or Array! is => '.$this->content);
        }

        return view('wiretables::livewire.wire-table', ['rows' => $dataTable["rows"], 'columns' => $dataTable["columns"]]);
    }

    public function pressCreate(){
        return redirect()->route($this->createRoute);
    }

    public function pressEdit($id){
        $placeholder = $this->getPlaceholder();
        return redirect()->route($this->editRoute, [$placeholder => $id]);
    }

    public function pressDelete($id){
        if($this->confirmDelete){
            $this->showDeleteConfirmation = true;
            return;
        }
        $this->deleteObject($id);
    }

    public function deleteObject($id){
        $this->showDeleteConfirmation = false;
        $placeholder = $this->getPlaceholder();
        return redirect()->route($this->deleteRoute, [$placeholder => $id]);
    }

    private function getPlaceholder():string{
        $placeholder = explode('{', $this->editRoute);
            if(count($placeholder) < 2){
                throw new Exception('Invalid placeholder in route '.$this->editRoute);
            }
        return explode('}', $placeholder[1])[0];
    }
}
