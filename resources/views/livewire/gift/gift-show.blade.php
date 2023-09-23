<div>
    <div class="page-heading">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}"><i class="fa fa-home font-20"></i></a>
            </li>
            <li class="breadcrumb-item">Thông tin quà tặng</li>
        </ol>
    </div>
    <div class="page-content fade-in-up">
        <div wire:loading class="loader"></div>
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Thông tin quà tặng</div>
            </div>
            <div class="ibox-body">
                <form>
                    @csrf
                    <div class="form-group row">
                        <label for="giftName" class="col-1 col-form-label ">Tên quà tặng<span
                                class="text-danger"> *</span></label>
                        <div class="col-5">
                            <input id="giftName" type="text" class="form-control" wire:model.defer="giftName">
                            @error('giftName')
                                @include('layouts.partials.text._error')
                            @enderror
                        </div>
                        <label for="giftPoint" class="col-1 col-form-label ">Điểm quà tặng<span
                                class="text-danger"> *</span></label>
                        <div class="col-5">
                            <input id="giftPoint" type="text" class="form-control"
                                onkeypress="return onlyNumberKey(event)" wire:model.defer="giftPoint">
                            @error('giftPoint')
                                @include('layouts.partials.text._error')
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="warehouseProvince" class="col-1 col-form-label ">Kho<span
                                class="text-danger"> *</span></label>
                        <div class="col-5">
                            <select wire:model="warehouseProvince" id="warehouseProvince" class="custom-select select2-box">
                                <option hidden>Chọn Kho</option>
                                @foreach ($giftWarehouseList as $key => $item)
                                    <option value="{{ $item['gift_warehouse_id'] }}">{{ $item['gift_warehouse_name'] }}</option>
                                @endforeach
                            </select>
                            @error('warehouseProvince')<span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <label for="giftQuantity" class="col-1 col-form-label ">Số lượng</label>
                        <div class="col-5">
                            <input id="giftQuantity" type="text" class="form-control" wire:model.defer="giftQuantity"
                                onkeypress="return onlyNumberKey(event)">
                            @error('giftQuantity')
                                @include('layouts.partials.text._error')
                            @enderror
                        </div>
                    </div>
                </form>
            </div>

            <div class="ibox-head">
                <div class="ibox-title">Danh sách quà tặng đã quy đổi</div>
            </div>
            <div class="ibox-body">
                <div class="dataTables_wrapper dt-bootstrap4 no-footer">
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class="dataTables_length" id="category-table_length">
                                <label>Hiển thị
                                    <select name="category-table_length" aria-controls="category-table"
                                        wire:model="perPage" class="form-control form-control-sm">
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>
                                </label>
                            </div>
                        </div>
                    </div>
                    @if (count($history) > 0)
                        {{ $history->links() }}
                    @endif
                    <div class="row">
                        <div class="col-sm-12 table-responsive">
                            <table class="table table-striped table-bordered dataTable no-footer"
                                id="category-table" cellspacing="0" width="100%" role="grid"
                                aria-describedby="category-table_info" style="width: 100%;">
                                <thead>
                                    <tr role="row">
                                        <th wire:click="sorting('gift_name')"
                                            class="@if ($this->key_name == 'gift_name')
                                        {{ $sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc' }} @else sorting @endif"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1">Tên khách hàng</th>
                                        <th style="width: 150px;">Số điện thoại</th>
                                        <th style="width: 150px;">Số lượng</th>
                                        <th style="width: 150px;">Ngày đổi</th>
                                    </tr>
                                </thead>
                                <div wire:loading class="loader"></div>
                                <tbody>
                                    @forelse ($history as $item)
                                        <tr data-parent="" data-index="1" role="row" class="odd">
                                            <td>{{ $item->customer->name }}</td>
                                            <td>{{ $item->customer->phone }}</td>
                                            <td>1</td>
                                            <td>{{ $item->created_at }}</td>
                                        </tr>
                                    @empty
                                        <tr class="text-center text-danger">
                                            <td colspan="12">Không có bản ghi</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if (count($history) > 0)
                        {{ $history->links() }}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
