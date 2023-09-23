<?php

namespace App\Http\Livewire\Bank;

use App\Http\Livewire\Base\BaseLive;
use Livewire\Component;
use App\Models\AccountMoney;

class BankCreate extends Component
{
    public $accountType;
    public $accountCode;
    public $accountNumber;
    public $accountOwner;
    public $bankName;
    public $balance;


    public function mount()
    {
    }

    public function render()
    {
        $this->updateUI();
        return view('livewire.bank.bank-create');
    }
    public function updateUI()
    {
        $this->dispatchBrowserEvent('setSelect2');
    }
    public function store()
    {
        $this->validate([
            'accountType' => 'required',
            'accountCode' => 'required|unique:account_money,account_code',
            'accountNumber' => 'required|unique:account_money,account_number',
            'accountOwner' => 'required',
            'bankName' => 'required',
            'balance' => 'required|numeric|min:0',

        ], [
            'accountType.required' => 'Loại tài khoản là bắt buộc',
            'accountCode.required' => 'Mã tài khoản là bắt buộc',
            'accountNumber.required' => 'Số tài khoản là bắt buộc',
            'accountCode.unique' => 'Mã tài khoản đã tồn tại',
            'accountNumber.unique' => 'Số tài khoản đã tồn tại',
            'accountOwner.required' => 'Chủ sở hữu là bắt buộc',
            'bankName.required' => 'Tên ngân hàng là bắt buộc',
            'balance.required' => 'Số tiền ban đầu là bắt buộc',
            'balance.numeric' => 'Số tiền ban đầu phải là số',
            'balance.min' => 'Số tiền ban đầu phải lớn hơn 0'
        ]);
        $accountMoney = new AccountMoney();
        $accountMoney->type = $this->accountType;
        $accountMoney->level = 1;
        $accountMoney->account_code = $this->accountCode;
        $accountMoney->account_number = $this->accountNumber;
        $accountMoney->account_owner = $this->accountOwner;
        $accountMoney->bank_name = $this->bankName;
        $accountMoney->balance = $this->balance;
        $accountMoney->orginal_money = $this->balance;
        $accountMoney->save();
        $this->resetInput();
        $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'message' => 'Tạo mới thành công']);
    }
    public function resetInput()
    {
        $this->accountType = '';
        $this->accountCode = '';
        $this->accountNumber = '';
        $this->accountOwner = '';
        $this->bankName = '';
        $this->balance = '';
    }
}
