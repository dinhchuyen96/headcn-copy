<div>
    <div class="page-content fade-in-up">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">THAY ĐỔI PHỤ TÙNG XE</div>
            </div>
            <div class="ibox-body">
                <form>
                    <!-- CHANGE 2 BIKES --->
                    <div class='col-12'>
                        <div class="form-group row">
                            <div class='col-6'>
                                <select wire:model="orgbikeframeno" id="orgbikeframeno"
                                class="custom-select select2-box col-6">
                                    <option hidden value="">Chọn xe đổi </option>
                                    @foreach ($bikelist as $item)
                                        <option {{ $orgbikeframeno==$item->chassic_no ? 'selected' :'' }}
                                            value='{{ $item->chassic_no }}' >
                                        {{ $item->chassic_no }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('orgbikeframeno')<span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class='col-6'>
                                <input type='text' wire:model.lazy="newprice" id="newprice" placeholder='Giá đề xuất'
                                 class='form-control'>
                                @error('newprice')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class='col-6'>
                                <input type='text' disabled wire:model="orgmodelname"
                                 id="orgmodelname"  class='form-control'>
                                 @error('orgmodelname')<span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class='col-6'>
                                <textarea class='col-12' placeholder = 'Nội dung thay đổi'
                                wire:model.lazy="bikenote"></textarea>
                                @error('bikenote')<span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class='col-6'>
                                <label for="orgpart" class="col-form-label">Phụ tùng cần thay</label>
                            </div>
                            <div class='col-6 border-bottom'>
                                <label for="newpart" class="col-form-label ">Phụ tùng thay / thêm</label>
                                @error('newpartcode')<span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class='col-6'>
                                <select wire:model="orgpart" id="orgpart"
                                class="custom-select select2-box">
                                    <option hidden value="">Chọn phụ tùng</option>
                                    @foreach ($orgpartlist as $itempart)
                                        <option {{$orgpart==$itempart->code ? 'selected' :'' }}
                                        value="{{ $itempart->code }}">
                                        {{ $itempart->code.'-'.$itempart->name }}</option>
                                    @endforeach
                                </select>
                                @error('orgpart')<span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class='col-6'>
                                <select wire:model.lazy="newpart" id="newpart"
                                class="custom-select select2-box">
                                    <option hidden value="">Chọn phụ tùng</option>
                                    @foreach ($newpartlist as $item)
                                        <option {{ $newpart==$item->code ? 'selected' :'' }}
                                        value="{{ $item->code }}">{{ $item->code.'-'.$item->name }}</option>
                                    @endforeach
                                </select>
                                @error('newpart')<span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>

                        </div>
                        <div class="form-group row">
                            <div class='col-6'>
                                <table class="table table-striped table-bordered readonly_input" id="table_inputs" >
                                    <thead>
                                        <tr class='bg-secondary'>
                                            <th style="width:25%;">Mã PT</th>
                                            <th  style="width:35%;">Tên PT</th>
                                            <th style="width: 12%">SL</th>
                                            <th style="width: 23%">Giá</th>
                                            <th style="width: 5%">&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @if($orgpartcode)
                                    @foreach($orgpartcode as $key => $value)
                                        <tr>
                                            <td>
                                                <input disabled type="text" class="form-control"
                                                wire:model="orgpartcode.{{$key}}"
                                                title = '{{$value}}'
                                                placeholder =""
                                                id = "orgpartcode.{{$key}}" >
                                                @error('orgpartcode.'.$key)
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </td>
                                            <td>
                                                <input disabled type="text" class="form-control"
                                                wire:model="orgpartname.{{$key}}"
                                                placeholder =""
                                                id = "orgpartname.{{$key}}" >
                                                @error('orgpartname.'.$key)
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </td>
                                            <td>
                                                <input type="text" class="form-control"
                                                wire:model="orgpartqty.{{$key}}"
                                                placeholder =""
                                                id = "orgpartqty.{{$key}}" >
                                                @error('orgpartqty.'.$key)
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </td>
                                            <td>
                                                <input disabled type="text" class="form-control"
                                                wire:model="orgpartprice.{{$key}}"
                                                placeholder =""
                                                id = "orgpartprice.{{$key}}" >
                                                @error('orgpartprice.'.$key)
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </td>
                                            <td>
                                                <button wire:click.prevent="orgpartremoveItem({{$key}})"  class="delete" data-toggle="tooltip"
                                                data-original-title="Xóa">
                                                <i class="fa fa-remove" ></i></button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @endif
                                    </tbody>
                                </table>

                            </div>
                            <div class='col-6'>
                                <table class="table table-striped table-bordered readonly_input" id="table_inputs" >
                                        <thead>
                                            <tr class='bg-secondary' >
                                                <th style="width:25%;">Mã PT</th>
                                                <th  style="width:35%;">Tên PT</th>
                                                <th style="width: 12%">SL</th>
                                                <th style="width: 23%">Giá</th>
                                                <th style="width: 5%">&nbsp;</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @if($newpartcode)
                                        @foreach($newpartcode as $key => $value)
                                            <tr>
                                                <td>
                                                    <input disabled type="text" class="form-control"
                                                    wire:model="newpartcode.{{$key}}"
                                                    title='{{$value}}'
                                                    placeholder =""
                                                    id = "newpartcode.{{$key}}" >
                                                    @error('newpartcode.'.$key)
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <input disabled type="text" class="form-control"
                                                    wire:model="newpartname.{{$key}}"
                                                    placeholder =""
                                                    id = "newpartname.{{$key}}" >
                                                    @error('newpartname.'.$key)
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control"
                                                    wire:model="newpartqty.{{$key}}"
                                                    placeholder =""
                                                    id = "newpartqty.{{$key}}" >
                                                    @error('newpartqty.'.$key)
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <input disabled type="text" class="form-control"
                                                    wire:model="newpartprice.{{$key}}"
                                                    placeholder =""
                                                    id = "newpartprice.{{$key}}" >
                                                    @error('newpartprice.'.$key)
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <button wire:click.prevent="newpartremoveItem({{$key}})"  class="delete" data-toggle="tooltip"
                                                    data-original-title="Xóa">
                                                    <i class="fa fa-remove" ></i></button>
                                                </td>
                                            </tr>
                                        @endforeach
                                        @endif
                                        </tbody>
                                    </table>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <a href="{{ route('quanlykho.baocaokhoxemay.index') }}" class="btn btn-default">
                            <i class="fa fa-arrow-left"></i>
                            Trở lại
                        </a>
                        <button type="button" wire:click.prevent="ResetBikeInfo()" class="btn btn-secondary">Nhập lại</button>
                        <button type="button" wire:click.prevent="ChangeBikeInfoClick()" class="btn btn-primary "
                            >Đồng ý</button>
                    </div>
                </form>
            </div>
        </div>
    </div>





</div>

@section('js')
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            $('#orgbikeframeno').on('change', function(e) {
                var data = $('#orgbikeframeno').select2("val");
                @this.set('orgbikeframeno', data);
            });
            $('#orgpart').on('change', function(e) {
                var data = $('#orgpart').select2("val");
                @this.set('orgpart', data);
            });
            $('#newpart').on('change', function(e) {
                var data = $('#newpart').select2("val");
                @this.set('newpart', data);
            });
        })

    </script>


@endsection
