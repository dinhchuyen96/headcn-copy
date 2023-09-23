@php
use Carbon\Carbon;
@endphp
<div>
    <div class="page-content fade-in-up">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Thông tin khách hàng</div>
            </div>
            <div class="ibox-body">
                <form>
                    <div class="form-group row mt-1">
                        <label for="CustomerName" class="col-2 col-form-label ">Tên khách hàng</label>
                        <div class="col-4">
                            {{ $customer->name }}

                        </div>
                        <label for="CustomerCode" class="col-2 col-form-label ">Mã khách hàng</label>
                        <div class="col-4">
                            {{ $customer->code }}
                        </div>
                    </div>

                    <div class="form-group row mt-1">
                        <label for="Sex" class="col-2 col-form-label">Giới tính</label>
                        <div class="col-4">
                            @if ($customer->sex == 1)
                                Nam
                            @endif
                            @if ($customer->sex == 2)
                                Nữ
                            @endif
                        </div>
                        <label for="Birthday" class="col-2 col-form-label ">Ngày sinh</label>
                        <div class="col-4">
                            @if ($customer->birthday)
                                {{ Carbon::createFromFormat('Y-m-d', $customer->birthday)->format('d/m/Y') }}
                            @endif
                        </div>
                    </div>
                    <div class="form-group row mt-1">
                        <label for="PhoneNumber" class="col-2 col-form-label ">Số điện thoại</label>
                        <div class="col-4">
                            {{ $customer->phone }}
                        </div>
                        <label for="Address" class="col-2 col-form-label ">Địa chỉ</label>
                        <div class="col-4">
                            {{ $customer->address . (isset($customer->wardCustomer) ? ', ' . $customer->wardCustomer->name : '') . (isset($customer->districtCustomer) ? ', ' . $customer->districtCustomer->name : '') . (isset($customer->provinceCustomer) ? ', ' . $customer->provinceCustomer->name : '') }}
                        </div>
                    </div>
                    <div class="form-group row mt-1">
                        <label for="PhoneNumber" class="col-2 col-form-label ">Tích điểm</label>
                        <div class="col-4">
                            {{ numberFormat($customer->point) }}
                        </div>

                    </div>
                </form>
            </div>

            <div class="ibox-head">
                <div class="ibox-title">Danh sách quà tặng đã quy đổi</div>
                <span class="show-list-gift">Xem danh sách</span>
            </div>
            <div class="ibox-body gift-list-changed">
                <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
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
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1">Tên quà
                                            tặng</th>
                                        <th style="width: 150px;">Số lượng</th>
                                    </tr>
                                </thead>
                                <div wire:loading class="loader"></div>
                                <tbody>
                                    @forelse ($history as $item)
                                        <tr data-parent="" data-index="1" role="row" class="odd">
                                            <td>{{ $item->gift->gift_name }}</td>
                                            <td>1</td>
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

            <div class="ibox-head">
                <div class="ibox-title">Danh sách quà tặng quy đổi</div>
            </div>
            <div class="ibox-body">
                <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                    <div class="row">
                        <div class="col-sm-12 table-responsive">
                            <table class="table table-striped table-bordered dataTable no-footer"
                                id="category-table" cellspacing="0" width="100%" role="grid"
                                aria-describedby="category-table_info" style="width: 100%;">
                                <thead>
                                    <tr role="row">
                                        <th style="width: 10px;"></th>
                                        <th wire:click="sorting('gift_name')"
                                            class="@if ($this->key_name == 'gift_name')
                                        {{ $sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc' }} @else sorting @endif"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 50%;">Tên quà tặng</th>
                                        <th wire:click="sorting('quantity')"
                                            class="@if ($this->key_name == 'quantity')
                                        {{ $sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc' }} @else sorting @endif"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 150px;">Số lượng</th>
                                        <th wire:click="sorting('gift_point')"
                                            class="@if ($this->key_name == 'gift_point')
                                        {{ $sortingName == 'desc' ? 'sorting_desc' : 'sorting_asc' }} @else sorting @endif"
                                            tabindex="0" aria-controls="category-table" rowspan="1" colspan="1"
                                            style="width: 164.5px;">Điểm quà tặng</th>
                                    </tr>
                                </thead>
                                <div wire:loading class="loader"></div>
                                <tbody>
                                    @forelse ($data as $item)
                                        <tr data-parent="" data-index="1" role="row" class="odd">
                                            <td>
                                                <input type="checkbox" name="checkGift" wire:model="checkGift"
                                                    value="{{ $item->id }}" class="check-box-order">
                                            </td>
                                            <td>{{ $item->gift_name }}</td>
                                            <td>{{ numberFormat($item->quantity) }}</td>
                                            <td>{{ numberFormat($item->gift_point) }}</td>
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
                    @if (count($data) > 0)
                        {{ $data->links() }}
                    @endif
                </div>

            </div>

            <div class="ibox-footer">
                <div class="form-group row justify-content-center btn-group-mt">
                    <div class="col-1">
                        <button wire:click.prevent="store" type="button" class="btn btn-primary"><i class="fa fa-gift"></i>
                            Đổi quà</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('.show-list-gift').on('click', function(e) {
                $('.gift-list-changed').toggleClass('gift-list-changed-show');
                if ($('.gift-list-changed').hasClass('gift-list-changed-show')) {
                    $(this).text('Ẩn danh sách');
                } else {
                    $(this).text('Xem danh sách');
                }
            });
        });
    </script>
</div>
