<div >
    <input type="hidden" id="count_accessories" >
    <table class="table table-striped table-bordered readonly_input" id="table_inputs" >
        <thead>
            <tr>
                <th style="width:150px;">Mã PT</th>
                <th  style="width:230px;">Tên PT</th>
                <th style="width: 100px">Giá</th>
                <th style="width: 100px">SL đặt </th>
                <th style="width: 120px">Đã nhận</th>
                <th style="width: 100px">SL nhận</th>
                <th style="width: 100px">Kho nhập</th>
                <th style="width: 100px">Vị trí</th>

                <th style="width: 50px">&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><input  type="hidden" class="form-control"
                    wire:model="warehouse_id" id="warehouse_id" >
                </td>
            </tr>

            @if($accessoryNumber)
            <tr >
                <span style='display:none'>
                @foreach($accessoryNumber as $keyItem => $valueItem)
                {{ $totalOrderQty += $poQty[$keyItem] }}
                {{ $totalReceiptQty += $receiptQty[$keyItem] }}
                {{ $totalInputQty += $qty[$keyItem] }}
                @endforeach
                </span>
                <td colspan='2'></td>
                <td>TOTAL</td>
                <td> {{ $totalOrderQty  }}</td>
                <td> {{ $totalReceiptQty }}</td>
                <td>{{ $totalInputQty }}</td>
                <td colspan='2'></td>
            </tr>
            @foreach($accessoryNumber as $key => $value)
            <tr >
                <td>
                    <input disabled type="text" class="form-control"
                    wire:model.lazy="accessoryNumber.{{$key}}"
                    placeholder =""
                    id ="accessoryNumber.{{$key}}"
                     >
                    @error('accessoryNumber.'.$key)<span class="text-danger">{{ $message }}</span>@enderror
                </td>

                <td>
                    <input disabled type="text" class="form-control"
                    wire:model="accessoryName.{{$key}}"
                    placeholder =""
                    id = "accessoryName.{{$key}}" >
                      @error('accessoryName.'.$key)<span class="text-danger">{{ $message }}</span>@enderror
                </td>

                <td>
                    <input type="text" disabled  width='100px' class="format_number form-control"
                        wire:model.lazy="netPrice.{{$key}}" id="netPrice{{$key}}" >
                    @error('netPrice.'.$key)<span class="text-danger">{{ $message }}</span>@enderror
                </td>

                <!--TUDN 23Nov21 - Add vi tri kho, ton kho -->

                <td><input type="text" disabled placeholder='' class="format-number form-control"
                    wire:model.lazy="poQty.{{$key}}" id="poQty{{$key}}">
                </td>
                <td><input type="text" disabled placeholder='' class="format-number form-control"
                    wire:model.lazy="receiptQty.{{$key}}" id="receiptQty{{$key}}">
                </td>
                <!--end TUDN 23Nov21 --->

                <td><input {{ $showstatus ? 'disabled' : '' }}  type="text" class="format_number form-control bg-warning "
                        wire:model.lazy="qty.{{$key}}" id="qty{{$key}}">
                         @error('qty.'.$key)<span class="text-danger">{{ $message }}</span>@enderror
                         @error('stkqty.'.$key)<span class="text-danger">{{ $message }}</span>@enderror
                     <input type =hidden  wire:model.lazy="stkqty.{{$key}}" id="stkqty{{$key}}">
                </td>



                <td><input disabled type="text" 'disabled' width='250px' placeholder="" class="format-number form-control"
                    wire:model="whName.{{$key}}" id= "whName{{$key}}">
                </td>
                <td><input disabled type="text" 'disabled' width='250px' placeholder="" class="format-number form-control"
                    wire:model="whLocation.{{$key}}" id= "whLocation{{$key}}">
                </td>

                <td>
                    <button  @if ($showstatus) style='display:none' @endif wire:click.prevent="removeItem({{$key}})"  class="delete" data-toggle="tooltip"
                        data-original-title="Xóa">
                        <i class="fa fa-remove" ></i></button>
                </td>
            </tr>
            @endforeach
            @endif
        </tbody>
    </table>
                 @if (!$showstatus)
                    <div class="form-group row">
                        <div class="col-12 text-center">
                            <button name="submit" type="button" wire:click="createorder"  class="btn btn-primary">
                                @if ($order_id)
                                Cập nhật @else Tạo đơn nhập @endif
                            </button>
                        </div>
                    </div>
                @endif
</div>
@section('js')
    <script>
    window.addEventListener('checkalert', event => {
        var id = event.detail.id-1;
        var idx ="accessaryNumber.".concat(id);
        document.getElementById(idx).value =event.detail.name;

    });

    window.addEventListener('alertMessage', event => {
        var message = event.detail.name;
        alert(message);
    });





    </script>
@endsection
