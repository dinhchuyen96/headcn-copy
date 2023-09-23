<div class="page-content fade-in-up">
    <div class="ibox">
        <div class="ibox-head">
            <div class="ibox-title">Thông tin đơn hàng</div>
        </div>
        <div class="ibox-body">
            <form>
                <div class="form-group row">
                    <label for="customer-name" class="col-1 col-form-label ">Tên khách hàng</label>
                    <div class="col-5">
                        {!! Form::text(null, $data->customer->name, array('placeholder' => 'Tên khách hàng','class' => 'form-control', 'disabled')) !!}

                    </div>
                    <label for="customer-addr" class="col-1 col-form-label ">Địa chỉ</label>
                    <div class="col-5">
                        {!! Form::text(null, $data->customer->address, array('placeholder' => 'Địa chỉ khách hàng','class' => 'form-control', 'disabled')) !!}
                    </div>
                </div>
                <div class="form-group row">
                    <label for="customer-phone" class="col-1 col-form-label ">SĐT</label>
                    <div class="col-5">
                        {!! Form::number(null, $data->customer->phone, array('placeholder' => 'Số điện thoại khách hàng','class' => 'form-control', 'disabled')) !!}

                    </div>
                    <label for="customer-email" class="col-1 col-form-label ">Email</label>
                    <div class="col-5">
                        {!! Form::text(null, $data->customer->email, array('placeholder' => 'Email khách hàng','class' => 'form-control', 'disabled')) !!}
                    </div>
                </div>
                <div class="form-group row">
                    <label for="order-no" class="col-1 col-form-label ">Mã đơn hàng</label>
                    <div class="col-5">
                        {!! Form::text('order_no', null, array('placeholder' => 'Mã đơn hàng','class' => 'form-control', 'disabled' => checkRoute('show'))) !!}
                        @error('order_no')
                            @include('layouts.partials.text._error')
                        @enderror
                    </div>
                    <label for="order-type" class="col-1 col-form-label ">Loại</label>
                    <div class="col-5">
                        {!! Form::select('type', array('1' => 'Bán buôn', '2' => 'Bán lẻ'), null, ['class' => 'form-control', 'disabled' => checkRoute('show')]) !!}
                        @error('type')
                            @include('layouts.partials.text._error')
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label for="total-items" class="col-1 col-form-label ">Số lượng</label>
                    <div class="col-5">
                        {!! Form::text('total_items', null, array('placeholder' => 'Số lượng','class' => 'form-control', 'disabled' => checkRoute('show'))) !!}
                        @error('total_items')
                            @include('layouts.partials.text._error')
                        @enderror
                    </div>
                    <label for="tax" class="col-1 col-form-label ">Thuế</label>
                    <div class="col-5">
                        {!! Form::text('tax', null, array('placeholder' => 'Thuế','class' => 'form-control', 'disabled' => checkRoute('show'))) !!}
                        @error('tax')
                            @include('layouts.partials.text._error')
                        @enderror

                    </div>
                </div>

                <div class="form-group row">
                    <label for="discount" class="col-1 col-form-label ">Discount</label>
                    <div class="col-5">
                        {!! Form::text('discount', null, array('placeholder' => 'Discount','class' => 'form-control', 'disabled' => checkRoute('show'))) !!}
                        @error('discount')
                            @include('layouts.partials.text._error')
                        @enderror
                    </div>
                    <label for="order-total" class="col-1 col-form-label">Tổng tiền</label>
                    <div class="col-5">
                        {!! Form::text('total_money', null, array('placeholder' => 'Tổng tiền','class' => 'form-control', 'disabled' => checkRoute('show'))) !!}
                        @error('total_money')
                            @include('layouts.partials.text._error')
                        @enderror
                    </div>
                </div>
                <div class="form-group row">

                    <label for="order-status" class="col-1 col-form-label ">Trạng thái</label>
                    <div class="col-5">
                    {!! Form::select('status', array('1' => 'Đã thanh toán', '2' => 'Chưa thanh toán', '3' => 'Chờ xử lý', '4' => 'Đã hủy', '5' => 'Chờ xử lý hủy'), null, ['class' => 'form-control', 'disabled' => checkRoute('show')]) !!}
                    </div>
                    <label for="date_payment" class="col-1 col-form-label ">Hạn thanh toán</label>
                    <div class="col-5">
                    {!! Form::dateTime('date_payment', null, array('placeholder' => 'Hạn thanh toán','class' => 'form-control', 'disabled' => checkRoute('show'))) !!}
                        @error('date_payment')
                            @include('layouts.partials.text._error')
                        @enderror
                    </div>
                </div>

                <div class="form-group row justify-content-center btn-group-mt">
                        @include('layouts.partials.button._back')
                    <div class="col-1">
                        @if(checkRoute('edit'))
                            @include('layouts.partials.button._save')
                        @endif
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
