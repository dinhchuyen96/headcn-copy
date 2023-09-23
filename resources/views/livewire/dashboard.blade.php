<div class="row customer-row">
    <div class=" ibox ibox_top bg-success color-white widget-stat">
        <div class="ibox-body mr-5">
            <h5 class="m-b-5 font-strong">Xe nhập / Kế hoạch giao từ HVN <br> (Từ đầu tháng)</h5>
            <div class="m-b-5">
                <h5>{{ $arrayBox[0] }}</h5>
            </div>
        </div>
        <i class="fa fa-motorcycle widget-stat-icon"></i>
    </div>
    <div class=" ibox ibox_top bg-info color-white widget-stat">
        <div class="ibox-body">
            <h5 class="m-b-5 font-strong">Xe bán / Doanh Thu <br> (trong ngày)</h5>
            <div class="m-b-5">
                <h5>{{ $arrayBox[1] }}</h5>
            </div>
        </div>
        <i class="fa fa-money widget-stat-icon"></i>
    </div>

    <div class="  ibox ibox_top bg-warning color-white widget-stat">
        <div class="ibox-body">
            <h5 class="m-b-5 font-strong">Phụ tùng nhập <br> (trong ngày)</h5>
            <div class="m-b-5">
                <h5>{{ $arrayBox[2] }}</h5>
            </div>
        </div>
        <i class="fa fa-wrench widget-stat-icon"></i>
    </div>

    <div class=" ibox ibox_top bg-danger color-white widget-stat">
        <div class="ibox-body">
            <h5 class="m-b-5 font-strong">Phụ tùng bán / Doanh Thu <br>(trong ngày)</h5>
            <div class="m-b-5">
                <h5>{{ $arrayBox[3] }}</h5>
            </div>
        </div>
        <i class="ti-bar-chart widget-stat-icon"></i>
    </div>
    <div class=" ibox ibox_top bg-blue color-white widget-stat">
        <div class="ibox-body">
            <h5 class="m-b-5 font-strong">Số lượng KTDK <br>(từ đầu tháng)</h5>
            <div class="m-b-5">
                <div class="row">
                    @foreach ($arrayBox[4] as $key => $item)
                        <div class="col-6">
                            <h5>{{ $key . ': ' . $item }}</h5>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <i class="fa fa-cogs widget-stat-icon"></i>

    </div>
</div>
<div class="row">
    <div class="col-lg-12">

        <div class="ibox">

            <ul class="nav nav-tabs nav-tabs-custom" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab"
                    href="#xeban" role="tab" aria-controls="home" aria-selected="true">Xe bán trong ngày</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab"
                     aria-controls="profile" aria-selected="false">Kế hoạch HVN giao xe về HEAD hôm nay</a>
                </li>
            </ul>

            <div class="tab-content" id="myTabContent">

                <!---xe ban-->
                <div class="tab-pane fade show active" id="xeban" role="tabpanel" aria-labelledby="home-tab">
                    <div class="ibox-body">
                        <table class="table table-striped table-bordered dataTable no-footer">
                            <thead>
                                <tr>
                                    <th>Mã đơn hàng</th>
                                    <th>Tên khách hàng</th>
                                    <th>Số khung</th>
                                    <th>Số máy</th>
                                    <th>Model</th>
                                    <th>Color</th>

                                </tr>
                            </thead>
                            <tbody>
                                @forelse($order_detail as $item)
                                    <tr>
                                        <td>
                                            <a href="#">{{ $item->order_id }}</a>
                                        </td>
                                        <td>{{ $item->order->customer->name }}</td>
                                        <td>{{ $item->chassic_no }}</td>
                                        <td>
                                            {{ $item->engine_no }}
                                        </td>
                                        <td>{{ $item->model_code }}</td>
                                        <td>{{ $item->color }}</td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class=" text-center"><span class="text-danger">Chưa có đơn hàng
                                                nào</span></td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        @if (count($order_detail) > 0)
                            {{ $order_detail->links() }}
                        @endif
                    </div>

                </div>

                <!--- receive plan  --->
                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    <div class="form-group row mt-1">
                        <div class="col-9">
                        </div>
                        <div class="col-3 px-5">
                            <input type="date" class="form-control input-date-kendo-edit" id="receivedate"
                            max='{{ date('Y-m-d') }}' wire:model="receivedate">
                        </div>
                    </div>
                    <div class="ibox-body">
                        <table class="table table-striped table-bordered dataTable no-footer">
                            <thead>
                                <tr>
                                    <th>Lot No#</th>
                                    <th>Số khung</th>
                                    <th>Số máy</th>
                                    <th>Model</th>
                                    <th>Color</th>
                                    <th>Nhập kho</th>
                                    <th>Nhập HMS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($hms_receive_plan as $key=>$item)
                                    <tr>
                                        <td>
                                            <a href="#">{{ $item->hvn_lot_number }}</a>
                                        </td>
                                        <td>{{ $item->chassic_no }}</td>
                                        <td>
                                            {{ $item->engine_no }}
                                        </td>
                                        <td>{{ $item->model_code }}</td>
                                        <td>{{ $item->color }}</td>
                                        <td>{{
                                                isset($item->buy_date) && !is_null($item->buy_date) && !empty($item->buy_date) ? 'Đã nhập kho' : ''
                                            }}
                                        </td>
                                        <td>{{
                                              isset($item->actual_arrival_date_time) && !is_null($item->actual_arrival_date_time) && !empty($item->actual_arrival_date_time) ? 'Đã nhập HMS' : 'Chưa nhập HMS'
                                             }}
                                        </td>
                                    </tr>
                                @empty

                                @endforelse
                            </tbody>
                        </table>
                        @if (count($hms_receive_plan) > 0)
                            {{ $hms_receive_plan->links() }}
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .visitors-table tbody tr td:last-child {
        display: flex;
        align-items: center;
    }

    @media only screen and (max-width: 1280px) {
        .ibox_top {
            display: flex;
            align-items: center;
            margin: 20px 0 20px 15px;
        }

    }

    .ibox_top {
        flex: 0 0 18%;
        max-width: 20%;
        margin: 20px 0 20px 15px;
    }


    .visitors-table .progress {
        flex: 1;
    }

    .visitors-table .progress-parcent {
        text-align: right;
        margin-left: 10px;
    }
</style>
<script>
        document.addEventListener('livewire:load', function() {
            // Your JS here.
            var fromDate = new Date(); //new Date(date.getFullYear(), date.getMonth(), 1);
            $('#receivedate').kendoDatePicker({
                format: "dd/MM/yyyy",
                value: new Date(),
                change: function() {
                    var data = $('#receivedate').val();
                    @this.set('receivedate', data);
                    window.livewire.emit('changereceiveDate', data);
                }
            });

        })

</script>
