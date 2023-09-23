<div>
    <div class="page-heading">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}"><i class="fa fa-home font-20"></i></a>
            </li>
            <li class="breadcrumb-item">Danh sách quà tặng</li>
        </ol>
    </div>
    <div class="page-content fade-in-up">
        <div class="ibox">
            <div class="ibox-body">
                <form>
                    <div class="form-group row">
                        <div class="col-4">
                            <div id='simple-search-box'>
                                <div class="input-group">
                                    <input class="form-control border-end-0 border rounded-pill" type="text"
                                           placeholder='Tìm kiếm' id="keyword"  wire:model='keyword'>
                                    <span class="input-group-append">
                                <button class="btn btn-outline-secondary bg-white border-start-0 border rounded-pill ms-n3"
                                        type="button" id='btnsimplesearch' wire:click='simpleSearch'>
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                    <div class="row">
                        <div class="col-sm-12 col-md-2">
                        </div>
                        <div class="col-sm-12 col-md-10">
                            <div id="category-table_filter" class="dataTables_filter">
                                <button data-toggle="modal" data-target="#ModalStockout"
                                class="btn btn-outline-primary" alt='Thêm mới mã quà tặng'><i
                                        class="fa fa-arrow-circle-o-left"></i>
                                    Xuất kho quà tặng</button>
                                    <button data-toggle="modal" data-target="#ModalStockin"
                                class="btn btn-outline-primary" alt='Thêm mới mã quà tặng'><i
                                        class="fa fa-sign-out"></i>
                                    Nhập kho quà tặng</button>
                                    <button data-toggle="modal" data-target="#ModalNewGift" class="btn btn-outline-primary" ><i
                                        class="fa fa-plus"></i>
                                    Thêm mã</button>
                                <button data-target="#ModalExport" data-toggle="modal" type="button"
                                    class="btn btn-warning add-new" {{ count($data) ? '' : 'disabled' }}><i
                                        class="fa fa-file-excel-o"></i> Export file</button>
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
                                            style="width: 10%">Mã quà</th>
                                        <th wire:click="sorting('gift_name')"
                                            class="@if ($this->key_name == 'gift_name')
                                            {{ $sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc' }} @else sorting @endif"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 15%;">Tên quà tặng</th>
                                        <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                        style="width: 15%;" >Kho</th>
                                        <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 15%;">Vị trí</th>
                                        <th wire:click="sorting('gift_point')"
                                        class="@if ($this->key_name == 'gift_point')
                                        {{ $sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc' }} @else sorting @endif"
                                        tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                        style="">Rate/Điểm đổi </th>
                                        <th wire:click="sorting('quantity')"
                                            class="@if ($this->key_name == 'quantity')
                                            {{ $sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc' }} @else sorting @endif"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 15%;"  >Số lượng tồn</th>
                                        <th tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                        style="width: 15%;" >Thao tác</th>
                                    </tr>
                                </thead>
                                <!-- <div wire:loading class="loader"></div> -->
                                <tbody>
                                    @forelse ($data as $item)
                                        <tr data-parent="" data-index="1" role="row" class="odd">
                                            <td class="text-center">{{ $item->code }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->warehouse_name ? $item->warehouse_name : '' }}</td>
                                            <td>{{ $item->position_name ? $item->position_name : '' }}</td>
                                            <td>{{ numberFormat($item->rate) }}</td>
                                            <td>{{ numberFormat($item->qty) }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('quatang.show.index', $item->id) }}"
                                                    class="btn btn-warning btn-xs m-r-5" title="Sửa"><i
                                                        class="fa fa-eye font-14"></i></a>
                                                <a href="" data-toggle="modal" data-target="#ModalNewGift"
                                                    class="btn btn-primary btn-xs m-r-5" title="Sửa mã quà tặng"><i
                                                        class="fa fa-pencil font-14"></i></a>
                                                <a href="" wire:click="deleteId({{ $item->id }})"
                                                    data-toggle="modal" data-target="#ModalDelete"
                                                    class="btn btn-danger delete-category btn-xs m-r-5" title="Xóa"><i
                                                        class="fa fa-trash font-14"></i></a>
                                            </td>
                                        </tr>
                                        @if ($loop->last)
                                            <td colspan="5">TOTAL</td>
                                            <td>{{ $totalqty }}</td>
                                            <td></td>
                                        @endif
                                    @empty

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
    {{-- Modal Delete --}}
    <div wire:ignore.self class="modal fade" id="ModalDelete" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-backdrop fade in" style="height: 100%;"></div>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Xác nhận xóa</h5>
                </div>
                <div class="modal-body">
                    <p>Bạn có xóa không? Thao tác này không thể phục hồi!</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">Đóng</button>
                    <button type="button" wire:click.prevent="delete()" class="btn btn-danger close-modal"
                        data-dismiss="modal">Xóa</button>
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


    <div wire:ignore.self class="modal fade" id="ModalNewGift" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form>
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title color-white" id="exampleModalLabel">THÊM QUÀ TẶNG</h5>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <div class='col-sm-12'>
                            <input type='text' wire:model="giftcode" id="giftcode"  class='form-control'
                            placeholder = 'Mã / bắt buộc'>
                            @error('giftcode')<span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class='col-sm-12'>
                            <input type='text' wire:model="giftname" id="giftname"  class='form-control'
                            placeholder = 'Tên quà / bắt buộc'>
                            @error('giftname')<span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class='col-sm-12'>
                            <input type='text' wire:model="rate" id="rate"  class='form-control'
                            placeholder = 'Số điểm quy đổi / bắt buộc'>
                            @error('rate')<span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">Đóng</button>
                    <button type="button" wire:click.prevent="createNewGift()" class="btn btn-primary">Đồng ý</button>
                </div>
            </div>
            </form>
        </div>
    </div>
    <!-- end modal  -->
    <!-- nhap kho -->
    <div wire:ignore.self class="modal fade" id="ModalStockin" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title color-white" id="exampleModalLabel">NHẬP KHO QUÀ TẶNG</h5>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                    <label for="stockindate" class="col-12 col-form-label">Ngày nhập kho <span
                            class="text-danger">*</span></label>
                        <div class='col-sm-12'>
                            <input type="date" class="form-control input-date-kendo-edit" id="stockindate"
                            max='{{ date('Y-m-d') }}' wire:model.lazy="stockindate">
                            @error('stockindate')<span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class='col-sm-12'>
                            <select wire:model.lazy="selectgift" id="selectgift"
                                class="custom-select select2-box col-sm-12">
                                    <option hidden value="">Chọn Quà tặng</option>
                                    @foreach ($selectgiftlist as $item)
                                        <option {{ $selectgift==$item->id ? 'selected' :'' }}
                                        value="{{ $item->id }}">
                                        {{ $item->code .'-'. $item->name}}
                                        </option>
                                    @endforeach
                            </select>
                                @error('selectgift')<span class="text-danger">{{$message}}</span>
                                @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class='col-sm-12'>
                            <select wire:model.lazy="selectwarehousein" id="selectwarehousein"
                                class="custom-select select2-box col-sm-12">
                                    <option hidden value="">Chọn kho nhập</option>
                                    @foreach ($warehouselist as $item)
                                        <option {{ $selectwarehousein==$item->id ? 'selected' :'' }}
                                        value="{{ $item->id }}">
                                        {{ $item->name}}
                                        </option>
                                    @endforeach
                            </select>
                                @error('selectwarehousein')<span class="text-danger">{{$message}}</span>
                                @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class='col-sm-12'>
                            <select wire:model.lazy="selectpositionin" id="selectpositionin"
                                class="custom-select select2-box col-sm-12">
                                    <option hidden value="">Chọn vị trí nhập</option>
                                    @foreach ($positionlist as $item)
                                        <option {{ $selectpositionin==$item->id ? 'selected' :'' }}
                                        value="{{ $item->id }}">
                                        {{ $item->name}}
                                        </option>
                                    @endforeach
                            </select>
                                @error('selectpositionin')<span class="text-danger">{{$message}}</span>
                                @enderror
                        </div>

                    </div>
                    <div class="form-group row">
                        <div class='col-sm-12'>
                            <input type='text' wire:model.lazy="stockinqty" id="stockinqty"  class='form-control'
                            placeholder = 'Số lượng nhập / bắt buộc'>
                            @error('stockinqty')<span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">Đóng</button>
                    <button type="button" wire:click.prevent="doStockIn()" class="btn btn-primary">Đồng ý</button>
                </div>
            </div>
        </div>
    </div>
    <!--end modal nhap kho --->

     <!-- xuat kho -->

     <div wire:ignore.self class="modal fade" id="ModalStockout" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <form wire:Submit.prevent='doStockOut' >
                <div class="modal-header bg-primary">
                    <h5 class="modal-title color-white" id="exampleModalLabel">XUẤT KHO QUÀ TẶNG</h5>
                </div>
                <div class="modal-body">

                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="{{ $quatangnavlink==true ? 'nav-link active' : 'nav-link' }}"  id="nav-quatang-tab"  data-toggle="tab"
                            href="#quatang" role="tab" aria-controls="quatang" aria-selected="{{ $selectquatangtab }}">QUÀ TẶNG</a>
                        </li>
                        <li class="nav-item">
                            <a class="{{ $ordernavlink==true ? 'nav-link active' : 'nav-link' }}"  id="nav-order-tab" data-toggle="tab"
                            href="#order" role="tab"
                            aria-controls="order" aria-selected= "{{ $selectordertab }}" >THÔNG TIN ĐƠN HÀNG</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">

                        <!--qua tang TAB DETAIL -->
                        <div class= "{{ $quatangnavlink==true ? 'tab-pane fade show active' : 'tab-pane fade' }}"
                        id="quatang" role="tabpanel" aria-labelledby="quatang-tab">
                            <div class="form-group row">
                                 <label for="stockoutdate" class="col-12 col-form-label">Ngày xuất kho <span
                                    class="text-danger">*</span></label>
                                <div class='col-sm-12' wire:defer>
                                    <input type="date" class="form-control input-date-kendo-edit" id="stockoutdate"
                                    max='{{ date('Y-m-d') }}' value="{{ $stockoutdate }}" wire:model.lazy="stockoutdate">
                                    @error('stockoutdate')<span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class='col-sm-12'>

                                    <select wire:model.lazy="selectgiftout" id="selectgiftout"
                                        class="custom-select select2-box col-sm-12">
                                            <option hidden value="">Chọn Quà tặng</option>
                                            @foreach ($selectgiftlist as $item)
                                                <option {{ $selectgiftout==$item->id ? 'selected' :'' }}
                                                value="{{ $item->id }}">
                                                {{ $item->code .'-'. $item->name}}
                                                </option>
                                            @endforeach
                                    </select>
                                        @error('selectgiftout')<span class="text-danger">{{$message}}</span>
                                        @enderror

                                </div>
                            </div>
                            <div class="form-group row">
                                <div class='col-sm-12'>

                                    <select wire:model.lazy="selectwarehouseout" id="selectwarehouseout"
                                        class="custom-select select2-box col-sm-12">
                                            <option hidden value="">Chọn kho xuất</option>
                                            @foreach ($warehouselist as $item)
                                                <option {{ $selectwarehouseout==$item->id ? 'selected' :'' }}
                                                value="{{ $item->id }}">
                                                {{ $item->name}}
                                                </option>
                                            @endforeach
                                    </select>
                                        @error('selectwarehouseout')<span class="text-danger">{{$message}}</span>
                                        @enderror

                                </div>
                            </div>
                            <div class="form-group row">
                                <div class='col-sm-12'>
                                    <select wire:model.lazy="selectpositionout" id="selectpositionout"
                                        class="custom-select select2-box col-sm-12">
                                            <option hidden value="">Chọn vị trí xuất</option>
                                            @foreach ($positionlist as $item)
                                                <option {{ $selectpositionout==$item->id ? 'selected' :'' }}
                                                value="{{ $item->id }}">
                                                {{ $item->name}}
                                                </option>
                                            @endforeach
                                    </select>
                                        @error('selectpositionout')<span class="text-danger">{{$message}}</span>
                                        @enderror
                                </div>

                            </div>
                            <div class="form-group row">
                                <div class='col-sm-12'>
                                        <input type='text' wire:model.lazy="stockoutqty" id="stockoutqty"  class='form-control'
                                        placeholder = 'Số lượng xuất / bắt buộc'>
                                        @error('stockoutqty')<span class="text-danger">{{$message}}</span>
                                        @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class='col-sm-12'>
                                    <input type='text' wire:model.lazy="stockoutnote" id="stockoutnote"  class='form-control'
                                    placeholder = 'Lí do'>
                                    @error('stockoutnote')<span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                            </div>

                        </div>
                        <!--END qua tang TAB DETAIL -->

                        <!--DON HANG TAB DETAIL -->
                        <div class= "{{ $ordernavlink==true ? 'tab-pane fade show active' : 'tab-pane fade' }}"  id="order" role="tabpanel" aria-labelledby="order-tab">
                            <div class="form-group row">
                                <div class='col-sm-12'>
                                    <select name='customerPhone' id="customerPhone"
                                        data-ajax-url="{{ route('customers.getCustomerByPhoneOrName.index') }}"
                                        class="custom-select" >
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class='col-sm-12'>
                                    <span> {{$selectcustomerinfo}}</span>
                                    @if (isset($customerPhone))
                                    <a href="" wire:click.prevent="removeselectedcustomer({{ $customerPhone }})"
                                            class="btn btn-danger delete-category btn-xs m-r-5" title="Xóa"><i
                                                class="fa fa-trash font-14"></i></a>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class='col-sm-12 table-responsive'>

                                    @if(isset($customerorders))

                                    <table class="table table-striped table-bordered dataTable no-footer"
                                        id="orders-table" cellspacing="0" width="100%" role="grid"
                                        aria-describedby="orders-table_info"
                                        style="display:block;width: 100%;overflow-x: scroll;white-space: nowrap;">
                                        <thead>
                                            <tr role="row">
                                                <th tabindex="0" aria-controls="orders-table" rowspan="1" colspan="1"
                                                    style="width: 5%"><input type='checkbox'> </th>
                                                <th tabindex="0" aria-controls="orders-table" rowspan="1" colspan="1"
                                                    style="width: 5%">mã đơn</th>
                                                <th tabindex="0" aria-controls="orders-table" rowspan="1" colspan="1"
                                                   style="width: 15%">Số khung</th>
                                                <th tabindex="0" aria-controls="orders-table" rowspan="1" colspan="1"
                                                   style="width: 15%">Số máy</th>
                                                <th tabindex="0" aria-controls="orders-table" rowspan="1" colspan="1"
                                                   style="width: 15%">Mã PT</th>
                                                <th tabindex="0" aria-controls="orders-table" rowspan="1" colspan="1"
                                                   style="width: 20%">Tên PT</th>
                                                <th tabindex="0" aria-controls="orders-table" rowspan="1" colspan="1"
                                                   style="width: 10%">SL</th>
                                                <th tabindex="0" aria-controls="orders-table" rowspan="1" colspan="1"
                                                   style="width: 10%">Giá</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                         @forelse($customerorders as $key=>$itemorder)
                                            <tr>
                                                <td>
                                                    <input  type="checkbox" class="form-checkbox h-6 w-6 text-green-500"
                                                    id = 'chkorders.{{ $itemorder->id }}'
                                                    onclick="setCheckBox('chkorders.{{ $itemorder->id }}')"
                                                    name ='chkorders'
                                                    value="{{ isset($itemorder->id) ?  $itemorder->id : ''}}"
                                                    @if(in_array($itemorder->id,$selectedOrders)) checked @endif
                                                    >
                                                 </td>
                                                <td>{{ isset($itemorder->id) ? $itemorder->id  : '' }}</td>
                                                <td>{{ isset($itemorder->frameno) ? $itemorder->frameno  : '' }}</td>
                                                <td>{{ isset($itemorder->engineno) ? $itemorder->engineno  : '' }}</td>
                                                <td>{{ isset($itemorder->partno) ? $itemorder->partno  : '' }}</td>
                                                <td>{{ isset($itemorder->partname) ? $itemorder->partname  : '' }}</td>
                                                <td>{{ isset($itemorder->quantity) ? numberFormat($itemorder->quantity)  : 0 }}</td>
                                                <td>{{ isset($itemorder->price) ? numberFormat($itemorder->price)  : 0 }}</td>
                                            </tr>
                                         @empty
                                            <tr class="text-center text-danger">
                                                <td colspan="8"></td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <!--END DON HANG TAB DETAIL -->
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">Đóng</button>
                    <button type="button" wire:click.prevent="doStockOut()" class="btn btn-primary">Đồng ý</button>
                </div>
            <form>
            </div>
        </div>
    </div>

    <!--end modal xuat kho --->


</div>





@section('js')
    <script type="text/javascript">
             document.addEventListener('livewire:load', function() {
            // Your JS here.


        })

        document.addEventListener('DOMContentLoaded', function() {
            // Your JS here.
            setDatePickerUI();
            setSelect2Customer();

            $('#selectgift').on('change', function(e) {
                var data = $('#selectgift').select2("val");
                @this.set('selectgift', data);
            });
            $('#selectwarehousein').on('change', function(e) {
                var data = $('#selectwarehousein').select2("val");
                @this.set('selectwarehousein', data);
            });
            $('#selectpositionin').on('change', function(e) {
                var data = $('#selectpositionin').select2("val");
                @this.set('selectpositionin', data);
            });


            $('#selectgiftout').on('change', function(e) {
                var data = $('#selectgiftout').select2("val");
                @this.set('selectgiftout', data);
            });
            $('#selectwarehouseout').on('change', function(e) {
                var data = $('#selectwarehouseout').select2("val");
                @this.set('selectwarehouseout', data);
            });
            $('#selectpositionout').on('change', function(e) {
                var data = $('#selectpositionout').select2("val");
                @this.set('selectpositionout', data);
            });



        })

        window.addEventListener('closeModalStockin', event => {
            $("#ModalStockin").modal('hide');
        })
        window.addEventListener('closeModalNewGift', event => {
            $("#ModalNewGift").modal('hide');
        })
        window.addEventListener('closeModalStockout', event => {
            $("#ModalStockout").modal('hide');
        })



        document.addEventListener('select2Customer', function() {
            setSelect2Customer();
        });


        function setCheckBox(id){
            var chk = document.getElementById(id);
            var chkvalue =chk.value;
            if (chk.checked) {
                window.livewire.emit('setSelectedOrder',{
                    chkvalue
                });
            }else{
                window.livewire.emit('unsetSelectedOrder',{
                    chkvalue
                });
            }
        }

        function setSelect2Customer() {
            let ajaxUrl = $('#customerPhone').data("ajaxUrl");
            $('#customerPhone').select2({
                ajax: {
                    url: ajaxUrl,
                    data: function(params) {
                        var query = {
                            search: params.term,
                        }
                        return query;
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        };
                        return false;
                    }
                },
                placeholder: 'Nhập tên hoặc SĐT để tìm kiếm',
            });
            $('#customerPhone').on('change', function(e) {
                var data = $('#customerPhone').select2("val");
                @this.set('customerPhone', data);
                window.livewire.emit('getCustomerOrders');
            });
        };

       $('#nav-quatang-tab').click(function(){
            @this.set('selectquatangtab', "true");
            @this.set('selectordertab', "false");
            @this.set('quatangnavlink', true);
            @this.set('ordernavlink', false);
            return false;
       })

       $('#nav-order-tab').click(function(){
            @this.set('selectordertab', "true");
            @this.set('selectquatangtab', "false");
            @this.set('quatangnavlink', false);
            @this.set('ordernavlink', true);
            return false;
       })

        document.addEventListener('setUpdateDatepicker', function() {
            setDatePickerUI();
        });



        function setDatePickerUI() {
            $("#stockindate").kendoDatePicker({
                max: new Date(),
                //value: new Date(),
                format: 'dd/MM/yyyy',
                change: function() {
                    if (this.value() != null) {
                        window.livewire.emit('setstockindate', {
                            ['stockindate']: this.value() ? this.value().toLocaleDateString('en-US') :
                                null
                        });
                    }
                }
            });

            $("#stockoutdate").kendoDatePicker({
                max: new Date(),
                //value: new Date(),
                format: 'dd/MM/yyyy',
                change: function() {
                    if (this.value() != null) {
                        window.livewire.emit('setstockoutdate', {
                            ['stockoutdate']: this.value() ? this.value().toLocaleDateString('en-US') :
                                null
                        });
                    }
                }
            });
        }

    </script>
@endsection
<style>
    .select2-container {
        width: 100% !important;
        padding: 0;
    }
</style>
