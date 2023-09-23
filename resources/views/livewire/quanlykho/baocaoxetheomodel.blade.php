    
<div>
    <div class="page-content fade-in-up">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Báo cáo kho xe máy theo model</div>
            </div>
            <div class="ibox-body">
                <form>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12">
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <select wire:model="Model" id="Model" class="custom-select select2-box">
                                        <option hidden value="">Chọn model</option>
                                        @foreach ($modelList as $item)
                                            <option value="{{str_replace(" ","",$item->m_mto_code)}}"
                                                {{ $item->m_mto_code == $Model ? ' selected' : '' }}
                                            >{{ $item->m_mto_code }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <select wire:model="Color" id="Color" class="custom-select select2-box">
                                       
                                        <option hidden value="">Chọn color</option>
                                        @foreach ($modelList_color as $item)
                                            <option value="{{ (str_replace(" ","",$item->m_color_name))}}"
                                                {{ (str_replace(" ","",$item->m_color_name)) == $Color ? 'selected' : '' }}
                                            >{{
                                                $item->m_color_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <select wire:model="warehouse" id="warehouse" class="custom-select select2-box">
                                        <option hidden value="">Chọn kho</option>
                                        @if(isset($warehouselist))
                                        @foreach ($warehouselist as $key => $item)
                                            <option value="{{ $key }}">{{ $item }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="dataTables_wrapper dt-bootstrap4 no-footer">
                    <div class="row mt-3">
                        <div class="col-sm-12 col-md-12 text-right">
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
                                            style="width: 20%">MTO code
                                        </th>
                                        <th>Color code</th>
                                        <th>Color name</th>
                                        <th>Kho</th>
                                        <th class="" tabindex="0" aria-controls="category-table"
                                            rowspan="1" colspan="1" style="width: 164.5px;">Số lượng</th>
                                        <th>Giá</th>
                                        <th>Thành tiền</th>
                                    </tr>
                                </thead>
                                <div wire:loading class="loader"></div>
                                <tbody>


                                    @forelse ($data as $value)
                                        @if ($loop->first)
                                        <tr>
                                            <td colspan='4'><span class="font-weight-bold"> TỔNG (TOTAL) </span></td>
                                            <td colspan="1" class="font-weight-bold">{{
                                              isset($totalnumber) ?  number_format($totalnumber) : 0 }}</td>
                                            <td colspan="1"></td>
                                            <td colspan="1" class="font-weight-bold">{{
                                              isset($totalprice) ?  number_format($totalprice) :0 }}</td>
                                            
                                        </tr>
                                        @endif
                                        <tr data-parent="" data-index="1" role="row" class="odd">
                                            <td>
                                                {{ $value->m_mto_code }}
                                            </td>
                                            <td>{{ $value->m_color_code}}</td>
                                            <td>{{ $value->color}}</td>
                                            <td>{{ isset($value->warehouse_name) ? $value->warehouse_name : ''}}</td>
                                            <td>
                                                {{ isset($value->total_number) ? $value->total_number : 0}}
                                            </td>
                                            <td>
                                                {{ isset($value->m_price) ? number_format($value->m_price) : 0}}
                                            </td>
                                            <td>{{
                                                isset($value->total_number) && isset($value->m_price) ?
                                                number_format($value->total_number *  $value->m_price) :0
                                            }}</td>
                                        </tr>

                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-danger">Không có bản ghi nào.</td>
                                        </tr>
                                    @endforelse
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
            $('#Model').on('change', function(e) {
                var data = $('#Model').select2("val");
                @this.set('Model', data);
                window.livewire.emit('filterColor', data);
            });
            $('#Color').on('change', function(e) {
                var data = $('#Color').select2("val");
                @this.set('Color', data);
            });
            $('#warehouse').on('change', function(e) {
                var data = $('#warehouse').select2("val");
                @this.set('warehouse', data);
            });
        })
    </script>
@endsection
