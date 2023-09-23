<div>
    <div class="page-content fade-in-up">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">DANH SÁCH PHIẾU CHI</div>
            </div>
           <div class="ibox-body">
                <form>
                    <div class="form-group row">
                        <label for="CustomerName" class="col-1 col-form-label ">Nội dung</label>
                        <div class="col-3">
                            <input name="CustomerName" type="text" class="form-control form-red"
                                placeholder='Nội dung'
                                wire:model.debounce.1000ms='content'>
                        </div>
                        <label for="CustomerAddress" class="col-1 col-form-label ">Loại phiếu chi</label>
                        <div class="col-3">
                            <select wire:model="incometype" id="incometype" class="custom-select select2-box col-6">
                                <option value="">--Tất cả--</option>
                                <option value="8">Nhập phụ tùng</option>
                                <option value="9">Nhập xe</option>
                                <option value="10">Chi nội bộ</option>
                                <option value="11">Chi phí khác</option>
                                <option value="100">Nộp tiền TK NH</option>
                                <option value="101">Rút tiền NH về quỹ</option>
                                <option value="102">Chuyển tiền nội bộ</option>
                            </select>
                        </div>

                        <label for="Time" class="col-1 col-form-label ">Ngày hạch toán<span class="text-danger">*</span></label>
                        @include('layouts.partials.input._inputDateRanger')

                    </div>
                    <div class="form-group row">
                        <div class="col-3"
                        style=" {{ isset($incometype) && $incometype == 10 ? 'display:block' : 'display:none' }}">
                            <select wire:model="servicetypeid" id="servicetypeid"
                                class="custom-select select2-box col-6">
                                <option value="">Hạng mục</option>
                                @if (isset($listservicetypeid) && count($listservicetypeid) > 0)
                                    @foreach ($listservicetypeid as $key => $value)
                                        <option {{ $value->id == $servicetypeid ? 'selected' : '' }}
                                            value='{{ $value->id }}'>
                                            {{ $value->title }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="form-group row justify-content-center">
                        @include('layouts.partials.button._reset')
                    </div>
                </form>

                <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer pt-4">
                    <div class="row">
                        <div class="col-sm-12">
                            <div id="category-table_filter" class="dataTables_filter">
                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#cknoibomodal">
                                    <i class="fa fa-file-excel-o"></i> Chuyển/nộp tiền nội bộ
                                </button>
                                <button type="button" class="btn btn-warning add-new" data-toggle="modal"
                                    data-target="#exportModal" {{ count($data) ? '' : 'disabled' }}>
                                    <i class="fa fa-file-excel-o"></i> Export file
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12 table-responsive">
                            <table class="table table-striped table-bordered dataTable no-footer"
                                id="category-table" cellspacing="0" width="100%" role="grid"
                                aria-describedby="category-table_info"
                                style="width: 100%;overflow-x: scroll;white-space: nowrap;">
                                <thead>
                                    <tr role="row">
                                        <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            wire:click='sorting("id")' aria-label="ID: activate to sort column">ID
                                        </th>
                                        <th class="{{ $key_name == 'code' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            wire:click='sorting("code")'>Ngày hạch toán</th>
                                        <th class="{{ $key_name == 'name' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            wire:click='sorting("name")'>Nội dung</th>
                                        <th class="{{ $key_name == 'phone' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            wire:click='sorting("phone")'>Loại</th>

                                        <th class="{{ $key_name == 'title' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            wire:click='sorting("title")'>Hạng mục </th>

                                        <th class="{{ $key_name == 'address' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            wire:click='sorting("address")'>Mã TK Nhận</th>
                                        <th class="{{ $key_name == 'address' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            wire:click='sorting("address")'>Số TK Nhận</th>
                                        <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            class="{{ $key_name == 'total_money1' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                            wire:click='sorting("total_money1")'>Mã TK Chi</th>
                                        <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            class="{{ $key_name == 'total_money1' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                            wire:click='sorting("total_money1")'>Số TK Chi</th>
                                        <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            class="{{ $key_name == 'total_money2' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                            wire:click='sorting("total_money2")'>Số tiền</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data as $key => $value)
                                        @if ($loop->first)
                                            <tr data-parent="" data-index="1" role="row" class="odd">
                                                <td colspan=8 class='font-weight-bold'>
                                                </td>
                                                <td colspan=1 class='font-weight-bold'>
                                                    TOTAL
                                                </td>
                                                <td colspan=1 class='font-weight-bold'>
                                                    {{ numberFormat($totalmoney) }}
                                                </td>
                                            </tr>
                                        @endif

                                        <tr data-parent="" data-index="1" role="row" class="odd">
                                            <td class="sorting_1">
                                                {{ $value->id }}
                                            </td>
                                            <td>{{ $value->payment_date }}</td>
                                            <td>{{ $value->note }}</td>
                                            <td>{{ isset($value->type) ? $this->getPaidType($value->type) : 'Chi tiền hàng' }}
                                            </td>
                                            <td>{{ isset($value->title) ? $value->title : '' }}</td>
                                            <td>{{ $value->to_account_code }}</td>
                                            <td>{{ $value->to_account_number }}</td>
                                            <td>{{ $value->account_code }}</td>
                                            <td>{{ $value->account_number }}</td>
                                            <td>{{ numberFormat($value->money) }}</td>
                                        </tr>
                                    @empty
                                        <tr class="text-center text-danger">
                                            <td colspan="10">Không có bản ghi</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    @if (count($data) > 0)
                        {{ $data->appends(Arr::except(Request::query(), 'page'))->links() }}
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- modal -->
    @include('livewire.common.modal._modalExport')
    <div wire:ignore.self class="modal fade" id="cknoibomodal" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title color-white" id="exampleModalLabel">CHUYỂN / NỘP TIỀN NỘI BỘ</h5>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="transfer_date" class="col-12 col-form-label">Ngày hạch toán <span
                                class="text-danger">*</span></label>
                    </div>
                    <div class="form-group row">
                        <div class='col-sm-12'>
                            <input type="date" class="form-control input-date-kendo-edit" id="transfer_date"
                                max='{{ date('Y-m-d') }}' wire:model.lazy="transfer_date">
                            @error('transfer_date')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class='col-sm-12'>
                            <select wire:model.lazy="from_account_money_id" id="from_account_money_id"
                                class="custom-select select2-box col-sm-12">
                                <option hidden value="">Chọn TK Chuyển/Nộp</option>
                                @foreach ($account_money_list as $item)
                                    <option {{ $from_account_money_id == $item->id ? 'selected' : '' }}
                                        value="{{ $item->id }}">
                                        {{ $item->account_code .
                                            (isset($item->account_number) ? '-' . $item->account_number : '') .
                                            '-' .
                                            $item->account_owner .
                                            (isset($item->bank_name) ? '-' . $item->bank_name : '') }}
                                    </option>
                                @endforeach
                            </select>
                            @error('from_account_money_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class='col-sm-12'>
                            <select wire:model.lazy="to_account_money_id" id="to_account_money_id"
                                class="custom-select select2-box col-sm-12">
                                <option hidden value="">Chọn TK Nhận</option>
                                @foreach ($this->account_money_list as $item)
                                    <option {{ $this->to_account_money_id == $item->id ? 'selected' : '' }}
                                        value="{{ $item->id }}">
                                        {{ $item->account_code .
                                            (isset($item->account_number) ? '-' . $item->account_number : '') .
                                            '-' .
                                            $item->account_owner .
                                            (isset($item->bank_name) ? '-' . $item->bank_name : '') }}
                                    </option>
                                @endforeach
                            </select>
                            @error('to_account_money_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class='col-sm-12'>
                            <input type='text' wire:model.lazy="amount" id="amount" class='form-control'
                                placeholder='Số tiền'>
                            @error('amount')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class='col-sm-12'>
                            <select wire:model="transfer_user_id" id="transfer_user_id"
                                class="custom-select select2-box col-sm-12">
                                <option hidden value="">Người chuyển</option>
                                @foreach ($transfer_user_list as $item)
                                    <option {{ $transfer_user_id == $item->id ? 'selected' : '' }}
                                        value="{{ $item->id }}">
                                        {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('transfer_user_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class='col-sm-12'>
                            <textarea class='col-sm-12' placeholder='Nội dung' wire:model.lazy="note"></textarea>
                            @error('note')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">Đóng</button>
                    <button type="button" wire:click.prevent="cknoibo()" class="btn btn-primary">Đồng ý</button>
                </div>
            </div>
        </div>
    </div>
    <!-- end modal  -->
</div>
@section('js')
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            $('#incometype').on('change', function(e) {
                var data = $('#incometype').select2("val");
                @this.set('incometype', data);
                //neu ko fai la noi bo => reset
                if (data != 10) {
                    @this.set('servicetypeid', 0);
                }
            });
            $('#servicetypeid').on('change', function(e) {
                var data = $('#servicetypeid').select2("val");
                @this.set('servicetypeid', data);
                //@this.emit('setSelectservicetypeid', data);
            });

            setDatePickerUI();

            //modal
            $('#from_account_money_id').on('change', function(e) {
                var data = $('#from_account_money_id').select2("val");
                @this.set('from_account_money_id', data);
            });
            $('#to_account_money_id').on('change', function(e) {
                var data = $('#to_account_money_id').select2("val");
                @this.set('to_account_money_id', data);
            });
            $('#transfer_user_id').on('change', function(e) {
                var data = $('#transfer_user_id').select2("val");
                @this.set('transfer_user_id', data);
            });
            //end modal


            function setDatePickerUI() {
                $("#fromDate").kendoDatePicker({
                    max: new Date(),
                    value: new Date(),
                    format: 'dd/MM/yyyy',
                    change: function() {
                        if (this.value() != null) {
                            window.livewire.emit('setfromDate', {
                                ['fromDate']: this.value() ? this.value().toLocaleDateString(
                                    'en-US') : null
                            });
                        }
                    }
                });
                $("#toDate").kendoDatePicker({
                    max: new Date(),
                    value: new Date(),
                    format: 'dd/MM/yyyy',
                    change: function() {
                        if (this.value() != null) {
                            window.livewire.emit('settoDate', {
                                ['toDate']: this.value() ? this.value().toLocaleDateString(
                                    'en-US') : null
                            });
                        }
                    }
                });

                $("#transfer_date").kendoDatePicker({
                    max: new Date(),
                    value: new Date(),
                    format: 'dd/MM/yyyy',
                    change: function() {
                        if (this.value() != null) {
                            window.livewire.emit('settransfer_date', {
                                ['transfer_date']: this.value() ? this.value()
                                    .toLocaleDateString('en-US') : null
                            });
                        }
                    }
                });
            };
        })
    </script>
@endsection
<style>
    .select2-container {
        width: 100% !important;
        padding: 0;
    }

</style>
