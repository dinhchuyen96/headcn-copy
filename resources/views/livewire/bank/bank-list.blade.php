<div>
    <div class="page-content fade-in-up">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Quản lý danh sách tài khoản ngân hàng</div>
            </div>
            <div class="ibox-body">
                <form>
                    <div class="form-group row">
                        <label for="accountCode" class="col-1 col-form-label ">Mã tài khoản</label>
                        <div class="col-3">
                            <input id="accountCode" placeholder="Mã tài khoản" type="text" class="form-control"
                                wire:model="accountCode">

                        </div>
                        <label for="accountNumber" class="col-1 col-form-label ">Số tài khoản</label>
                        <div class="col-3">
                            <input id="accountNumber" placeholder="Số tài khoản" type="text" class="form-control"
                                wire:model="accountNumber">

                        </div>
                        <label for="accountOwner" class="col-1 col-form-label ">Chủ tài khoản</label>
                        <div class="col-3">
                            <input id="accountOwner" placeholder="Chủ tài khoản" type="text" class="form-control"
                                wire:model="accountOwner">

                        </div>
                    </div>

                    <div class="form-group row">
                        
                        <label for="accountType" class="col-1 col-form-label ">Loại tài khoản</label>
                        <div class="col-3">
                            <select wire:model="accountType" name='accountType' id="accountType"
                                class="custom-select select2-box">
                                <option value=''>Chọn loại TK</option>
                                <option value="CASH">CASH</option>
                                <option value="BANK">BANK</option>
                            </select>

                        </div>
                        <label for="bankName" class="col-1 col-form-label ">Tên ngân hàng</label>
                        <div class="col-3">
                            <input id="bankName" placeholder="Tên ngân hàng" type="text" class="form-control"
                                wire:model="bankName">
                        </div>
                    </div>
                </form>
                <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                    <div class="row">
                        <div class="col-sm-12">
                            <div id="category-table_filter" class="dataTables_filter">
                                <a href="{{ route('bank.create.index') }}" class="btn btn-primary"><i
                                        class="fa fa-plus"></i>
                                    Thêm mới</a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 table-responsive">
                            <table class="table table-striped table-bordered dataTable no-footer"
                                id="category-table" cellspacing="0" width="100%" role="grid"
                                aria-describedby="category-table_info" style="width: 100%;">
                                <thead>
                                    <tr role="row">
                                        <th tabindex="0" aria-controls="category-table" style="width: 30px;">STT</th>
                                        <th wire:click="sorting('account_code')"
                                            class="@if ($this->key_name == 'account_code')
                                            {{ $sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc' }} @else sorting @endif"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;">Mã tài khoản</th>
                                        <th wire:click="sorting('account_number')"
                                            class="@if ($this->key_name == 'account_number')
                                            {{ $sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc' }} @else sorting @endif"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;">Số tài khoản</th>
                                        <th wire:click="sorting('account_owner')"
                                            class="@if ($this->key_name == 'account_owner')
                                            {{ $sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc' }} @else sorting @endif"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;">Chủ tài khoản</th>
                                        <th wire:click="sorting('type')"
                                            class="@if ($this->key_name == 'type')
                                            {{ $sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc' }} @else sorting @endif"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;">Loại tài khoản</th>
                                        <th wire:click="sorting('bank_name')"
                                            class="@if ($this->key_name == 'bank_name')
                                            {{ $sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc' }} @else sorting @endif"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;">Tên ngân hàng</th>
                                        <th wire:click="sorting('balance')"
                                            class="@if ($this->key_name == 'balance')
                                            {{ $sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc' }} @else sorting @endif"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;">Số tiền hiện tại</th>
                                        <th wire:click="sorting('orginal_money')"
                                            class="@if ($this->key_name == 'orginal_money')
                                            {{ $sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc' }} @else sorting @endif"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;">Số tiền dư đầu</th>
                                    </tr>
                                </thead>
                                <div wire:loading class="loader"></div>
                                <tbody>
                                    @forelse ($data as $item)
                                        <tr data-parent="" data-index="1" role="row" class="odd">
                                            <td>
                                                {{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}
                                            </td>
                                            <td>{{ $item->account_code }}</td>
                                            <td>{{ $item->account_number }}</td>
                                            <td>{{ $item->account_owner }}</td>
                                            <td>{{ $item->type }}</td>
                                            <td>{{ $item->bank_name }}</td>
                                            <td>{{ numberFormat($item->balance) }}</td>
                                            <td>{{ numberFormat($item->orginal_money) }}</td>
                                        </tr>
                                    @empty
                                        <tr class="text-center text-danger">
                                            <td colspan="12">Không có bản ghi</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if (count($data) > 0)
                        {{ $data->links() }}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@section('js')
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            $('#accountType').on('change', function(e) {
                var data = $('#accountType').select2("val");
                @this.set('accountType', data);
            });
        });
    </script>
@endsection
