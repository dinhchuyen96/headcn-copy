<?php

namespace App\Http\Livewire\Base;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Http\Livewire\Base\BaseTrimString;
use App\Http\Livewire\BasePermission;

abstract class BaseLive extends BaseTrimString
{
    use WithPagination;
    use WithFileUploads;
    //    use BasePermission;

    public $deleteId;
    public $reset = false;
    public $searchTerm;
    public $key_name = 'created_at', $sortingName = 'desc';
    public $key_name2 = 'created_at', $sortingName2 = 'desc';
    public $perPage = 25;
    protected  static function paginationView()
    {
        return 'livewire.common.pagination._pagination';
    }
    public function deleteId($id)
    {
        $this->deleteId = $id;
    }
    public function levelClicked()
    {
    }
    public function resetSearch()
    {
        $this->reset = true;
    }
    public function updatingSearchTerm()
    {
        $this->resetPage();
    }
    public function updatingSearchCategory()
    {
        $this->resetPage();
    }
    public function updatingStore()
    {
        dd($this->checkEditPermission);
    }
    public function updatingSetDate()
    {
        $this->resetPage();
    }
    public function updatingPerPage()
    {
        $this->resetPage();
    }
    public function sorting($key)
    {
        if ($this->key_name == $key) {
            $this->sortingName = $this->getSortName();
        } else {
            $this->sortingName = 'desc';
        }
        $this->key_name = $key;
    }
    function getSortName()
    {
        return $this->sortingName == "desc" ? "asc" : "desc";
    }

    public function sorting2($key)
    {
        if ($this->key_name2 == $key) {
            $this->sortingName2 = $this->getSortName2();
        } else {
            $this->sortingName2 = 'asc';
        }
        $this->key_name2 = $key;
        //         dd($this->key_name2, $this->sortingName2);
    }
    function getSortName2()
    {
        return $this->sortingName2 == "desc" ? "asc" : "desc";
    }
    public function setAddress($province_id, $district_id, $ward_id, $address)
    {
        $this->province_id = $province_id;
        $this->district_id = $district_id;
        $this->ward_id = $ward_id;
        $this->address = $address;
    }
}
