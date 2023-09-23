<div>
    <div class="page-content fade-in-up">
        <div class="ibox">
            <div class="ibox-body">
                <form>
                    <div class="form-group row">
                        <label for="Time" class="col-1 col-form-label ">Thời gian</label>
                        @include('layouts.partials.input._inputDateRangerNow')
                        <label for="Time" class="col-2 col-form-label text-right">Tình trạng liên hệ</label>
                        <div class="col-3">
                            <select wire:model="account_money_id" id="account_money_id"
                            class="custom-select select2-box col-sm-12">
                                <option hidden value="">Chọn TK</option>
                                @foreach ($this->account_money_list as $item)
                                    <option {{ $this->account_money_id==$item->id ? 'selected' :'' }}
                                    value="{{ $item->id }}">
                                    {{ $item->account_code
                                        .(isset($item->account_number) ? '-'.$item->account_number : '')
                                        .'-'.$item->account_owner
                                        .(isset($item->bank_name) ? '-'.$item->bank_name : '')  }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row justify-content-center">
                        @include('layouts.partials.button._reset')
                    </div>

                </form>
                <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                    <div class="row">
                        <div class="col-sm-12">
                            <div id="category-table_filter" class="dataTables_filter">
                                <button @if (count($data) == 0) disabled @endif name="submit" data-target="#exportModal"
                                    data-toggle="modal" type="button" class="btn btn-warning add-new"><i
                                        class="fa fa-file-excel-o"></i> Export file</button>
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
                                            <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1">Ngày</th>

                                           <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1">Số tài khoản</th>
                                           <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1">Tên tài khoản</th>
                                           <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1">Ngân hàng</th>

                                           <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1">Nội dung</th>

                                           <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1">Dư đầu</th>
                                           <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1">PS có/thu</th>
                                           <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1">PS nợ/chi</th>
                                           <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1">Dư cuối</th>
                                    </tr>
                                </thead>
                                <div wire:loading class="loader"></div>
                                <tbody>
                                    @php ($current_account_code = '')
                                    @forelse ($data as $item)
                                        @if ($loop->index==0)
                                            @php ($current_account_code = $item->account_code)
                                            @include ('livewire.ketoan.baocao.begin-so-quy', compact('data_begin', 'current_account_code'))
                                        @endif
                                        @if ($loop->index > 0 && $current_account_code != $item->account_code && !$loop->last)
                                            @include ('livewire.ketoan.baocao.end-so-quy', compact('data', 'current_account_code'))
                                            @include ('livewire.ketoan.baocao.sub-total-so-quy', compact('data', 'current_account_code'))

                                            @php ($current_account_code = $item->account_code)
                                            @include ('livewire.ketoan.baocao.begin-so-quy', compact('data_begin', 'current_account_code'))

                                        @endif
                                        <tr data-parent="" data-index="1" role="row" class="odd">
                                            @if ($current_account_code != $item->account_code)
                                                @php ($current_account_code = $item->account_code)
                                            @endif

                                            <td>{{ $item->trans_date }}</td>

                                            <td>{{ $item->account_number }}</td>
                                            <td>{{ $item->account_owner }}</td>
                                            <td>{{ $item->bank_name }}</td>
                                            <td>{{ $item->note }}</td>
                                            <td></td>
                                            <td>{{ isset($item->in_money) ? $item->in_money : '' }}</td>
                                            <td>{{ isset($item->out_money) ?$item->out_money : '' }}</td>
                                            <td></td>
                                        </tr>

                                        @if ($loop->last)
                                            @include ('livewire.ketoan.baocao.end-so-quy', compact('data', 'current_account_code'))
                                            @include ('livewire.ketoan.baocao.sub-total-so-quy', compact('data', 'current_account_code'))
                                            @include ('livewire.ketoan.baocao.total-so-quy', compact('data', 'current_account_code'))
                                        @endif
                                    @empty
                                        <tr>
                                            <td colspan="12" class="text-center text-danger">Không có bản ghi nào.</td>
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
    <div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Download file</h5>
                </div>
                <div class="modal-body">
                    Bạn có chắc chắn muốn xuất file không?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-basic" data-dismiss="modal">Quay lại</button>
                    <button type="button" wire:click="export" class="btn btn-primary" data-dismiss="modal"
                        id='btn-upload-film'>Đồng ý</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#account_money_id').on('change', function(e) {
            var data = $('#account_money_id').select2("val");
            @this.set('account_money_id', data);
        });

    });
</script>
