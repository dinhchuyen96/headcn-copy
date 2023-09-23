@php
use Carbon\Carbon;
@endphp
<div>
    <div wire:loading class="loader"></div>
    <div class="page-content fade-in-up">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Thông tin phiếu bảo dưỡng</div>
            </div>

            <div class="ibox-body">

                @if (!$addNew && !$showStatus && !$editStatus)
                    <div class="notice-get-motobike font-weight-bold" style="font-style: normal;">
                        <i class="fa fa-exclamation-circle" aria-hidden="true"></i> Chỉ tạo được phiếu kiểm tra định kỳ
                        đối
                        với những xe bán trước ngày {{ Carbon::now()->subDays(7)->format('d/m/Y') }} (trước 7 ngày) và
                        sau ngày {{ Carbon::now()->addYears(-3)->format('d/m/Y') }} (trong 3 năm)
                    </div>
                @endif

                @if (!$showStatus && !$editStatus)
                    <div class="form-group row">
                        <label for="searchChassicNo" class="col-2 col-form-label ">Số khung</label>
                        <div class="col-4">
                            <input type="text" name="searchChassicNo" class="form-control size13 search-input"
                                wire:model.lazy="searchChassicNo" id='searchChassicNo' autocomplete="off">
                            @error('searchChassicNo')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <label for="searchEngineNo" class="col-2 col-form-label ">Số máy</label>
                        <div class="col-4">
                            <input type="text" name="searchEngineNo" class="form-control size13 search-input"
                                wire:model.lazy="searchEngineNo" id='searchEngineNo' autocomplete="off">
                            @error('searchEngineNo')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="searchPhone" class="col-2 col-form-label ">Số điện thoại KH</label>
                        <div class="col-4">
                            <input type="text" name="searchPhone" class="form-control size13 search-input"
                                wire:model.lazy="searchPhone" id='updatedSearchPhone' autocomplete="off"
                                onkeypress="return onlyNumberKey(event)">
                        </div>
                        <label for="searchMotorNumber" class="col-2 col-form-label ">Biển số xe</label>
                        <div class="col-4">
                            <input type="text" name="searchMotorNumber" class="form-control size13 search-input"
                                wire:model.lazy="searchMotorNumber" id='searchMotorNumber' autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group row justify-content-center">
                        <div class="col-1">
                            <div class="row justify-content-center">
                                <button type="button" class="btn btn-primary" wire:click="search"><i
                                        class="fa fa-search"></i>
                                    Tìm kiếm</button>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="form-group row mt-5">
                    <label for="serviceRequest" class="col-2 col-form-label ">Triệu chứng/Yêu cầu KT
                    <span class="text-danger">*</span></label>
                    <div class="col-4">
                        <textarea class="form-control" id="serviceRequest" name="serviceRequest" rows="4"
                            {{ $showStatus ? 'disabled' : '' }} wire:model.lazy="serviceRequest"></textarea>
                        @error('serviceRequest')
                            @include('layouts.partials.text._error')
                        @enderror
                    </div>
                    <label for="serviceRequest" class="col-2 col-form-label ">Tư vấn sửa chữa</label>
                    <div class="col-3">
                        <textarea class="form-control" id="contentSuggest" name="contentSuggest" rows="4"
                            wire:model.lazy="contentSuggest"></textarea>
                    </div>
                    <div class="col-1">
                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" id="beforeRepair" wire:model="beforeRepair">
                            <label class="form-check-label" for="beforeRepair">Trước sửa chữa</label>
                        </div>
                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" id="afterRepair" wire:model="afterRepair">
                            <label class="form-check-label" for="afterRepair">Sau sửa chữa</label>
                        </div>

                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" id="notNeedWash" wire:model="notNeedWash">
                            <label class="form-check-label" for="notNeedWash">Không cần rửa xe</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group row">
                            <label for="chassic_no" class="col-4 col-form-label ">Người kiểm tra</label>
                            <div class="col-8">
                                <select id="service_user_check_id" name="service_user_check_id"
                                    {{ $showStatus ? 'disabled' : '' }} wire:model.lazy="service_user_check_id"
                                    class="custom-select select2-box form-control">
                                    <option value="">--Chọn--</option>
                                    @foreach ($inspectionStaffs as $inspectionStaff)
                                        <option value="{{ $inspectionStaff->id }}">
                                            {{ $inspectionStaff->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('service_user_check_id')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="service_user_fix_id" class="col-4 col-form-label ">Nhân viên sửa chữa
                                chính</label>
                            <div class="col-8">
                                <select id="service_user_fix_id" name="service_user_fix_id"
                                    {{ $showStatus ? 'disabled' : '' }} wire:model.lazy="service_user_fix_id"
                                    class="custom-select select2-box form-control">
                                    <option value="">--Chọn--</option>
                                    @foreach ($listFixer as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('service_user_fix_id')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group row">
                            <label for="chassic_no" class="col-4 col-form-label ">Người tiếp nhận<span
                                    class="text-danger">*</span></label>
                            <div class="col-8">
                                <select id="service_user_id" name="service_user_id" wire:model.lazy="service_user_id"
                                    {{ $showStatus ? 'disabled' : '' }}
                                    class="custom-select select2-box form-control">
                                    <option value="">--Chọn--</option>
                                    @foreach ($technicalStaffs as $technicalStaff)
                                        <option value="{{ $technicalStaff->id }}">{{ $technicalStaff->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('service_user_id')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="service_user_export_id" class="col-4 col-form-label ">Nhân viên xuất
                                kho</label>
                            <div class="col-8">
                                <select id="service_user_export_id" name="service_user_export_id"
                                    {{ $showStatus ? 'disabled' : '' }} wire:model.lazy="service_user_export_id"
                                    class="custom-select select2-box form-control">
                                    <option value="">--Chọn--</option>
                                    @foreach ($listExporter as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('service_user_export_id')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div id="checkServiceTable">
                    <table style="width: 100%;text-align: center; margin-top:2.5rem" border="1">
                        <tbody>
                            <tr>
                                <td colspan="12"><strong>Kiểm tra phụ tùng tại quầy tiếp nhận</strong></td>
                                <td colspan="12"><strong>Kiểm tra phụ tùng tại xưởng dịch vụ</strong></td>
                            </tr>
                            <tr>
                                <td rowspan="2">Phụ tùng</td>
                                <td colspan="5">Kiểm tra (✔)</td>
                                <td rowspan="2">Phụ tùng</td>
                                <td colspan="5">Kiểm tra (✔)</td>
                                <td rowspan="2">Phụ tùng</td>
                                <td colspan="5">Kiểm tra (✔)</td>
                                <td rowspan="2">Phụ tùng</td>
                                <td colspan="5">Kiểm tra (✔)</td>
                            </tr>
                            <tr>
                                <td>O</td>
                                <td>D</td>
                                <td>T</td>
                                <td>V</td>
                                <td>B</td>
                                <td>O</td>
                                <td>D</td>
                                <td>T</td>
                                <td>V</td>
                                <td>B</td>
                                <td>O</td>
                                <td>D</td>
                                <td>T</td>
                                <td>V</td>
                                <td>B</td>
                                <td>O</td>
                                <td>D</td>
                                <td>T</td>
                                <td>V</td>
                                <td>B</td>
                            </tr>
                            <tr style="text-align: left;">
                                <td>Dầu phanh</td>
                                @for ($h = 1; $h <= 5; $h++)
                                    <td><input type="checkbox" wire:model="checkService" value="{{ $h }}" />
                                    </td>
                                @endfor
                                <td>Lốp trước</td>
                                @for ($h = 6; $h <= 10; $h++)
                                    <td><input type="checkbox" wire:model="checkService" value="{{ $h }}" />
                                    </td>
                                @endfor
                                <td>Dây phanh</td>
                                @for ($h = 11; $h <= 15; $h++)
                                    <td><input type="checkbox" wire:model="checkService" value="{{ $h }}" />
                                    </td>
                                @endfor
                                <td>Côn</td>
                                @for ($h = 16; $h <= 20; $h++)
                                    <td><input type="checkbox" wire:model="checkService" value="{{ $h }}" />
                                    </td>
                                @endfor
                            </tr>
                            <tr style="text-align: left;">
                                <td>Phanh trước</td>
                                @for ($h = 21; $h <= 25; $h++)
                                    <td><input type="checkbox" wire:model="checkService" value="{{ $h }}" />
                                    </td>
                                @endfor
                                <td>Lốp sau</td>
                                @for ($h = 26; $h <= 30; $h++)
                                    <td><input type="checkbox" wire:model="checkService" value="{{ $h }}" />
                                    </td>
                                @endfor
                                <td>Dầu số</td>
                                @for ($h = 31; $h <= 35; $h++)
                                    <td><input type="checkbox" wire:model="checkService" value="{{ $h }}" />
                                    </td>
                                @endfor
                                <td>Chổi than</td>
                                @for ($h = 36; $h <= 40; $h++)
                                    <td><input type="checkbox" wire:model="checkService" value="{{ $h }}" />
                                    </td>
                                @endfor
                            </tr>
                            <tr style="text-align: left;">
                                <td>Phanh sau</td>
                                @for ($h = 41; $h <= 45; $h++)
                                    <td><input type="checkbox" wire:model="checkService" value="{{ $h }}" />
                                    </td>
                                @endfor
                                <td>Dầu máy</td>
                                @for ($h = 46; $h <= 50; $h++)
                                    <td><input type="checkbox" wire:model="checkService" value="{{ $h }}" />
                                    </td>
                                @endfor
                                <td>Dây đai</td>
                                @for ($h = 51; $h <= 55; $h++)
                                    <td><input type="checkbox" wire:model="checkService" value="{{ $h }}" />
                                    </td>
                                @endfor
                                <td>Họng ga</td>
                                @for ($h = 56; $h <= 60; $h++)
                                    <td><input type="checkbox" wire:model="checkService" value="{{ $h }}" />
                                    </td>
                                @endfor
                            </tr>
                            <tr style="text-align: left;">
                                <td>Bóng đèn</td>
                                @for ($h = 61; $h <= 65; $h++)
                                    <td><input type="checkbox" wire:model="checkService" value="{{ $h }}" />
                                    </td>
                                @endfor
                                <td>Làm mát</td>
                                @for ($h = 66; $h <= 70; $h++)
                                    <td><input type="checkbox" wire:model="checkService" value="{{ $h }}" />
                                    </td>
                                @endfor
                                <td>Ắc quy</td>
                                @for ($h = 71; $h <= 75; $h++)
                                    <td><input type="checkbox" wire:model="checkService" value="{{ $h }}" />
                                    </td>
                                @endfor
                                <td>Bugi</td>
                                @for ($h = 76; $h <= 80; $h++)
                                    <td><input type="checkbox" wire:model="checkService" value="{{ $h }}" />
                                    </td>
                                @endfor
                            </tr>
                            <tr style="text-align: left;">
                                <td>Công tắc</td>
                                @for ($h = 81; $h <= 85; $h++)
                                    <td><input type="checkbox" wire:model="checkService" value="{{ $h }}" />
                                    </td>
                                @endfor
                                <td>Xích</td>
                                @for ($h = 86; $h <= 90; $h++)
                                    <td><input type="checkbox" wire:model="checkService" value="{{ $h }}" />
                                    </td>
                                @endfor
                                <td>Lọc gió</td>
                                @for ($h = 91; $h <= 95; $h++)
                                    <td><input type="checkbox" wire:model="checkService" value="{{ $h }}" />
                                    </td>
                                @endfor
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr style="text-align: left;">
                                <td>Còi</td>
                                @for ($h = 96; $h <= 100; $h++)
                                    <td><input type="checkbox" wire:model="checkService" value="{{ $h }}" />
                                    </td>
                                @endfor
                                <td>Công tơ mét</td>
                                @for ($h = 101; $h <= 105; $h++)
                                    <td><input type="checkbox" wire:model="checkService" value="{{ $h }}" />
                                    </td>
                                @endfor
                                <td>Nhông xích</td>
                                @for ($h = 106; $h <= 110; $h++)
                                    <td><input type="checkbox" wire:model="checkService" value="{{ $h }}" />
                                    </td>
                                @endfor
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="form-group row pt-4">
                    <label for="resultRepair" class="col-2 col-form-label ">Ghi chú sau KT</label>
                    <div class="col-10">
                        <textarea class="form-control" id="resultRepair" name="resultRepair" rows="4" {{ $showStatus ? 'disabled' : '' }}
                            wire:model.lazy="resultRepair"></textarea>
                        @error('resultRepair')
                            @include('layouts.partials.text._error')
                        @enderror
                    </div>
                </div>
                @if ($showViewHistoryBtn)
                    <div class="form-group row pt-4">
                        <div class="col-12 text-right">
                            <button data-target="#modal-form-view-history" data-toggle="modal" type="button"
                                class="btn btn-outline-primary">Xem lịch sử sửa chữa</button>
                        </div>
                        <div wire:ignore.self class="modal fade" id="modal-form-view-history" tabindex="-1"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Lịch sử sữa chửa</h5>
                                    </div>
                                    <div class="modal-body">
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th style="width:100px">Loại</th>
                                                    <th style="width:100px">Ngày KT</th>
                                                    <th style="width:50px">Lần</th>
                                                    <th style="width:120px">Tiền công</th>
                                                    <th style="width:50%">Nội dung thực hiện</th>
                                                    <th style="width:30%">Ghi chú</th>
                                                    <th style="width:200px">Người tiếp nhận</th>
                                                    <th style="width:200px">Nhân viên sửa chữa chính</th>
                                                    <th style="width:200px">Số KM</th>
                                                    <th style="width:200px">Tên cửa hàng</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($historyData as $key => $itemHistory)
                                                    <tr wire:key="{{ $itemHistory->id }}" class="history-item"
                                                        wire:click.prevent="searchHistory({{ $itemHistory->id }}, [{{ $itemHistory->order ? $itemHistory->order->id : '' }}])">
                                                        <td>
                                                            {{ $itemHistory->history_type == 1 ? 'SCTT' : 'KTĐK' }}
                                                        </td>
                                                        <td>
                                                            {{ $itemHistory->history_type == 1 ? date('d-m-Y', strtotime($itemHistory->in_factory_date)) : date('d-m-Y', strtotime($itemHistory->check_date)) }}
                                                        </td>
                                                        <td>{{ $itemHistory->periodic_level }}</td>
                                                        <td>{{ $itemHistory->order ? $itemHistory->order->total : '' }}
                                                        </td>
                                                        <td>{{ $itemHistory->content_request }}</td>
                                                        <td>{{ $itemHistory->result_repair }}</td>
                                                        <td>{{ $itemHistory->serviceUser ? $itemHistory->serviceUser->name : '' }}</td>
                                                        <td>{{ $itemHistory->user ? $itemHistory->user->name : '' }}</td>
                                                        <td>{{ $itemHistory->km ? $itemHistory->km : '' }}</td>
                                                        <td>{{ $HEAD_NAME }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="6" class="text-center text-danger"> Chưa có dữ liệu
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>


                                        <div class="history-detail-wrap">
                                            <div class="history-detail-work">
                                                <h3>Danh sách công việc sửa chữa</h3>
                                                <table class="table table-striped table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Nội dung công việc</th>
                                                            <th style="width:120px">Tiền công</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if (!empty($historyTasks))
                                                            @foreach ($historyTasks as $hTask)
                                                                <tr class="history-work-item">
                                                                    <td>{{ $hTask->workContent ? $hTask->workContent->name : '' }}
                                                                    </td>
                                                                    <td>{{ $hTask->price }}</td>
                                                                </tr>
                                                            @endforeach
                                                        @else
                                                            <tr>
                                                                <td colspan="2" class="text-center text-danger"> Chưa có
                                                                    dữ liệu
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="history-detail-accessories">
                                                <h3>Danh sách phụ tùng thay thế</h3>
                                                <table class="table table-striped table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Mã vạch</th>
                                                            <th>Tên phụ tùng</th>
                                                            <th>SL</th>
                                                            <th>Đơn giá</th>
                                                            <th>Khuyến mãi</th>
                                                            <th>Thành tiền</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if (!empty($historyTasks))
                                                            @foreach ($historyAccessories as $hAccessory)
                                                                <tr class="history-work-item">
                                                                    <td>{{ $hAccessory->accessorie_code }}</td>
                                                                    <td>{{ $hAccessory->accessorie_name }}</td>
                                                                    <td>{{ $hAccessory->quantity }}</td>
                                                                    <td>{{ $hAccessory->price }}</td>
                                                                    <td>{{ $hAccessory->promotion }}</td>
                                                                    <td>{{ $hAccessory->quantity * $hAccessory->price - (($hAccessory->quantity * $hAccessory->price) / 100) * $hAccessory->promotion }}
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        @else
                                                            <tr>
                                                                <td colspan="6" class="text-center text-danger"> Chưa
                                                                    có
                                                                    dữ liệu
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Đóng</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="row mt-5">
                    <div class="col-6">
                        <h3 class="pb-3 h2-sec-ttl">Thông tin xe</h3>
                        <div class="form-group row">
                            <label for="chassic_no" class="col-2 col-form-label ">Số khung <span
                                    class="text-danger"> *</span></label>
                            <div class="col-10">
                                <input type="text" {{ !$addNew ? 'disabled' : '' }} name="chassic_no"
                                    class="form-control size13" wire:model.lazy="chassic_no" id='chassic_no'
                                    autocomplete="off">
                                @error('chassic_no')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="engine_no" class="col-2 col-form-label ">Số máy <span class="text-danger">
                                    *</span></label>
                            <div class="col-10">
                                <input type="text" {{ !$addNew ? 'disabled' : '' }} name="engine_no"
                                    class="form-control size13" wire:model.lazy="engine_no" id='engine_no'
                                    autocomplete="off">
                                @error('engine_no')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        @if (!$addNew)
                            <div class="form-group row">
                                <label for="customer_name" disabled class="col-2 col-form-label ">Họ tên KH</label>
                                <div class="col-10">
                                    <input type="text" name="customer_name" class="form-control size13" disabled
                                        wire:model.lazy="customer_name" id='customer_name' autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label {{ $showStatus ? 'disabled' : '' }} class="col-2 col-form-label ">Ngày
                                    mua</label>
                                <div class="col-10">
                                    <input type="date" class="form-control input-date-kendo"
                                        max='{{ date('Y-m-d') }}' disabled wire:model.lazy="sell_date">
                                </div>
                            </div>
                        @endif

                        @if ($addNew)
                            <div class="form-group row">
                                <label for="model_code" class="col-2 col-form-label ">Đời xe</label>
                                <div class="col-4">
                                    <input type="text" name="model_code" class="form-control size13"
                                        wire:model.lazy="model_code" id='model_code' autocomplete="off"
                                        placeholder="Tên đời xe">
                                </div>
                                <label for="model_type" class="col-2 col-form-label ">Phân loại</label>
                                <div class="col-4">
                                    <input type="text" name="model_type" class="form-control size13"
                                        wire:model.lazy="model_type" id='model_type' autocomplete="off"
                                        placeholder="Phân loại đời xe">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="model_list" class="col-2 col-form-label ">Danh mục</label>
                                <div class="col-4">
                                    <input type="text" name="model_list" class="form-control size13"
                                        wire:model.lazy="model_list" id='model_list' autocomplete="off"
                                        placeholder="Danh mục đời xe">
                                </div>
                                <label for="color" class="col-2 col-form-label ">Màu xe</label>
                                <div class="col-4">
                                    <input type="text" name="color" class="form-control size13" wire:model.lazy="color"
                                        id='color' autocomplete="off" placeholder="Màu xe">
                                </div>
                            </div>
                        @endif
                        <div class="form-group row">
                            <label for="motor_numbers" class="col-2 col-form-label ">Biển số</label>
                            <div class="col-10">
                                <input type="text" {{ $showStatus ? 'disabled' : '' }} name="motor_numbers"
                                    class="form-control size13" wire:model.lazy="motor_numbers" id='motor_numbers'
                                    autocomplete="off">
                                @error('motor_numbers')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <h3 class="pb-3 h2-sec-ttl">Thông tin KTĐK</h3>
                        <div class="form-group row">
                            <label for="km_no" class="col-2 col-form-label ">Số KM</label>
                            <div class="col-10">
                                <input type="number" {{ $showStatus ? 'disabled' : '' }} name="km_no"
                                    class="form-control size13" wire:model.lazy="km_no" id='km_no' autocomplete="off"
                                    onkeypress="return onlyNumberKey(event)">
                                @error('km_no')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="check_no" class="col-2 col-form-label ">Lần KT</label>
                            <div class="col-10">
                                <input type="text" {{ $showStatus ? 'disabled' : '' }} name="check_no"
                                    class="form-control size13" wire:model.lazy="check_no" id='check_no'
                                    autocomplete="off" onkeypress="return onlyNumberKey(event)">
                                @error('check_no')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="check_date" class="col-2 col-form-label ">Ngày KT</label>
                            <div class="col-10">
                                <input type="date" {{ $showStatus ? 'disabled' : '' }} id="check_date"
                                    class="form-control" wire:model.lazy="check_date">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="next_check_no" class="col-2 col-form-label ">Số km sau</label>
                            <div class="col-10">
                                <p class="pt-1 mt-3">
                                    {{ $next_km_check_no }}
                                </p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="next_check_date" class="col-2 col-form-label ">Ngày dự kiến sau</label>
                            <div class="col-10">
                                <p class="pt-1 mt-3 ml-3">
                                    {{ $next_check_date }}
                                </p>
                            </div>
                        </div>
                        <div class="form-group row {{ $check_no && $motorbike_id ? '' : 'd-none' }}">
                            <button wire:click.prevent="printMainListCheckNo" type="button" class="btn btn-primary">In
                                phiếu KTĐK {{ $check_no }}</button>
                        </div>
                    </div>
                </div>

                <div class="row pt-5">
                    <div class="col-6">
                        <h3 class="pb-3 h2-sec-ttl">Thông tin khách hàng</h3>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group row">
                            <label for="customer_name" class="col-2 col-form-label ">Họ tên KH</label>
                            <div class="col-10">
                                <input name="customer_name" type="text" class="form-control"
                                    {{ $showStatus ? 'disabled' : '' }} wire:model.lazy="customer_name">
                                @error('customer_name')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="customer_phone" class="col-2 col-form-label ">Điện thoại</label>
                            <div class="col-10">
                                <input name="customer_phone" type="text" class="form-control"
                                    {{ $showStatus ? 'disabled' : '' }} onkeypress="return onlyNumberKey(event)"
                                    wire:change="onChangeCustomerPhone"
                                    wire:model.lazy="customer_phone">
                                @error('customer_phone')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="customer_age" class="col-2 col-form-label ">Tuổi</label>
                            <div class="col-10">
                                <input name="customer_age" maxlength="3" type="number" class="form-control"
                                    {{ $showStatus ? 'disabled' : '' }} onkeypress="return onlyNumberKey(event)"
                                    wire:model.lazy="customer_age">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="customer_sex" class="col-2 col-form-label ">Giới tính</label>
                            <div class="col-10">
                                <select name="customer_sex" type="text" {{ $showStatus ? 'disabled' : '' }}
                                    class="form-control" wire:model.lazy="customer_sex">
                                    <option value="">--Giới tính--</option>
                                    <option value="1" {{ $sex = 1 ? 'selected' : '' }}>Nam</option>
                                    <option value="2" {{ $sex = 2 ? 'selected' : '' }}>Nữ</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="sell_date" class="col-2 col-form-label ">Ngày mua xe</label>
                            <div class="col-10">
                                <input type="date" id="sell_date" class="form-control"
                                    max='{{ date('Y-m-d') }}' {{ !$addNew ? 'disabled' : '' }}
                                    wire:model.lazy="sell_date">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-2 col-form-label ">Tích điểm</label>
                            <label class="col-10 col-form-label text-info">{{ $customerPoint ?? 0 }}<span>
                                    điểm</span></label>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group row">
                            <label for="customer_address" class="col-2 col-form-label ">Địa chỉ</label>
                            <div class="col-10">
                                <input name="customer_address" type="text" class="form-control"
                                    {{ $showStatus ? 'disabled' : '' }} wire:model.lazy="customer_address">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="province_id" class="col-2 col-form-label ">Tỉnh/TP</label>
                            <div class="col-10">
                                <select id="province_id" name="province_id" {{ $showStatus ? 'disabled' : '' }}
                                    class="form-control select2-box" wire:model.lazy="province_id">
                                    <option value="">--Chọn thành phố--</option>
                                    @foreach ($provinces as $province)
                                        <option value="{{ $province->province_code }}">
                                            {{ $province->short_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="district_id" class="col-2 col-form-label ">Quận/Huyện</label>
                            <div class="col-10">
                                <select id="district_id" name="district_id" {{ $showStatus ? 'disabled' : '' }}
                                    class="form-control select2-box" wire:model.lazy="district_id">
                                    <option value="">--Chọn Quận/ Huyện--</option>
                                    @foreach ($districts as $key => $district)
                                        <option value="{{ $key }}">
                                            {{ $district }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="customer_job" class="col-2 col-form-label ">Nghề nghiệp</label>
                            <div class="col-10">
                                <input name="customer_job" type="text" {{ $showStatus ? 'disabled' : '' }}
                                    class="form-control" wire:model.lazy="customer_job">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-2 col-form-label ">Biển số</label>
                            <div class="col-10">
                                <input type="text" disabled name="motor_numbers" class="form-control size13"
                                    wire:model.lazy="motor_numbers" id='motor_numbers' autocomplete="off">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive pt-5">
                    <div class="table-title">
                        <div class="row">
                            <div class="col-sm-8">
                                <h2 class="h2-sec-ttl">Danh sách công việc sửa chữa </h2>
                            </div>
                            @php
                                $positions = auth()->user()->positions;
                            @endphp
                            @if (!$showStatus)

                                <div class="col-sm-4 text-right">
                                    <button type="button" wire:click.prevent="addTask({{ $i }})"
                                        class="btn btn-primary" {{ ($positions != 18 && $positions != null) ? 'disabled' : '' }}><i class="fa fa-plus"></i> Thêm mới</button>
                                </div>
                            @endif
                        </div>
                    </div>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">Nội dung công việc</th>
                                <th scope="col">Nhân viên sửa chữa</th>
                                <th scope="col">Chi tiền</th>
                                <th scope="col">Đơn vị gia công</th>
                                <th scope="col">Tiền công(VND)</th>
                                <th scope="col">Khuyến mãi(%)</th>
                                <th scope="col">Thành tiền(VND)</th>

                                @if (!$showStatus)
                                    <th style="width:50px"></th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @if (!empty($tasks))
                                @foreach ($tasks as $key => $task)
                                    <tr>
                                        <td>
                                            <select id="task_content_{{ $task }}"
                                                {{ $showStatus ? 'disabled' : '' }}
                                                onchange="changeWorkContentId({{ $task }}, event)"
                                                wire:model="task_content.{{ $task }}"
                                                class="custom-select select2-box form-control">
                                                <option value="">--Chọn--</option>
                                                @foreach ($listContent as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error("task_content.$task")
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </td>
                                        <td>
                                            <select id="task_service_user_fix_id_{{ $task }}"
                                                {{ $showStatus ? 'disabled' : '' }}
                                                onchange="changeUserFixId({{ $task }}, event)"
                                                wire:model="task_service_user_fix_id.{{ $task }}"
                                                class="custom-select select2-box form-control">
                                                <option value="">--Chọn--</option>
                                                @foreach ($listFixer as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error("task_service_user_fix_id.$task")
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </td>
                                        <td>
                                            @if (!empty($task_out_service))
                                                @if ($task_out_service[$task])
                                                    <input type="number" class="form-control task-input" min="0"
                                                        {{ $showStatus ? 'disabled' : '' }}
                                                        onchange="changeTaskValue({{ $task }})"
                                                        onkeypress="return onlyNumberKey(event)"
                                                        wire:model="task_payment.{{ $task }}">
                                                    @error("task_payment.$task")
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                @else
                                                    <div></div>
                                                @endif

                                            @endif
                                        </td>
                                        <td>
                                            @if (!empty($task_out_service))
                                                @if ($task_out_service[$task])
                                                    <input type="text" class="form-control task-input"
                                                        {{ $showStatus ? 'disabled' : '' }}
                                                        wire:model="task_process_company.{{ $task }}">
                                                    @error("task_process_company.$task")
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                @else
                                                    <div></div>
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            <input type="number" class="form-control task-input" min="0"
                                                {{ $showStatus ? 'disabled' : '' }}
                                                onchange="changeTaskValue({{ $task }})"
                                                onkeypress="return onlyNumberKey(event)"
                                                wire:model="task_price.{{ $task }}">
                                            @error("task_price.$task")
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </td>
                                        <td>
                                            <input type="number" class="form-control task-input" min="0"
                                                {{ $showStatus ? 'disabled' : '' }}
                                                onchange="changeTaskValue({{ $task }})"
                                                onkeypress="return onlyNumberKey(event)"
                                                wire:model="task_promotion.{{ $task }}">
                                        </td>
                                        <td>
                                            <input type="text" readonly class="form-control"
                                                wire:model="task_total.{{ $task }}">
                                        </td>
                                        @if (!$showStatus)
                                            <td class="align-middle text-center">
                                                <a class="delete"
                                                    wire:click.prevent="removeTask({{ $key }})"
                                                    data-toggle="tooltip" data-original-title="Xóa">
                                                    <i class="fa fa-remove"></i></a>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" class="text-center text-danger">Chưa có dữ liệu</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <div class="pt-5">
                    <div class="table-title">
                        <div class="row">
                            <div class="col-sm-8">
                                <h2 class="h2-sec-ttl">Danh sách phụ tùng thay thế</h2>
                            </div>
                            @if (!$showStatus)
                                <div class="col-sm-4 text-right">
                                    <button type="button" wire:click.prevent="addAccessory({{ $j }})"
                                         class="btn btn-primary add-new" {{ ($positions != 18 && $positions != null) ? 'disabled' : '' }}><i class="fa fa-plus"></i> Thêm mới</button>
                                </div>
                            @endif
                        </div>
                    </div>
                    <table class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th style="width:170px">Mã PT</th>
                            <th style="width:200px">Vị trí kho</th>
                            <th style="width:200px">Tên PT</th>
                            {{-- <th style="width:170px">Mã NCC</th> --}}
                            <th style="width:150px">SL</th>
                            <th style="width:150px">Đơn giá</th>
                            <th style="width:150px">Khuyến mãi(%)</th>
                            <th style="width:150px">Thành tiền</th>
                            {{-- <th style="width:150px">Giá in HĐ</th>
                            <th style="width:150px">Giá thực tế</th> --}}
                            @if (!$showStatus)
                                <th style="width:50px"></th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
                        @if (!empty($accessories))
                            @foreach ($accessories as $key => $accessory)
                                <tr>
                                    <td>
                                        @if ($showStatus)
                                            <input type="text" readonly class="form-control"
                                                   wire:model="accessory_code.{{ $accessory }}">
                                        @endif
                                        @if (!$showStatus)
                                            <select class="form-control select2-box accessory_code"
                                                    {{ $showStatus ? 'disabled' : '' }}
                                                    onchange="changeAccessoryCode({{ $accessory }}, event)"
                                                    id="accessory_code_{{ $accessory }}"
                                                    wire:model="accessory_code.{{ $accessory }}">
                                                <option hidden value="">Chọn Mã phụ tùng</option>
                                                @foreach ($accessories_list[$accessory] as $value)
                                                    <option value="{{ $value }}">
                                                        {{ $value }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error("accessory_code.$accessory")
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        @endif
                                    </td>
                                    <td>
                                        @if ($showStatus)
                                            <select class="form-control accessory_warehouse_pos" disabled
                                                    id="accessory_warehouse_pos_{{ $accessory }}"
                                                    wire:model="accessory_warehouse_pos.{{ $accessory }}">
                                                <option hidden value="">Chọn Vị trí kho</option>
                                                @foreach ($positions_list[$accessory] as $value)
                                                    <option value="{{ $value['id'] }}">
                                                        {{ $value['name'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            {{-- <input type="text" readonly class="form-control"
                                            wire:model="accessory_warehouse_pos.{{ $accessory }}"> --}}
                                        @endif
                                        @if (!$showStatus)
                                            <select class="form-control select2-box accessory_warehouse_pos"
                                                    {{ $showStatus ? 'disabled' : '' }}
                                                    onchange="changeWarehousePos({{ $accessory }}, event)"
                                                    id="accessory_warehouse_pos_{{ $accessory }}"
                                                    wire:model="accessory_warehouse_pos.{{ $accessory }}">
                                                <option hidden value="">Chọn Vị trí kho</option>
                                                @foreach ($positions_list[$accessory] as $value)
                                                    <option value="{{ $value['id'] }}">
                                                        {{ $value['name'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error("accessory_warehouse_pos.$accessory")
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        @endif
                                    </td>
                                    <td>
                                        <input type="text" readonly class="form-control"
                                               wire:model="accessory_name.{{ $accessory }}">
                                    </td>
                                    {{-- <td>
                                        <input type="text" readonly class="form-control"
                                            wire:model="accessory_supplier.{{ $accessory }}">
                                    </td> --}}
                                    <td>
                                        <input type="number" class="form-control"
                                               {{ $showStatus ? 'disabled' : '' }}
                                               onkeypress="return onlyNumberKey(event)"
                                               onchange="changeAccessoryValue({{ $accessory }})"
                                               wire:model="accessory_quantity.{{ $accessory }}">
                                        @if (!$showStatus && !empty($accessory_available_quantity_root[$accessory]))
                                            @if ($accessory_available_quantity[$accessory] >= 0)
                                                <p class="text-info">Còn lại :
                                                    {{ $accessory_available_quantity[$accessory] }}</p>
                                            @else
                                                <p class="text-info">Không đủ bán</p>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        <input type="number" class="form-control"
                                               onkeypress="return onlyNumberKey(event)"
                                               {{ $showStatus ? 'disabled' : '' }}
                                               onchange="changeAccessoryValue({{ $accessory }})"
                                               wire:model="accessory_price.{{ $accessory }}">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control"
                                               onkeypress="return onlyNumberKey(event)"
                                               {{ $showStatus ? 'disabled' : '' }}
                                               onchange="changeAccessoryValue({{ $accessory }})"
                                               wire:model="accessory_promotion.{{ $accessory }}">
                                    </td>
                                    <td>
                                        <input type="text" readonly class="form-control"
                                               wire:model="accessory_total.{{ $accessory }}">
                                    </td>
                                    {{-- <td>
                                        <input type="number" class="form-control"
                                            onkeypress="return onlyNumberKey(event)"
                                            {{ $showStatus ? 'disabled' : '' }}
                                            wire:model="accessory_price_vat.{{ $accessory }}">
                                    </td> --}}
                                    {{-- <td>
                                        <input type="number" class="form-control"
                                            onkeypress="return onlyNumberKey(event)"
                                            {{ $showStatus ? 'disabled' : '' }}
                                            wire:model="accessory_price_actual.{{ $accessory }}">
                                    </td> --}}
                                    @if (!$showStatus)
                                        <td class="align-middle text-center">
                                            <a class="delete"
                                               wire:click.prevent="removeAccessory({{ $key }})"
                                               data-toggle="tooltip" data-original-title="Xóa">
                                                <i class="fa fa-remove"></i></a>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="8" class="text-center text-danger">Chưa có dữ liệu</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>

                <div class="form-group row mt-5">
                    <div class="col-12 text-center">
                        <label>
                            <input name="isvirtual" type="checkbox" id="isvirtual" wire:model.lazy="isvirtual">
                            <span class="info-add01">Đơn hàng ảo</span>
                        </label>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-12 text-center">
                        @if (!$showStatus && $saveFlag)
                            @if ($editStatus)
                                <button wire:click.prevent="update" type="button"
                                    {{ $showStatus ? 'disabled' : '' }} class="btn btn-primary">Lưu thông
                                    tin</button>
                            @else
                                <button wire:click.prevent="store" type="button" {{ $showStatus ? 'disabled' : '' }}
                                    class="btn btn-primary">Lưu thông tin</button>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('#province_id').on('change', function(e) {
                var data = $('#province_id').select2("val");
                @this.set('province_id', data);
            });
            $('#district_id').on('change', function(e) {
                var data = $('#district_id').select2("val");
                @this.set('district_id', data);
            });
            $(document).on('keypress', '.search-input', function(e) {
                if (e.which == 13) {
                    window.livewire.emit('search');
                }
            });
            $('#service_user_check_id').on('change', function(e) {
                var data = $('#service_user_check_id').select2("val");
                @this.set('service_user_check_id', data);
            });
            $('#service_user_id').on('change', function(e) {
                var data = $('#service_user_id').select2("val");
                @this.set('service_user_id', data);
            });
            $('#service_user_fix_id').on('change', function(e) {
                var data = $('#service_user_fix_id').select2("val");
                @this.set('service_user_fix_id', data);
            });
            $('#service_user_export_id').on('change', function(e) {
                var data = $('#service_user_export_id').select2("val");
                @this.set('service_user_export_id', data);
            });

            setDatePickerUI();
        });

        document.addEventListener('setDateForDatePicker', function() {
            setDatePickerUI();
        });

        function setDatePickerUI() {
            $("#check_date").kendoDatePicker({
                max: new Date(),
                format: 'dd/MM/yyyy',
                change: function() {
                    if (this.value() != null) {
                        window.livewire.emit('setCheckDate', {
                            ['check_date']: this.value() ? this.value().toLocaleDateString(
                                'en-US') : null
                        });
                    }
                }
            });

            $("#sell_date").kendoDatePicker({
                max: new Date(),
                format: 'dd/MM/yyyy',
                change: function() {
                    if (this.value() != null) {
                        window.livewire.emit('setSellDate', {
                            ['sell_date']: this.value() ? this.value().toLocaleDateString(
                                'en-US') : null
                        });
                    }
                }
            });
        };

        document.addEventListener("show-confirm", (event) => {
            let type = event.detail.type;
            let message = event.detail.message;
            Swal.fire({
                text: message,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: "Đồng ý",
                cancelButtonText: "Hủy bỏ",
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.set('addNew', true);
                }
            });
            return;
        });

        function changeTaskValue(task) {
            window.livewire.emit('countTaskPrice', task);
        }

        function changeAccessoryValue(index) {
            window.livewire.emit('countAccessoryPrice', index);
        }

        function changeAccessoryCode(index, event) {
            let value = $("#accessory_code_" + index + " option:selected").val();
            window.livewire.emit('changeAccessoryCode', {
                index: index,
                value: value
            });
        }

        function changeWarehousePos(index, event) {
            let value = $("#accessory_warehouse_pos_" + index + " option:selected").val();
            window.livewire.emit('changeWarehousePos', {
                index: index,
                value: value
            });
        }

        function changeUserFixId(index, event) {
            let value = $("#task_service_user_fix_id_" + index + " option:selected").val();
            window.livewire.emit('changeUserFixId', {
                index: index,
                value: value
            });
        }
        function changeWorkContentId(index, event) {
            let value = $("#task_content_" + index + " option:selected").val();
            window.livewire.emit('changeWorkContentId', {
                index: index,
                value: value
            });
        }
        document.addEventListener('confirmAddPrintPdf', function(event) {
            let titleMessage =
                "Tạo phiếu kiểm tra định kỳ thành công.Bạn có muốn in phiếu thu bằng pdf không?";
            Swal.fire({
                title: titleMessage,
                icon: 'success',
                confirmButtonText: 'Đồng ý',
                cancelButtonText: 'Hủy bỏ',
                showCancelButton: true,
                showCloseButton: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.open(
                        event.detail.urlPrintf,
                        '_blank'
                    );
                }
            })
        });
        document.addEventListener('confirmEditPrintPdf', function(event) {
            let titleMessage =
                "Cập nhật kiểm tra định kỳ thành công.Bạn có muốn in phiếu thu bằng pdf không?";
            Swal.fire({
                title: titleMessage,
                icon: 'success',
                confirmButtonText: 'Đồng ý',
                cancelButtonText: 'Hủy bỏ',
                showCancelButton: true,
                showCloseButton: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.open(
                        event.detail.urlPrintf,
                        '_blank'
                    );
                }
            })
        });

        document.addEventListener('printMainListCheckNo', function(event) {
            window.open(
                event.detail.urlPrintf,
                '_blank'
            );
        });
    </script>

    <style>
        .notice-get-motobike {
            color: #ff0000;
            padding: 10px;
            border: 1px solid;
            margin-bottom: 15px;
            font-style: italic;
            text-align: center;
        }

        .k-picker-wrap.k-state-disabled {
            background-color: #e9ecef;
            opacity: 1;
        }

        .col-form-label {
            white-space: nowrap;
        }

        #checkServiceTable table,
        td,
        th {
            border: 1px solid black;
            height: 30px;
            text-align: center;
        }

    </style>
</div>
