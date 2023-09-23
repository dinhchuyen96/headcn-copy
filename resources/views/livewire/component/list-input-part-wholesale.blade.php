<div class="row">
<div class="col-sm-12 table-responsive">

    <input type="hidden" id="count_accessories" >
    <table class="table table-striped table-bordered dataTable no-footer"
    id="category-table" cellspacing="0" width="100%" role="grid"
    aria-describedby="category-table_info" width: 100%;overflow-x: scroll;white-space: nowrap;">
        <thead width="100%" >
            <tr>
                <th tabindex="0" width='10%' aria-controls="category-table" rowspan="1" colspan="1">Mã PT</th>
                <th tabindex="0" {{$type == 1 ? "width=15%" : "width=25%"}} aria-controls="category-table" rowspan="1" colspan="1">Tên PT</th>
                <!--TUDN 23Nov21 -- add vitri kho, ton kho -->
                <th tabindex="0" {{$type == 1 ? "width=10%" : "width=20%"}}  aria-controls="category-table" rowspan="1" colspan="1">Vị trí kho</th>
                <th tabindex="0" width='10%' aria-controls="category-table" rowspan="1" colspan="1">Tồn</th>
                <!--END TUDN -->
                <th tabindex="0"  width='10%' aria-controls="category-table" rowspan="1" colspan="1">SL Bán</th>
                <th tabindex="0"  width='10%' aria-controls="category-table" rowspan="1" colspan="1">Giá Nhập</th>
                @if($type == 1)
                    <th tabindex="0"  width='10%' aria-controls="category-table" rowspan="1" colspan="1">Giá N.Yết </th>
                    <th tabindex="0"  width='10%' aria-controls="category-table" rowspan="1" colspan="1">Giá In HĐ</th>
                @endif
                <th tabindex="0"  width='10%' aria-controls="category-table" rowspan="1" colspan="1">Giá TT</th>
                <th tabindex="0"  width='10%' aria-controls="category-table" rowspan="1" colspan="1">&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan=10><input  type="hidden" class="form-control"
                    wire:model="warehouse_id" id="warehouse_id" >
                </td>
            </tr>

            @if($accessoryNumber)
            @foreach($accessoryNumber as $key => $value)

            <tr >
                <td>
                    <input disabled   type="text" class="form-control"
                    wire:model.lazy="accessoryNumber.{{$key}}"
                    placeholder ="Mã phụ tùng"
                    id ="accessoryNumber.{{$key}}"
                     >
                    @error('accessoryNumber.'.$key)<span class="text-danger">{{ $message }}</span>@enderror
                </td>

                <td>
                    <input disabled  type="text" class="form-control"
                    wire:model="accessoryName.{{$key}}"
                    placeholder ="tên phụ tùng"
                    id = "accessoryName.{{$key}}" >
                      @error('accessoryName.'.$key)<span class="text-danger">{{ $message }}</span>@enderror
                </td>

                <!--TUDN 23Nov21 - Add vi tri kho, ton kho -->
                <td><input disabled  type="text"  placeholder="Vị trí" class="format-number form-control"
                    wire:model="whLocation.{{$key}}" id= "whLocation{{$key}}">
                </td>
                <td><input type="text" disabled placeholder='Tồn' class="format-number form-control"
                    wire:model.lazy="stockQty.{{$key}}" id="stockQty{{$key}}">
                </td>
                <!--end TUDN 23Nov21 --->

                <td><input {{ $showstatus ? 'disabled' : '' }}  type="text" class="format_number form-control bg-warning "
                        wire:model.lazy="qty.{{$key}}" id="qty{{$key}}" >
                         @error('qty.'.$key)<span class="text-danger">{{ $message }}</span>@enderror
                </td>

                <td>
                    <input type="text" disabled  class="format_number form-control"
                        wire:model.lazy="orderPrice.{{$key}}" id="orderPrice{{$key}}" >
                    @error('orderPrice.'.$key)<span class="text-danger">{{ $message }}</span>@enderror
                </td>
                @if($type == 1)
                    <td>
                        <input type="text" disabled class="format_number form-control"
                            wire:model.lazy="netPrice.{{$key}}" id="netPrice{{$key}}" >
                        @error('netPrice.'.$key)<span class="text-danger">{{ $message }}</span>@enderror
                    </td>

                    <td><input {{ $showstatus ? 'disabled' : '' }}  type="text"  class="format_number form-control bg-warning"
                            wire:model.lazy="vatPrice.{{$key}}" id="vatPrice.{{$key}}"
                            maxlength="10">
                        @error('vatPrice.'.$key)<span class="text-danger">{{ $message }}</span>@enderror
                    </td>
                @endif
                <td><input {{ $showstatus ? 'disabled' : '' }}  type="text"   class="format_number form-control bg-warning"
                    wire:model.lazy="actPrice.{{$key}}" maxlength="10" id="actPrice.{{$key}}">
                    @error('actPrice.'.$key)<span class="text-danger">{{ $message }}</span>@enderror
                </td>
                <td>
                    <button  @if ($showstatus) style='display:none' @endif wire:click.prevent="removeItem({{$key}})"  class="delete" data-toggle="tooltip"
                        data-original-title="Xóa">
                        <i class="fa fa-remove" ></i></button>
                </td>
            </tr>
                @if ($loop->last)
                <tr>
                    <td colspan=3 class="font-weight-bold"> TOTAL</td>
                    <td class="font-weight-bold">{{ $totalstock }} </td>
                    <td class="font-weight-bold">{{ $totalsell }} </td>
                    <td colspan=5 class="font-weight-bold"></td>
                </tr>
                @endif
            @endforeach
            @endif
        </tbody>
    </table>
    <div class="form-group row">
        <div class="col-12 text-right">
            <button @if ($showstatus) style='display:none' @endif  name="submit" type="button" wire:click="createorder"
            class="btn btn-primary" {{$showstatus?'disabled':''}}>@if(!$order_id)Tạo đơn @else Cập nhật @endif</button>
            <button name="submit" type="button" wire:click="printorder" class="btn btn-secondary" style='display:none'>
            <i  class="fa fa-print" aria-hidden="true"></i>
            In Phiếu</button>
        </div>
    </div>
</div>
</div>
@section('js')
    <script>
    document.addEventListener('livewire:load', function() {
        $('#category-table').DataTable( {
                "scrollX": true
            } );

    });

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
