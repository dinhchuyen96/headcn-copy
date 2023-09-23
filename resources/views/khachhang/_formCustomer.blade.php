<div class="page-content fade-in-up">
    <div class="ibox">
        <div class="ibox-head">
            <div class="ibox-title">Thông tin khách hàng</div>
        </div>
        <div class="ibox-body">
            <form>
                <div class="form-group row mt-1">
                    <label for="CustomerName" class="col-1 col-form-label ">Tên khách hàng <span class="text-danger" {{ checkRoute('show')?'hidden':'' }}>*</span></label>
                    <div class="col-3">
                        {!! Form::text('name', null, array('placeholder' => 'Tên khách hàng','class' => 'form-control', 'disabled' => checkRoute('show'))) !!}
                        @error('name')
                            @include('layouts.partials.text._error')
                        @enderror
                    </div>
                    <label for="CustomerCode" class="col-1 col-form-label ">Mã khách hàng <span class="text-danger" {{ checkRoute('show')?'hidden':'' }}>*</span></label>
                    <div class="col-3">
                        {!! Form::text('code', $defaultCustomerCode??null, array('placeholder' => 'Mã khách hàng','class' => 'form-control', 'disabled' => checkRoute('show'))) !!}
                        @error('code')
                            @include('layouts.partials.text._error')
                        @enderror
                    </div>
                    <label for="Sex" class="col-1 col-form-label">Giới tính</label>
                    <div class="col-3">
                        {!! Form::select('sex', array('1' => 'Nam', '2' => 'Nữ'), null, ['class' => 'form-control', 'disabled' => checkRoute('show')]) !!}
                    </div>
                </div>

                <div class="form-group row mt-1">
                    <label for="Birthday" class="col-1 col-form-label ">Ngày sinh <span class="text-danger" {{ checkRoute('show')?'hidden':'' }}>*</span></label>
                    <div class="col-3">
                        {!! Form::date('birthday', null, array('placeholder' => 'Ngày sinh','class' => 'form-control date-picker input-date-kendo', 'id' => "id-date-picker-2", "data-date-format"=>"dd-mm-yyyy", 'disabled' => checkRoute('show'), 'max'=>date('Y-m-d'))) !!}
                        @error('birthday')
                        @include('layouts.partials.text._error')
                    @enderror
                    </div>
                    <label for="PhoneNumber" class="col-1 col-form-label ">Số điện thoại <span class="text-danger" {{ checkRoute('show')?'hidden':'' }}>*</span></label>
                    <div class="col-3">
                        {!! Form::number('phone', null, array('placeholder' => 'Số điện thoại','class' => 'form-control', 'disabled' => checkRoute('show'))) !!}
                        @error('phone')
                            @include('layouts.partials.text._error')
                        @enderror
                    </div>
                    <label for="Email" class="col-1 col-form-label ">Email</label>
                    <div class="col-3">
                        {!! Form::text('email', null, array('placeholder' => 'Email','class' => 'form-control', 'disabled' => checkRoute('show'))) !!}
                        @error('email')
                            @include('layouts.partials.text._error')
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="IdentityCode" class="col-1 col-form-label ">Số CMT/CCCD</label>
                    <div class="col-3">
                        {!! Form::number('identity_code', null, array('placeholder' => 'Số CMT/CCCD','class' => 'form-control', 'disabled' => checkRoute('show'))) !!}
                    </div>
                    <label for="Job" class="col-1 col-form-label ">Nghề nghiệp</label>
                    <div class="col-3">
                        {!! Form::text('job', null, array('placeholder' => 'Nghề nghiệp','class' => 'form-control', 'disabled' => checkRoute('show'))) !!}
                    </div>
                </div>
                @if(checkRoute('create'))
                    @livewire('component.address')
                @else
                    @livewire('component.address', ['status' => checkRoute('show'), 'address' => $data->address, 'province_id' => $data->city, 'district_id'=>$data->district, 'ward_id'=>$data->ward ])
                @endif
                <div class="form-group row justify-content-center btn-group-mt">
                    <div>
                        <a href="{{ route('customers.index') }}" class="btn btn-default">
                            <i class="fa fa-arrow-left"></i>
                            Trở lại
                        </a>
                        @if(checkRoute('create'))
                            <button name="submit" type="submit" class="btn btn-primary">
                                <i class="fa fa-plus"></i> Tạo mới
                            </button>
                        @elseif(checkRoute('edit'))
                            <button name="submit" type="submit" class="btn btn-primary">
                            <i class="fa fa-plus"></i> Lưu
                        </button>
                        @endif
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
