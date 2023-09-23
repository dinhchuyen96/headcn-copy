<div>
    <div wire:loading class="loader"></div>

    <div class="page-content fade-in-up">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Thông tin phiếu sửa chữa</div>
            </div>
            <div class="ibox-body">
                <div class="notice-get-motobike font-weight-bold" style="color: #ff0000; font-style: normal">
                    <i class="fa fa-info-circle" aria-hidden="true"></i> Hãy tìm kiếm thông tin xe trước, nếu không
                    có trong hệ thống sẽ cho phép tạo mới xe và
                    thông tin khách hàng
                </div>
                <div class="form-group row">
                    <label for="chassicNoSearch" class="col-2 col-form-label ">Số khung</label>
                    <div class="col-4">
                        <input id="chassicNoSearch" name="chassicNoSearch" type="text" class="form-control"
                            wire:model.lazy="chassicNoSearch">
                    </div>
                    <label for="engineNoSearch" class="col-2 col-form-label ">Số máy</label>
                    <div class="col-4">
                        <input id="engineNoSearch" name="engineNoSearch" type="text" class="form-control"
                            wire:model.lazy="engineNoSearch">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="customerPhoneSearch" class="col-2 col-form-label">Số điện thoại KH </label>
                    <div class="col-4">
                        <input id="customerPhoneSearch" name="customerPhoneSearch"
                            onkeypress="return onlyNumberKey(event)" type="number" class="form-control"
                            wire:model.lazy="customerPhoneSearch">
                    </div>
                    <label for="numberMotorSearch" class="col-2 col-form-label ">Biển số </label>
                    <div class="col-4">
                        <input id="numberMotorSearch" name="numberMotorSearch" type="text" class="form-control"
                            wire:model.lazy="numberMotorSearch">
                    </div>
                </div>
                <br>
                <div class="row justify-content-center">
                    <button type="button" class="btn btn-primary" wire:click="search()"><i class="fa fa-search"></i>
                        Tìm kiếm</button>
                </div>
                <br><br>

                <div>
                    <div class="form-group row">
                        <label for="serviceRequest" class="col-2 col-form-label ">Triệu chứng/Yêu cầu KT
                            <span class="text-danger">*</span></label>
                        <div class="col-4">
                            <textarea class="form-control" id="serviceRequest" name="serviceRequest" rows="4"
                                wire:model.lazy="serviceRequest"></textarea>
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
                                <input type="checkbox" class="form-check-input" id="beforeRepair"
                                    wire:model="beforeRepair">
                                <label class="form-check-label" for="beforeRepair">Trước sửa chữa</label>
                            </div>
                            <div class="form-group form-check">
                                <input type="checkbox" class="form-check-input" id="afterRepair"
                                    wire:model="afterRepair">
                                <label class="form-check-label" for="afterRepair">Sau sửa chữa</label>
                            </div>

                            <div class="form-group form-check">
                                <input type="checkbox" class="form-check-input" id="notNeedWash"
                                    wire:model="notNeedWash">
                                <label class="form-check-label" for="notNeedWash">Không cần rửa xe</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="ServiceRequestCode" class="col-2 col-form-label ">Mã SR
                            <span class="text-danger">*</span>
                        </label>
                        <div class="col-4">
                            <input id="serviceRequestCode" name="serviceRequestCode" type="text" class="form-control"
                                wire:model.lazy="serviceRequestCode">
                            @error('serviceRequestCode')
                                @include('layouts.partials.text._error')
                            @enderror
                        </div>
                        <label for="ServiceType" class="col-2 col-form-label ">Loại sửa chữa
                            {{-- <span class="text-danger">*</span> --}}
                        </label>
                        <div class="col-4">
                            <select id="serviceType" name="serviceType" class="custom-select select2-box form-control"
                                wire:model.lazy="serviceType">
                                <option value="">--Chọn--</option>
                                @foreach ($serviceTypeList as $serviceType)
                                    <option value="{{ $serviceType->id }}">{{ $serviceType->name }}</option>
                                @endforeach
                            </select>
                            @error('serviceType')
                                @include('layouts.partials.text._error')
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="serviceUserCheckId" class="col-2 col-form-label ">Người kiểm tra</label>
                        <div class="col-4">
                            <select id="serviceUserCheckId" name="serviceUserCheckId"
                                wire:model.lazy="serviceUserCheckId" class="custom-select select2-box form-control">
                                <option value="">--Chọn--</option>
                                @foreach ($inspectionStaffs as $inspectionStaff)
                                    <option value="{{ $inspectionStaff->id }}">{{ $inspectionStaff->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('serviceUserCheckId')
                                @include('layouts.partials.text._error')
                            @enderror
                        </div>
                        <label for="serviceUserId" class="col-2 col-form-label ">Người tiếp nhận<span class="text-danger">*</span></label>
                        <div class="col-4">
                            <select id="serviceUserId" name="serviceUserId" wire:model.lazy="serviceUserId"
                                class="custom-select select2-box form-control">
                                <option value="">--Chọn--</option>
                                @foreach ($technicalStaffs as $technicalStaff)
                                    <option value="{{ $technicalStaff->id }}">{{ $technicalStaff->name }}</option>
                                @endforeach
                            </select>
                            @error('serviceUserId')
                                @include('layouts.partials.text._error')
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="numberMotor" class="col-2 col-form-label ">Biển số </label>
                        <div class="col-4">
                            <input id="numberMotor" name="numberMotor" type="text" class="form-control"
                                wire:model.lazy="numberMotor">

                        </div>
                        <label for="km" class="col-2 col-form-label ">Số KM</label>
                        <div class="col-4">
                            <input id="km" name="km" type="number" class="form-control" wire:model.lazy="km"
                                onkeypress="return onlyNumberKey(event)">
                            @error('km')
                                @include('layouts.partials.text._error')
                            @enderror
                        </div>

                

                    </div>
                    <div class="form-group row">
                        <label for="serviceFixerId" class="col-2 col-form-label ">Nhân viên sửa chữa chính</label>
                        <div class="col-4">
                            <select id="serviceFixerId" name="serviceFixerId" wire:model.lazy="serviceFixerId"
                                class="custom-select select2-box form-control">
                                <option value="">--Chọn--</option>
                                @foreach ($listFixer as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('serviceFixerId')
                                @include('layouts.partials.text._error')
                            @enderror
                        </div>
                        <label for="exportWarehouseId" class="col-2 col-form-label ">Nhân viên xuất kho</label>
                        <div class="col-4">
                            <select id="exportWarehouseId" name="exportWarehouseId" wire:model.lazy="exportWarehouseId"
                                class="custom-select select2-box form-control">
                                <option value="">--Chọn--</option>
                                @foreach ($listExporter as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('exportWarehouseId')
                                @include('layouts.partials.text._error')
                            @enderror
                        </div>
                    </div>

                    


                    <div>
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
                                    @for ($i = 1; $i <= 5; $i++)
                                        <td><input type="checkbox" wire:model="checkService"
                                                value="{{ $i }}" /></td>
                                    @endfor
                                    <td>Lốp trước</td>
                                    @for ($i = 6; $i <= 10; $i++)
                                        <td><input type="checkbox" wire:model="checkService"
                                                value="{{ $i }}" /></td>
                                    @endfor
                                    <td>Dây phanh</td>
                                    @for ($i = 11; $i <= 15; $i++)
                                        <td><input type="checkbox" wire:model="checkService"
                                                value="{{ $i }}" /></td>
                                    @endfor
                                    <td>Côn</td>
                                    @for ($i = 16; $i <= 20; $i++)
                                        <td><input type="checkbox" wire:model="checkService"
                                                value="{{ $i }}" /></td>
                                    @endfor
                                </tr>
                                <tr style="text-align: left;">
                                    <td>Phanh trước</td>
                                    @for ($i = 21; $i <= 25; $i++)
                                        <td><input type="checkbox" wire:model="checkService"
                                                value="{{ $i }}" /></td>
                                    @endfor
                                    <td>Lốp sau</td>
                                    @for ($i = 26; $i <= 30; $i++)
                                        <td><input type="checkbox" wire:model="checkService"
                                                value="{{ $i }}" /></td>
                                    @endfor
                                    <td>Dầu số</td>
                                    @for ($i = 31; $i <= 35; $i++)
                                        <td><input type="checkbox" wire:model="checkService"
                                                value="{{ $i }}" /></td>
                                    @endfor
                                    <td>Chổi than</td>
                                    @for ($i = 36; $i <= 40; $i++)
                                        <td><input type="checkbox" wire:model="checkService"
                                                value="{{ $i }}" /></td>
                                    @endfor
                                </tr>
                                <tr style="text-align: left;">
                                    <td>Phanh sau</td>
                                    @for ($i = 41; $i <= 45; $i++)
                                        <td><input type="checkbox" wire:model="checkService"
                                                value="{{ $i }}" /></td>
                                    @endfor
                                    <td>Dầu máy</td>
                                    @for ($i = 46; $i <= 50; $i++)
                                        <td><input type="checkbox" wire:model="checkService"
                                                value="{{ $i }}" /></td>
                                    @endfor
                                    <td>Dây đai</td>
                                    @for ($i = 51; $i <= 55; $i++)
                                        <td><input type="checkbox" wire:model="checkService"
                                                value="{{ $i }}" /></td>
                                    @endfor
                                    <td>Họng ga</td>
                                    @for ($i = 56; $i <= 60; $i++)
                                        <td><input type="checkbox" wire:model="checkService"
                                                value="{{ $i }}" /></td>
                                    @endfor
                                </tr>
                                <tr style="text-align: left;">
                                    <td>Bóng đèn</td>
                                    @for ($i = 61; $i <= 65; $i++)
                                        <td><input type="checkbox" wire:model="checkService"
                                                value="{{ $i }}" /></td>
                                    @endfor
                                    <td>Làm mát</td>
                                    @for ($i = 66; $i <= 70; $i++)
                                        <td><input type="checkbox" wire:model="checkService"
                                                value="{{ $i }}" /></td>
                                    @endfor
                                    <td>Ắc quy</td>
                                    @for ($i = 71; $i <= 75; $i++)
                                        <td><input type="checkbox" wire:model="checkService"
                                                value="{{ $i }}" /></td>
                                    @endfor
                                    <td>Bugi</td>
                                    @for ($i = 76; $i <= 80; $i++)
                                        <td><input type="checkbox" wire:model="checkService"
                                                value="{{ $i }}" /></td>
                                    @endfor
                                </tr>
                                <tr style="text-align: left;">
                                    <td>Công tắc</td>
                                    @for ($i = 81; $i <= 85; $i++)
                                        <td><input type="checkbox" wire:model="checkService"
                                                value="{{ $i }}" /></td>
                                    @endfor
                                    <td>Xích</td>
                                    @for ($i = 86; $i <= 90; $i++)
                                        <td><input type="checkbox" wire:model="checkService"
                                                value="{{ $i }}" /></td>
                                    @endfor
                                    <td>Lọc gió</td>
                                    @for ($i = 91; $i <= 95; $i++)
                                        <td><input type="checkbox" wire:model="checkService"
                                                value="{{ $i }}" /></td>
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
                                    @for ($i = 96; $i <= 100; $i++)
                                        <td><input type="checkbox" wire:model="checkService"
                                                value="{{ $i }}" /></td>
                                    @endfor
                                    <td>Công tơ mét</td>
                                    @for ($i = 101; $i <= 105; $i++)
                                        <td><input type="checkbox" wire:model="checkService"
                                                value="{{ $i }}" /></td>
                                    @endfor
                                    <td>Nhông xích</td>
                                    @for ($i = 106; $i <= 110; $i++)
                                        <td><input type="checkbox" wire:model="checkService"
                                                value="{{ $i }}" /></td>
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
                            <textarea class="form-control" id="resultRepair" name="resultRepair" rows="4"
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
                                                            wire:click.prevent="searchHistory({{ $itemHistory->id }}, {{ $itemHistory->order ? $itemHistory->order->id : '' }})">
                                                            <td>
                                                                {{ $itemHistory->history_type == 1 ? 'SCTT' : 'KTĐK' }}
                                                            </td>
                                                            <td>
                                                                {{ $itemHistory->history_type == 1 ? date('d-m-Y', strtotime($itemHistory->in_factory_date)) : date('d-m-Y', strtotime($itemHistory->check_date)) }}
                                                            </td>
                                                            <td>{{ $itemHistory->order ? $itemHistory->order->total : '' }}
                                                            </td>
                                                            <td>{{ $itemHistory->content_request }}</td>
                                                            <td>{{ $itemHistory->result_repair }}</td>
                                                            <td>{{ $itemHistory->serviceUser ? $itemHistory->serviceUser->name : '' }}</td>
                                                            <td>{{ $itemHistory->user ? $itemHistory->user->name : '' }}</td>
                                                            <td>{{ $itemHistory->km ? $itemHistory->km : '' }}</td>
                                                            <td>{{ env('APP_HEADNAME') }}</td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="5" class="text-center text-danger"> Chưa có dữ
                                                                liệu
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
                                                                    <td colspan="2" class="text-center text-danger">
                                                                        Chưa có
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
                                                                    <td colspan="6" class="text-center text-danger">
                                                                        Chưa
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
                        <div class="col-4 font-weight-bold border bg-light p-3">Thông tin xe</div>
                        <div class="col-8 font-weight-bold border bg-light p-3">Thông tin khách hàng</div>
                    </div>
                    <div class="row mt-3">
                        <label for="chassicNo" class="col-1 col-form-label " style="padding-right:0px ">Số khung<span class="text-danger">*</span></label>
                        <div class="col-3">
                            <input id="chassicNo" name="chassicNo" type="text" class="form-control"
                                {{ $isCreate ? '' : 'readonly' }} wire:model.lazy="chassicNo">
                            @error('chassicNo')
                                @include('layouts.partials.text._error')
                            @enderror
                        </div>
                        <label for="customerName" class="col-1 col-form-label ">Họ tên <span class="text-danger">*</span></label>
                        <div class="col-3">
                            <input id="customerName" name="customerName" type="text" class="form-control"
                                wire:model.lazy="customerName">
                            @error('customerName')
                                @include('layouts.partials.text._error')
                            @enderror
                        </div>
                        <label for="customerAddress" class="col-1 col-form-label ">Địa chỉ </label>
                        <div class="col-3">
                            <input id="customerAddress" name="customerAddress" type="text" class="form-control"
                                wire:model.lazy="customerAddress">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <label for="engineNo" class="col-1 col-form-label ">Số máy<span class="text-danger">*</span></label>
                        <div class="col-3">
                            <input id="engineNo" name="engineNo" type="text" class="form-control"
                                {{ $isCreate ? '' : 'readonly' }} wire:model.lazy="engineNo">
                            @error('engineNo')
                                @include('layouts.partials.text._error')
                            @enderror
                        </div>
                        <label for="customerPhone" class="col-1 col-form-label ">SĐT <span class="text-danger">*</span></label>
                        <div class="col-3">
                            <input id="customerPhone" name="customerPhone" type="text" class="form-control"
                                onkeypress="return onlyNumberKey(event)" wire:model.lazy="customerPhone">
                            @error('customerPhone')
                                @include('layouts.partials.text._error')
                            @enderror
                        </div>
                        <label for="customerCity" class="col-1 col-form-label ">Thành phố/ Tỉnh </label>
                        <div class="col-3">
                            <select name="customerCity" wire:model.lazy="customerCity" id="customerCity"
                                class="custom-select form-control select2-box">
                                <option value="">--Chọn--</option>
                                @foreach ($provinces as $province)
                                    <option value="{{ $province->province_code }}">{{ $province->name }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                    <div class="row mt-3">
                        <label for="modelName" class="col-1 col-form-label ">Loại xe</label>
                        <div class="col-3">
                            <input type="text" id="modelName" class="form-control"
                                {{ ($isCreate || $modelName == '') ? '' : 'disabled' }} wire:model.lazy="modelName">
                            @error('modelName')
                                @include('layouts.partials.text._error')
                            @enderror
                        </div>
                        <label for="customerSex" class="col-1 col-form-label ">Giới tính</label>
                        <div class="col-3">
                            <select id="customerSex" name="customerSex" type="text" class="form-control select2-box"
                                wire:model.lazy="customerSex">
                                <option value="">--Chọn--</option>
                                @foreach ($sexList as $item)
                                    <option value="{{ $item['id'] }}">{{ $item['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <label for="customerDistrict" class="col-1 col-form-label ">Quận/Huyện</label>
                        <div class="col-3">
                            <select name="customerDistrict" wire:model.lazy="customerDistrict" id="customerDistrict"
                                class="custom-select form-control select2-box">
                                <option value="">--Chọn--</option>
                                @foreach ($districts as $district)
                                    <option value="{{ $district->district_code }}">{{ $district->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <label for="buyDate" class="col-1 col-form-label ">Ngày mua</label>
                        <div class="col-3">
                            <input type="date" id="buyDate" class="form-control" max='{{ date('Y-m-d') }}'
                                {{ $isCreate ? '' : 'disabled' }} wire:model.lazy="buyDate">
                            @error('buyDate')
                                @include('layouts.partials.text._error')
                            @enderror
                        </div>
                        <label class="col-1 col-form-label ">Tích điểm</label>
                        <label class="col-4 col-form-label"><span class="text-info">{{ $customerPoint ?? 0 }}
                                điểm</span></label>
                    </div>
                    @livewire('component.input-repair',['isAdd'=>true])
                    @livewire('component.input-accessories',['isAdd'=>true])
                    <div class="form-group row pt-3">
                        <div class="col-12 text-center">
                            <button name="button" {{ $isDisableAccesory || $isDisableTask ? 'disabled' : '' }}
                                wire:click.prevent="store" type="submit" class="btn btn-primary">Tạo
                                phiếu</button>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#customerDistrict').on('change', function(e) {
            var data = $('#customerDistrict').select2("val");
            @this.set('customerDistrict', data);
        });
        $('#customerCity').on('change', function(e) {
            var data = $('#customerCity').select2("val");
            @this.set('customerCity', data);
        });
        $('#serviceUserId').on('change', function(e) {
            var data = $('#serviceUserId').select2("val");
            @this.set('serviceUserId', data);
        });
        $('#serviceUserCheckId').on('change', function(e) {
            var data = $('#serviceUserCheckId').select2("val");
            @this.set('serviceUserCheckId', data);
            Livewire.emit('idFixer', data)
        });
        $('#serviceFixerId').on('change', function(e) {
            var data = $('#serviceFixerId').select2("val");
            @this.set('serviceFixerId', data);
            if (data != null) {
                window.livewire.emit('setServiceFixerId', data);
            }
        });
        $('#exportWarehouseId').on('change', function(e) {
            var data = $('#exportWarehouseId').select2("val");
            @this.set('exportWarehouseId', data);
        });

        $('#serviceType').on('change', function(e) {
            var data = $('#serviceType').select2("val");
            @this.set('serviceType', data);
        });
        $('#customerSex').on('change', function(e) {
            var data = $('#customerSex').select2("val");
            @this.set('customerSex', data);
        });
        $(document).on('keypress', function(e) {
            if (e.which == 13) {
                Livewire.emit('search')
            }
        });
    });
    document.addEventListener('setDateForDatePicker', function() {
        setDatePickerUI();
    });
    document.addEventListener('livewire:load', function() {
        setDatePickerUI();
    });

    function setDatePickerUI() {
        $("#buyDate").kendoDatePicker({
            format: "dd/MM/yyyy"
        });
        var buyDate = $("#buyDate").data("kendoDatePicker");
        buyDate.max(new Date());
        buyDate.bind("change", function() {
            var value = this.value();
            if (value != null) {
                var datestring = moment(value).format('YYYY-MM-DD');
                @this.set('buyDate', datestring);
            }
        });
    };
    window.addEventListener('showAskCreate', event => {
        Swal.fire({
            title: 'Không tìm thấy thông tin xe. Bạn có muốn tạo mới thông tin xe và khách hàng không?',
            icon: 'question',
            confirmButtonText: 'Đồng ý',
            cancelButtonText: 'Hủy bỏ',
            showCancelButton: true,
            showCloseButton: true
        }).then((result) => {
            if (result.isConfirmed) {
                @this.set('isCreate', true);

            }
        })
    });
    document.addEventListener('confirmPrintPdf', function(event) {
        let titleMessage =
            "Tạo phiếu sửa chữa thông thường thành công.Bạn có muốn in phiếu thu bằng pdf không?";
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
</script>
<style>
    .notice-get-motobike {
        color: #0084ff;
        padding: 10px;
        border: 1px solid;
        margin-bottom: 15px;
        font-style: italic;
        text-align: center;
    }

</style>
