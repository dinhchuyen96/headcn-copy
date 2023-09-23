<div>
    <div wire:loading class="div-loading">
        <div class="loader"></div>
    </div>
    <div class="page-content fade-in-up">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Báo cáo kho phụ tùng</div>
            </div>
            <div class="ibox-body">
                <form>
                    <div class="form-group row">
                        <label for="Warehouses" class="col-1 col-form-label ">Kho</label>
                        <div class="col-3">
                            <select wire:model="Warehouses" id="Warehouses" class="custom-select select2-box">
                                <option hidden value="">Chọn Kho</option>
                                @foreach ($warehouseList as $key => $item)
                                    <option value="{{ $key }}">{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>

                        <label for="PositionInWarehouse" class="col-1 col-form-label ">Vị trí kho</label>
                        <div class="col-3">
                            <select wire:model="PositionInWarehouse" id="PositionInWarehouse"
                                class="custom-select select2-box">
                                <option hidden value="">Chọn Vị trí kho</option>
                                @foreach ($positionWarehouse as $key => $item)
                                    <option value="{{ $key }}">{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>
                        <label for="AccessoriesCode" class="col-1 col-form-label ">Mã phụ tùng</label>
                        <div class="col-3">
                            <input id="AccessoriesCode" wire:model="AccessoriesCode" placeholder="Mã phụ tùng"
                                type="text" class="form-control">
                        </div>
                    </div>

                    <div class="form-group row">


                        <label for="Time" class="col-1 col-form-label ">Thời gian</label>
                        @include('layouts.partials.input._inputDateRanger')
                    </div>

                    <div class="form-group row justify-content-center">
                        <div class="col-1">
                            @include('layouts.partials.button._reset')
                        </div>
                    </div>
                </form>
                <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                    <div class="row mt-5">
                        <div class="col-sm-12 col-md-6">

                        </div>
                        <div class="col-sm-12 col-md-6 text-right">
                            <button data-target="#ModalExport" data-toggle="modal" type="button" class="btn btn-warning add-new"
                                {{ count($accessories) ? '' : 'disabled' }}><i class="fa fa-file-excel-o"></i> Export
                                file</button>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12 table-responsive">
                            <table class="table table-striped table-bordered dataTable no-footer"
                                id="category-table" cellspacing="0" width="100%" role="grid"
                                aria-describedby="category-table_info"
                                style="display:block;width: 100%;overflow-x: scroll;white-space: nowrap;">
                                <thead>
                                    <tr role="row">
                                        <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            aria-sort="ascending" aria-label="ID: activate to sort column descending"
                                            style="width: 70px;">STT
                                        </th>
                                        <th class="" tabindex="0" aria-controls="category-table"
                                            rowspan="1" colspan="1" style="width: 164.5px;">Tên kho</th>
                                        <th class="" tabindex="0" aria-controls="category-table"
                                            rowspan="1" colspan="1" style="width: 164.5px;">Tên Vị trí</th>
                                        <th class="{{ $key_name == 'code' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;" wire:click='sorting("code")'>Mã phụ tùng</th>
                                        <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;" >Tồn đầu</th>
                                        <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;">Nhập mua</th>
                                        <th>Giá nhập</th>
                                        <th class="" tabindex="0" aria-controls="category-table"
                                            rowspan="1" colspan="1" style="width: 164.5px;">Nhập chuyển kho</th>
                                        <th class="" tabindex="0" aria-controls="category-table"
                                            rowspan="1" colspan="1" style="width: 164.5px;">Xuất bán</th>
                                        <th>Giá bán</th>
                                        <th class="" tabindex="0" aria-controls="category-table"
                                            rowspan="1" colspan="1" style="width: 164.5px;">Xuất chuyển kho</th>
                                        <th class="" tabindex="0" aria-controls="category-table"
                                            rowspan="1" colspan="1" style="width: 164.5px;">Tồn</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($accessories as $key => $value)
                                        <tr data-parent="" data-index="1" role="row" class="odd">
                                            <td class="sorting_1">
                                                {{ ($accessories->currentPage() - 1) * $accessories->perPage() + $loop->iteration }}
                                            </td>
                                            <td>
                                                {{ @$value->warehouse->name }}
                                            </td>
                                            <td>
                                                {{ @$value->positionInWarehouse->name  }}
                                            </td>
                                            <td><a href="#" data-url="{{$value->warehouse_id . ','. $value->position_in_warehouse_id .','. $value->code .','. $value->quantity_first_log }}" class='td-accessory' > {!! boldTextSearchV2($value->code, $AccessoriesCode) !!} </a></td>
                                            <td>
                                                {{ $rests[$value->id] }}
                                            </td>
                                            <td>
                                                {{ $value->quantity_buy_input ?? 0 }}
                                            </td>
                                            <td>{{ numberFormat($value->price_in) ?? 0 }}</td>
                                            <td>
                                                {{ $value->quantity_input_trans ?? 0 }}
                                            </td>
                                            <td>
                                                {{ $value->quantity_sell_output ?? 0 }}
                                            </td>
                                            <td>{{ numberFormat($value->price_out) ?? 0 }}</td>
                                            <td>
                                                {{ $value->quantity_output_trans ?? 0 }}
                                            </td>
                                            <td>
                                                {{  $rests[$value->id] +
                                                    $value->quantity_buy_input +
                                                    $value->quantity_input_trans -
                                                    $value->quantity_sell_output -
                                                    $value->quantity_output_trans }}
                                            </td>
                                        </tr>

{{--                                        @if ($loop->last)--}}
{{--                                            <tr>--}}
{{--                                                <td colspan="3">TOTAL</td>--}}
{{--                                                <td>{{ $totalBegin }}</td>--}}
{{--                                                <td>{{ $totalIn }}</td>--}}
{{--                                                <td>{{numberFormat($totalPriceIn) ?? 0}}</td>--}}
{{--                                                <td>{{ $totalTransIn }}</td>--}}
{{--                                                <td>{{ $totalOut }}</td>--}}
{{--                                                <td>{{numberFormat($totalPriceOut) ?? 0}}</td>--}}
{{--                                                <td>{{ $totalTransOut }}</td>--}}
{{--                                                <td>{{ $totalStock }}</td>--}}
{{--                                            </tr>--}}
{{--                                        @endif--}}
                                    @empty
                                        <tr>
                                            <td colspan="11" class="text-center text-danger">Không có bản ghi nào.</td>
                                        </tr>
                                    @endforelse

                                </tbody>
                            </table>

                        </div>
                    </div>
                    @if (count($accessories) > 0)
                        {{ $accessories->links() }}
                    @endif
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


    <!-- start detail phu tung-->
    <div wire:ignore.self class="modal fade" id="ModalDetail" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-blue">
                    <h5 class="modal-title" id="exampleModalLabel">CHI TIẾT PHỤ TÙNG</h5>
                </div>
                <div class="modal-body">
                    <table class="table table-striped table-bordered table-hover dataTable no-footer"
                        id="category-table" cellspacing="0" width="100%" role="grid"
                        aria-describedby="category-table_info"
                        style="display:block;width: 100%;overflow-x: scroll;white-space: nowrap;">
                        <thead>
                            <tr role="row">
                                <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1">
                                    Ngày giờ
                                </th>
                                <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1">
                                    Mua
                                </th>
                                <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1">
                                    Bán
                                </th>
                                <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1">
                                    Sửa chữa /thay
                                </th>
                                <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1">
                                    Chuyển kho đến
                                </th>
                                <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1">
                                    Chuyển kho đi
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($detailpartinfo as $itempartinfo)
                            <tr>
                              <td>
                                  {{$itempartinfo->date}}
                              </td>
                              <td>
                                  {{$itempartinfo->buy_in}}
                              </td>
                              <td>
                                  {{$itempartinfo->sell_out}}
                              </td>
                              <td>
                                  {{$itempartinfo->repair_qty}}
                              </td>
                              <td>
                                  {{$itempartinfo->transfer_in}}
                              </td>
                              <td>
                                  {{$itempartinfo->transfer_out}}
                              </td>
                            </tr>
                            @empty
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>
    <!---end detail phu tung modal dialog -->

</div>

@section('js')
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            $('#Warehouses').on('change', function(e) {
                var data = $('#Warehouses').select2("val");
                @this.set('Warehouses', data);
            });
            $('#PositionInWarehouse').on('change', function(e) {
                var data = $('#PositionInWarehouse').select2("val");
                @this.set('PositionInWarehouse', data);
            });

            /*
            $('#categroy-table').DataTable({
                "scrollX": true
            }); */

            // var fromDate = new Date(); //new Date(date.getFullYear(), date.getMonth(), 1);
            // var toDate = new Date();
            // $('#fromDate').data("kendoDatePicker").value(fromDate);
            // $('#toDate').data("kendoDatePicker").value(toDate);
            // datafrom = $('#fromDate').data("kendoDatePicker").val();
            // datato = $('#toDate').data("kendoDatePicker").val();
            // @this.set('FromDate', datafrom);
            // @this.set('ToDate', datato);

        });
        $('.td-accessory').on('click',function(e){
                var val = $(this).data("url");
                if(val){
                    values = val.split(",");
                    @this.set('selectedwarehouse', values[0]);
                    @this.set('selectedposition', values[1]);
                    @this.set('selectedpartno', values[2]);
                    @this.set('selectedbegin', values[3]);
                    $("#ModalDetail").modal('show');
                }
            });
    </script>

@endsection
