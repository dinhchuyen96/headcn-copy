<?php

namespace App\Http\Livewire\Bank;

use App\Http\Livewire\Base\BaseLive;
use App\Models\AccountMoney;

class BankList extends BaseLive
{
    public $accountType;
    public $accountCode;
    public $accountNumber;
    public $accountOwner;
    public $bankName;
    public $balance;

    public function mount()
    {
        $this->key_name = 'created_at';
        $this->sortingName = 'asc';
    }

    public function render()
    {
        $query = AccountMoney::query();
        if ($this->accountType) {
            $query->where('type',  $this->accountType);
        }
        if ($this->accountCode) {
            $query->where('account_code', 'like', '%' . $this->accountCode . '%');
        }
        if ($this->accountNumber) {
            $query->where('account_number', 'like', '%' . $this->accountNumber . '%');
        }
        if ($this->accountOwner) {
            $query->where('account_owner', 'like', '%' . $this->accountOwner . '%');
        }
        if ($this->bankName) {
            $query->where('bank_name', 'like', '%' . $this->bankName . '%');
        }

        $data = $query->orderBy($this->key_name, $this->sortingName)->paginate($this->perPage);
        $this->updateUI();
        return view('livewire.bank.bank-list', ['data' => $data]);
    }
    public function updateUI()
    {
        $this->dispatchBrowserEvent('setSelect2');
    }
}
