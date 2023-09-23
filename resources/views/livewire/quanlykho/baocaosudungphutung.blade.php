<div>
    <div class="page-heading">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}"><i class="fa fa-home font-20"></i></a>
            </li>
            <li class="breadcrumb-item">Báo cáo tình hình sử dụng phụ tùng</li>
        </ol>
    </div>
    <div class="page-content fade-in-up">
        <div class="ibox">
            <div class="ibox-body">
                <form>
                    <div class="form-group row">
                        <div class="col-md-3">
                            <label for="partno" class="form-label">Mã phụ tùng</label>
                            <select id="partno" wire:model.lazy ='partno'
                            class="custom-select select2-box">
                                <option  value=''> Chọn phụ tùng</option>
                                 @foreach ($partnolist as $key => $item)
                                    <option {{ $key==$partno ?'selected' : '' }}
                                    value="{{ $key }}">{{ $key .'-'. $item }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="usingtype" class="form-label">Loại</label>
                            <select id="usingtype" wire:model.lazy ='usingtype'
                            class="custom-select select2-box">
                                 <option {{ $usingtype==1 ?'selected' : '' }}  value='1'> Nhập kho</option>
                                 <option {{ $usingtype==2 ?'selected' : '' }} value='2'> Xuất kho</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="warehouse" class="form-label p-d-5">Kho</label>
                            <select id="warehouse" wire:model.lazy='warehouse'
                            class="custom-select select2-box">
                                 <option  value=0> Chọn kho</option>
                                 @foreach ($warehouselist as $key => $item)
                                    <option {{ $key==$warehouse ?'selected' : '' }}
                                    value="{{ $key }}">{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label for="inputZip" class="form-label">Thời gian</label>
                            @include('layouts.partials.input._inputDateRangerNew')
                        </div>
                    </div>
                    <div class="form-group row">

                    </div>
                </form>
                <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                    <div class="row mt-5">
                        <div class="col-sm-12 col-md-6">

                        </div>
                        <div class="col-sm-12 col-md-6 text-right">

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-striped table-bordered table-hover dataTable no-footer"
                                id="category-table" cellspacing="0" width="100%" role="grid"
                                aria-describedby="category-table_info"
                                style="display:block;width: 100%;overflow-x: scroll;white-space: nowrap;">
                                <thead>
                                    <tr role="row">
                                        <th tabindex="0" aria-controls="category-table" >STT
                                        </th>
                                        <th tabindex="0" style='width:15%' aria-controls="category-table" >Ngày chứng từ
                                        </th>
                                        @if($usingtype==2)
                                        <th class="" style='width:20%' tabindex="0" aria-controls="category-table">Bên nhận</th>
                                        @endif
                                        <th class="" style='width:35%' tabindex="0" aria-controls="category-table">Lý do</th>
                                        <th class="" style='width:5%' tabindex="0" aria-controls="category-table">Số lượng</th>
                                        <th class="" style='width:10%' tabindex="0" aria-controls="category-table">Đơn giá</th>
                                        <th class="" style='width:10%'  tabindex="0" aria-controls="category-table">Thành tiền</th>
                                    </tr>
                                </thead>
                                <div wire:loading class="loader"></div>
                                <tbody>
                                    @if(isset($data))
                                    @forelse($data as $item)
                                        @if ($loop->first)
                                            <tr>
                                                <td colspan="3">TOTAL</td>
                                                @if($usingtype==2)
                                                <td></td>
                                                @endif
                                                <td>{{ numberFormat($totalqty) }}</td>
                                                <td></td>
                                                <td>{{ numberFormat($totalamount)}}</td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <td>{{ $loop->index +1 }}</td>
                                            <td>{{ $item->date }}</td>
                                            @if($usingtype==2)
                                            <td>{{ $item->user }}</td>
                                            @endif
                                            <td>{{ $item->reason }}</td>
                                            <td>{{ $item->qty }}</td>
                                            <td>{{numberFormat($item->price) }}</td>
                                            <td>{{numberFormat ($item->amount) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="11" class="text-center text-danger">Không có bản ghi nào.</td>
                                        </tr>
                                    @endforelse
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" id="ModalExport" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Xác nhận</h5>
                </div>
                <div class="modal-body">
                    <p>Bạn có muốn xuất file không?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">Đóng</button>
                    <button type="button" wire:click.prevent="export()" class="btn btn-primary close-modal"
                        data-dismiss="modal">Đồng ý</button>
                </div>
            </div>
        </div>
    </div>



</div>

@section('js')
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            setDatePickerUI();
            $('#warehouse').on('change', function(e) {
                var data = $('#warehouse').select2("val");
                @this.set('warehouse', data);
            });
            $('#partno').on('change', function(e) {
                var data = $('#partno').select2("val");
                @this.set('partno', data);
            });
            $('#usingtype').on('change', function(e) {
                var data = $('#usingtype').select2("val");
                @this.set('usingtype', data);
            });

            //start setui date
            function setDatePickerUI() {
                $("#fromDate").kendoDatePicker({
                    max: new Date(),
                    value: new Date(),
                    format: 'dd/MM/yyyy',
                    change: function() {
                        if (this.value() != null) {
                            window.livewire.emit('setfromDate', {
                                ['fromDate']: this.value() ? this.value().toLocaleDateString('en-US') :
                                    null
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
                                ['toDate']: this.value() ? this.value().toLocaleDateString('en-US') :
                                    null
                            });
                        }
                    }
                });
            };
            //end setui date

        });
    </script>

@endsection
