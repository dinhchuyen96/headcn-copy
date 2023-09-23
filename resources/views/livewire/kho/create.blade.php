<div>
    <div class="page-content fade-in-up">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Thông tin kho</div>
            </div>
            <div class="ibox-body">
                <div class="form-group row">
                    <label for="StorageName" class="col-1 col-form-label ">Tên kho<span class="text-danger">
                            *</span></label>
                    <div class="col-3">
                        <input id="StorageName" wire:model.defer="StorageName" placeholder="Tên kho" type="text"
                            class="form-control">
                        @error('StorageName')<span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <label for="StorageEstablished" class="col-1 col-form-label ">Ngày thành lập <span
                            class="text-danger"> *</span></label>
                    <div class="col-3">
                        <input type="date" id="StorageEstablished" wire:model.defer="StorageEstablished"
                            name="StorageEstablished" class="form-control" max='{{ now()->format('Y-m-d') }}'>
                        @error('StorageEstablished')<span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <label for="Address" class="col-1 col-form-label">Địa chỉ<span class="text-danger">
                            *</span></label>
                    <div class="col-3">
                        <input id="Address" wire:model.defer="Address" placeholder="Địa chỉ" type="text"
                            class="form-control">
                        @error('Address')<span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label for="warehouseProvince" class="col-1 col-form-label ">Thành phố/Tỉnh<span
                            class="text-danger"> *</span></label>
                    <div class="col-3">
                        <select wire:model="warehouseProvince" id="warehouseProvince" class="custom-select select2-box">
                            <option hidden>Chọn Thành phố/Tỉnh</option>
                            @foreach ($province as $key => $item)
                                <option value="{{ $key }}">{{ $item }}</option>
                            @endforeach
                        </select>
                        @error('warehouseProvince')<span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <label for="warehouseDistrict" class="col-1 col-form-label ">Quận/Huyện<span
                            class="text-danger"> *</span></label>
                    <div class="col-3 ">
                        <select wire:model="warehouseDistrict" id="warehouseDistrict" class="custom-select select2-box">
                            <option hidden>Chọn Quận/ Huyện</option>
                            @foreach ($district as $key => $item)
                                <option value="{{ $key }}">{{ $item }}</option>
                            @endforeach
                        </select>
                        @error('warehouseDistrict')<span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <label for="warehouseType" class="col-1 col-form-label ">Loại kho</label>
                    <div class="col-3">
                        <select wire:model="warehouseType" id="warehouseType" class="custom-select select2-box">
                            <option value="1">Kho thường</option>
                            <option value="2">Kho quà tặng</option>
                        </select>
                        @error('warehouseType')<span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="form-group row justify-content-center btn-group-mt">
                    <div>
                        <a href="{{ route('kho.danhsach.index') }}" class="btn btn-default">
                            <i class="fa fa-arrow-left"></i>
                            Trở lại
                        </a>
                        <button type="button" class="btn btn-primary" wire:click.prevent="store()"><i
                                class="fa fa-plus"></i> Tạo
                            mới</button>
                    </div>
                </div>

                @if ($warehouseType == 1)
                    <div class="table-responsive">
                        <div class="table-wrapper">
                            <div class="table-title">
                                <div class="row">
                                    <div class="col-sm-8">
                                        <h2>Danh sách vị trí kho</h2>
                                    </div>
                                    <div class="col-sm-4 text-right">
                                        <button type="button" wire:click.prevent="addPos({{ $i }})"
                                            class="btn btn-info add-new"><i class="fa fa-plus"></i> Thêm
                                            mới</button>
                                    </div>
                                </div>
                            </div>

                            <div id='table_input'>
                                <table class="table table-striped table-bordered readonly_input">
                                    <thead>
                                        <tr>
                                            <th>Tên vị trí</th>
                                            <th style="width:100px">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($positions as $key => $value)
                                            <tr>
                                                <td>
                                                    <input type="text" class="form-control"
                                                        wire:model="position_name.{{ $value }}">
                                                </td>
                                                <td class="align-middle text-center">
                                                    <a class="delete"
                                                        wire:click.prevent="removePos({{ $key }})"
                                                        data-toggle="tooltip" data-original-title="Xóa">
                                                        <i class="fa fa-remove"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@section('js')
    <script type="text/javascript">
        StorageEstablished.max = moment(new Date()).format('YYYY-MM-DD');
        document.addEventListener('DOMContentLoaded', function() {
            $('#warehouseProvince').on('change', function(e) {
                var data = $('#warehouseProvince').select2("val");
                @this.set('warehouseProvince', data);
            });
            $('#warehouseDistrict').on('change', function(e) {
                var data = $('#warehouseDistrict').select2("val");
                @this.set('warehouseDistrict', data);
            });
            $('#warehouseType').on('change', function(e) {
                var data = $('#warehouseType').val();
                @this.set('warehouseType', data);
            });

            setDatePickerUI();
        })

        document.addEventListener('setStorageEstablishedPicker', function() {
            setDatePickerUI();
        });

        function setDatePickerUI() {
            $("#StorageEstablished").kendoDatePicker({
                max: new Date(),
                format: 'dd/MM/yyyy',
                change: function() {
                    if (this.value() != null) {
                        window.livewire.emit('setStorageEstablished', {
                            ['StorageEstablished']: this.value() ? this.value().toLocaleDateString(
                                'en-US') : null
                        });
                    }
                }
            });
        };
    </script>
@endsection
