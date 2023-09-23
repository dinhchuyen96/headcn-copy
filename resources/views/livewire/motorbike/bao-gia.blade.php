<div>
    <div class="page-content fade-in-up">
        <div class="ibox">
            <div class="ibox-body">
                <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                    <div class="row mt-5 p-b-5">
                        <div class="col-sm-12 col-md-6">
                            <div class="input-group col-6">
                                <input class="form-control border-end-0 border rounded-pill" type="text"
                                placeholder='Tìm kiếm' id="keyword"  wire:model='keyword'>
                                <span class="input-group-append">
                                    <button class="btn btn-outline-secondary bg-white border-start-0 border rounded-pill ms-n3"
                                    type="button" id='btnsimplesearch' >
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-6 text-right">
                            <button data-target="#ModalCreate" data-toggle="modal" type="button" class="btn btn-info">
                            <i class="fa fa-plus" aria-hidden="true"></i> Thêm mới</button>
                            <button data-target="#ModalExport" data-toggle="modal" type="button" class="btn btn-primary"
                                {{ count($data) ? '' : 'disabled' }}><i class="fa fa-file-excel-o"></i> Export
                                file</button>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-striped table-bordered table-hover dataTable no-footer"
                                id="proposal-table" cellspacing="0" width="100%" role="grid"
                                aria-describedby="proposal-table_info" style="width: 100%;">
                                <thead>
                                    <tr role="row">
                                        <th tabindex="0" aria-controls="proposal-table" rowspan="1" colspan="1"
                                            style="width: 70px;">STT
                                        </th>
                                        <th tabindex="0" style='width: 20%' aria-controls="proposal-table" rowspan="1" colspan="1">
                                        Tên khách hàng</th>
                                        <th tabindex="1" style='width: 20%' aria-controls="proposal-table" rowspan="1" colspan="1">
                                        MTOC</th>
                                        <th tabindex="0" style='width: 15%' aria-controls="proposal-table" rowspan="1" colspan="1">
                                        Mã PT</th>
                                        <th tabindex="0" style='width: 15%' aria-controls="proposal-table" rowspan="1" colspan="1">
                                        Công việc</th>
                                        <th tabindex="0" style='width: 10%' aria-controls="proposal-table" rowspan="1" colspan="1">
                                        Giá</th>
                                        <th tabindex="0" style='width: 10%'aria-controls="proposal-table" rowspan="1" colspan="1">
                                        Người tạo</th>
                                        <th tabindex="0" aria-controls="proposal-table" rowspan="1" colspan="1">
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data as $value)
                                        <tr data-parent="" data-index="1" role="row" class="odd">
                                            <td class="sorting_1">
                                                {{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
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


    <!-- create new modal --->
    <div wire:ignore.self class="modal fade" id="ModalCreate" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">TẠO BÁO GIÁ</h5>
                </div>
                <form>
                <div class="modal-body">
                    <!--first frame no --->
                    <div class="form-group row">
                        <div class='col-sm-12'>
                            <select wire:model="proposaltype" id="proposaltype"
                            class="custom-select select2-box col-sm-12">
                                <option hidden value="">Chọn Loại</option>
                                <option value =1 {{ $proposaltype ==1 ? 'selected' :'' }}>Xe máy</option>
                                <option value =2 {{ $proposaltype ==2 ? 'selected' :'' }}>Phụ tùng</option>
                                <option value =3 {{ $proposaltype ==3 ? 'selected' :'' }}>Dịch vụ</option>
                            </select>
                            @error('proposaltype')<span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class='col-sm-12'>
                            <input type='text' wire:model="customername" id="customername"
                             placeholder = 'Khách hàng'
                             class='form-control'>
                             @error('customername')<span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                        <input type=hidden wire:model ='customerid' id ='customerid' />
                    </div>
                    <div class="form-group row">
                        <div class='col-sm-12'>
                                <input type='text' wire:model="customerphone" id="customerphone"
                                placeholder = 'Số điện thoại'
                                class='form-control'>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class='col-sm-12'>
                                <input type='text' wire:model="customeraddress" id="customeraddress"
                                placeholder = 'Địa chỉ'
                                class='form-control'>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class='col-sm-12'>{{ $title }}</div>
                    </div>

                    <div class="form-group row">
                        @if ($proposaltype ==1)
                        <div class='col-sm-12'>
                            <select wire:model="mtoc" id="mtoc"
                            class="custom-select select2-box col-sm-12">
                            <option hidden value="">Chọn xe</option>
                            @if (isset($mtoclist))
                            @foreach ($mtoclist as $key=>$value)
                                <option value ='{{ $value->mtoc }}' > {{ $value->mtoc}} </option>
                            @endforeach
                            @endif
                            </select>
                            @error('mtoc')<span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                        @endif

                        <!-- Phu tung -->
                        @if ($proposaltype ==2)
                            <div class='col-sm-12'>
                                <select wire:model="partno" id="partno"
                                class="custom-select select2-box col-sm-12">
                                </select>
                                @error('partno')<span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                        @endif
                                            <!-- dich vu -->
                        @if ($proposaltype ==3)
                            <div class='col-sm-12'>
                                <select wire:model="jobcode" id="jobcode"
                                class="custom-select select2-box col-sm-12">
                                </select>
                                @error('jobcode')<span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                        @endif
                    </div>




                    <div class="form-group row">
                        <div class='col-sm-12'>
                            <input type='text' wire:model="price" id="price"
                            placeholder ='Giá bán'
                            class='form-control'>
                            @error('price')<span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class='col-sm-12'>
                            <textarea class='col-sm-12' placeholder = 'Ghi chú'
                             wire:model.lazy="propsalnote"></textarea>
                            @error('propsalnote')<span class="text-danger">{{$message}}</span>
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
    <!--END create new--->



</div>

@section('js')
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            $('#proposaltype').on('change', function(e) {
                var data = $('#proposaltype').select2("val");
                @this.set('proposaltype', data);
                var dataname = $('#proposaltype').select2("text");
                @this.set('title', dataname);
            });
        })

        $(document).ready(function() {
            $("#proposaltype").select2({ width: '100%' });
            $("#mtoc").select2({ width: '100%' });
        });

    </script>


@endsection
<style>
    .select2-container {
        width: 100% !important;
        padding: 0;
    }
</style>
