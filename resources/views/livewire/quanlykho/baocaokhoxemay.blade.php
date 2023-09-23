<div>
    <div class="page-content fade-in-up">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Báo cáo kho xe máy</div>
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
                        <label for="EngineNumber" class="col-1 col-form-label ">Màu xe</label>
                        <div class="col-3">
                            <input id="Color" wire:model="Color" placeholder="Màu xe" type="text"
                                class="form-control">
                        </div>
                        <label for="Model" class="col-1 col-form-label">Model</label>
                        <div class="col-3">
                            <input id="Model" wire:model="Model" placeholder="Model" type="text" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">

                        <label for="EngineNumber" class="col-1 col-form-label ">Số máy</label>
                        <div class="col-3">
                            <input id="EngineNumber" wire:model="EngineNumber" placeholder="Số máy" type="text"
                                class="form-control">
                        </div>
                        <label for="ChassicNumber" class="col-1 col-form-label">Số khung</label>
                        <div class="col-3">
                            <input id="ChassicNumber" wire:model="ChassicNumber" placeholder="Số khung" type="text"
                                class="form-control">
                        </div>
                        <label for="Time" class="col-1 col-form-label ">Thời gian</label>
                        @include('layouts.partials.input._inputDateRanger')
                    </div>

                    <div class="form-group row justify-content-center">
                        @include('layouts.partials.button._reset')
                    </div>
                </form>
                <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                    <div class="row">
                        <div class="col-sm-12 text-right">
                            <a href="{{ route('quanlykho.thaydoiphutungxe.index') }}" class="btn btn-secondary">
                            <i class="fa fa-cogs" aria-hidden="true"></i> Thay phụ tùng</button>
                            </a>
                            <button data-target="#ModalChangeBikeInfo" data-toggle="modal" type="button" class="btn btn-info">
                            <i class="fa fa-exchange" aria-hidden="true"></i> Đổi phụ tùng 2 xe</button>
                            <button data-target="#ModalExport" data-toggle="modal" type="button" class="btn btn-warning add-new"
                                {{ count($data) ? '' : 'disabled' }}><i class="fa fa-file-excel-o"></i> Export
                                file</button>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12 table-responsive">
                            <table class="table table-striped table-bordered dataTable no-footer"
                                id="category-table" cellspacing="0" width="100%" role="grid"
                                aria-describedby="category-table_info" style="width: 100%;">
                                <thead>
                                    <tr role="row">
                                        <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            aria-sort="ascending" aria-label="ID: activate to sort column descending"
                                            style="width: 70px;">STT
                                        </th>
                                        <th class="{{ $key_name == 'warehouse_id' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;" wire:click='sorting("warehouse_id")'>Kho</th>
                                        <th class="{{ $key_name == 'chassic_no' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;" wire:click='sorting("chassic_no")'>Số khung</th>
                                        <th class="{{ $key_name == 'engine_no' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;" wire:click='sorting("engine_no")'>Số máy</th>
                                        <th class="{{ $key_name == 'model_code' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;" wire:click='sorting("model_code")'>Model</th>
                                        <th class="{{ $key_name == 'color' ? ($sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc') : 'sorting' }}"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;" wire:click='sorting("color")'>Màu xe</th>
                                        <th class="" tabindex="0" aria-controls="category-table"
                                            rowspan="1" colspan="1" style="width: 164.5px;">Tồn đầu</th>
                                        <th class="" tabindex="0" aria-controls="category-table"
                                            rowspan="1" colspan="1" style="width: 164.5px;">Nhập mua</th>
                                        <th class="" tabindex="0" aria-controls="category-table"
                                            rowspan="1" colspan="1" style="width: 164.5px;">Nhập chuyển kho</th>
                                        <th class="" tabindex="0" aria-controls="category-table"
                                            rowspan="1" colspan="1" style="width: 164.5px;">Xuất bán</th>
                                        <th class="" tabindex="0" aria-controls="category-table"
                                            rowspan="1" colspan="1" style="width: 164.5px;">Xuất chuyển kho</th>
                                        <th class="" tabindex="0" aria-controls="category-table"
                                            rowspan="1" colspan="1" style="width: 164.5px;">Tồn</th>
                                    </tr>
                                </thead>
                                <div wire:loading class="loader"></div>
                                <tbody>
                                    @forelse ($data as $value)
                                        @if ($loop->first)
                                        <tr class='font-bold'>
                                            <td colspan=6> TOTAL</td>
                                            <td> {{  $totalbegin_qty}}</td>
                                            <td> {{  $totalbuy_qty}}</td>
                                            <td> {{  $totaltransferin_qty}}</td>
                                            <td> {{  $totalsale_qty}}</td>
                                            <td> {{  $totaltransferout_qty}}</td>
                                            <td> {{  $totalstock_qty}}</td>
                                        </tr>
                                        @endif
                                        <tr data-parent="" data-index="1" role="row" class="odd">
                                            <td class="sorting_1">
                                                {{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}
                                            </td>
                                            <td>
                                                {{ !empty($value->warehouse_name) ? $value->warehouse_name : '' }}
                                            </td>
                                            <td>
                                                {{ $value->chassic_no }}
                                            </td>
                                            <td>
                                                {{ $value->engine_no }}
                                            </td>
                                            <td>
                                                {{ $value->model_code }}
                                            </td>
                                            <td>
                                                {{ $value->color }}
                                            </td>
                                            <td>
                                            {{ $value->begin_qty}}
                                            </td>
                                            <td>
                                            {{ $value->buyin_qty}}
                                            </td>
                                            <td>
                                            {{ $value->transferin_qty}}
                                            </td>
                                            <td>
                                            {{ $value->sale_qty}}
                                            </td>
                                            <td>
                                            {{ $value->transferout_qty}}
                                            </td>
                                            <td>
                                            {{ $value->stock_qty}}
                                            </td>
                                        </tr>
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

    <!-- CHANGE 2 BIKES --->
    <div wire:ignore.self class="modal fade" id="ModalChangeBikeInfo" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Đổi phụ tùng 2 xe</h5>
                </div>
                <form>
                <div class="modal-body">
                    <!--first frame no --->
                    <div class="form-group row">
                        <div class='col-sm-12'>
                            <select wire:model="firstbikeframeno" id="firstbikeframeno"
                            class="custom-select select2-box col-sm-12">
                                <option hidden value="">Chọn xe đổi</option>
                                @foreach ($bikelist as $item)
                                    <option {{ $firstbikeframeno==$item->chassic_no ? 'selected' :'' }}
                                    value="{{ $item->chassic_no }}">{{ $item->chassic_no }}</option>
                                @endforeach
                            </select>
                            @error('firstbikeframeno')<span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class='col-sm-12'>
                            <input type='text' disabled wire:model="firstmodelname" id="firstmodelname"  class='form-control'>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="firstbikeprice" class="col-12 col-form-label ">Giá mới</label>
                    </div>
                    <div class="form-group row">
                        <div class='col-sm-12'>
                            <input type='text' wire:model="firstbikeprice" id="firstbikeprice"  class='form-control'>
                            @error('firstbikeprice')<span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                    <!--second frame no --->
                    <div class="form-group row">
                        <div class='col-sm-12'>
                            <select wire:model="secondbikeframeno" id="secondbikeframeno"
                            class="custom-select select2-box col-sm-12">
                                <option hidden value="">Chọn xe đổi</option>
                                @foreach ($bikelist as $item)
                                    <option {{ $secondbikeframeno==$item->chassic_no ? 'selected' :'' }}
                                    value="{{ $item->chassic_no }}">{{ $item->chassic_no }}</option>
                                @endforeach
                            </select>
                            @error('secondbikeframeno')<span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class='col-sm-12'>
                            <input type='text' disabled wire:model="secondmodelname" id="secondmodelname"  class='form-control'>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="secondbikeprice" class="col-12 col-form-label ">Giá mới</label>
                    </div>
                    <div class="form-group row">
                        <div class='col-sm-12'>
                            <input type='text' wire:model="secondbikeprice" id="secondbikeprice"  class='form-control'>
                            @error('secondbikeprice')<span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class='col-sm-12'>
                            <textarea class='col-sm-12' placeholder = 'Nội dung thay đổi'
                             wire:model.lazy="bikenote"></textarea>
                            @error('bikenote')<span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">Đóng</button>
                    <button type="button" wire:click.prevent="ChangeBikeInfo()" class="btn btn-primary "
                        >Đồng ý</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <!--END CHANGE 2 BIKES --->




</div>

@section('js')
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            $('#Warehouses').on('change', function(e) {
                var data = $('#Warehouses').select2("val");
                @this.set('Warehouses', data);
            });
        })


        document.addEventListener('DOMContentLoaded', function() {
            $('#firstbikeframeno').on('change', function(e) {
                var data = $('#firstbikeframeno').select2("val");
                @this.set('firstbikeframeno', data);
            });
        })

        document.addEventListener('DOMContentLoaded', function() {
            $('#secondbikeframeno').on('change', function(e) {
                var data = $('#secondbikeframeno').select2("val");
                @this.set('secondbikeframeno', data);
            });
        })

        document.addEventListener('DOMContentLoaded', function() {
            $("#firstbikeframeno").select2({
                dropdownParent: $("#ModalChangeBikeInfo")
            });

            $("#secondbikeframeno").select2({
                dropdownParent: $("#ModalChangeBikeInfo")
            });

        })


        $(document).ready(function() {
            $("#firstbikeframeno").select2({ width: '100%' });
            $("#secondbikeframeno").select2({ width: '100%' });
        });

    </script>


@endsection
<style>
    .select2-container {
        width: 100% !important;
        padding: 0;
    }
</style>
