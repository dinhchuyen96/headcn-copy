<div>
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-body">
                    <div class="flexbox mb-4">
                        <div>
                            <h3 class="m-0 h3-sec-ttl">Bán hàng</h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="ibox bg-success color-white widget-stat">
                                <a href="{{ route('tienich.canhbaochitiet.khongbaocaodungthoigian.index') }}">
                                    <div class="ibox-body mr-5">
                                        <h2 class="m-b-5 font-strong" style="color:white">{{ $count_hmsRP }} XE</h2>
                                        <div class="m-b-5" style="color:white; font-weight: bold; font-size: 16px;"> BÁO CÁO XE VỀ ĐẾN HEAD TRÊN HMS
                                            CHẬM</div><i class="ti-shopping-cart widget-stat-icon"
                                            style="color:white"></i>
                                        <div style="padding-right: 45px;color:white"><small style="font-size: 16px !important;">(Ngày giao hàng - Ngày nộp
                                                tiền < 3)</small>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class=" col-md-4">
                            <div class="ibox bg-info color-white widget-stat">
                                <a href="{{ route('tienich.canhbaochitiet.noptienmuon.index') }}">
                                    <div class="ibox-body">
                                        <h2 class="m-b-5 font-strong" style="color:white">{{ $countLatePayment }}</h2>
                                        <div class="m-b-5" style="color:white; font-weight: bold; font-size: 16px;">LÔ HÀNG NỘP TIỀN MUỘN </div><i
                                            class="ti-bar-chart widget-stat-icon" style="color:white"></i>
                                        <div style="padding-right: 45px;color:white"><small style="font-size: 16px !important;">Ngày giao hàng trên phiếu
                                                giao hàng số 4 /Dữ
                                                liệu ngày về trên HMS do HEAD nhập</small></div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-body">
                    <div class="flexbox mb-4">
                        <div>
                            <h3 class="m-0 h3-sec-ttl">Dịch vụ</h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3 col-xl-4">
                            <div class="ibox bg-success color-white widget-stat">
                                <a href="{{ route('tienich.canhbaochitiet.khieunaibaohanh.index') }}">
                                    <div class="ibox-body">
                                        <h2 class="m-b-5 font-strong" style="color:white">{{ $countWarrantyClaim }}
                                        </h2>
                                        <div class="m-b-5" style="color:white; font-weight: bold; font-size: 16px;">KHIẾU NẠI BẢO HÀNH</div><i
                                            class="ti-shopping-cart widget-stat-icon" style="color:white"></i>
                                        <div style="padding-right: 45px;color:white"><small style="font-size: 16px !important;">Khiếu nại bảo hành cần
                                                xử lý trong vòng
                                                5 ngày kể từ ngày phát sinh</small>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <div class="col-lg-3 col-xl-4">
                            <div class="ibox bg-danger color-white widget-stat">
                                <a href="{{ route('tienich.canhbaochitiet.chapthuanbaohanh.index') }}">
                                    <div class="ibox-body">
                                        <h2 class="m-b-5 font-strong" style="color:white">{{ $applyInsurance }}%</h2>
                                        <div class="m-b-5" style="color:white; font-weight: bold; font-size: 16px;">TỶ LỆ CHẤP THUẬN BẢO HÀNH
                                        </div><i style="color:white" class="ti-user widget-stat-icon"></i>
                                        <div style="padding-right: 45px;color:white"><small style="font-size: 16px !important;">Số lượng chấp thuận khiếu
                                                nại bảo hành /Tổng
                                                số khiếu nại bảo hành</small></div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-body">
                    <div class="flexbox mb-4">
                        <div>
                            <h3 class="m-0 h3-sec-ttl">Phụ tùng</h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-6">
                            <div class="ibox bg-info color-white widget-stat">
                                <div class="ibox-body">
                                    <h2 class="m-b-5 font-strong">{{ $averageRate }}</h2>
                                    <div class="m-b-5" style="color:white; font-weight: bold; font-size: 16px;">TỶ LỆ DOANH THU PHỤ TÙNG & DẦU</div><i
                                        class="ti-bar-chart widget-stat-icon"></i>
                                    <div style="padding-right: 45px"><small style="font-size: 16px !important;">HEAD có tỉ lệ doanh thu trên đầu xe cao hơn
                                            tỉ
                                            lệ trung bình của chính HEAD trong tháng</small></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-xl-4">
                            <div class="ibox bg-warning color-white widget-stat">
                                <a href="{{ route('tienich.canhbaochitiet.canhbaourgent.index') }}">
                                    <div class="ibox-body">
                                        <h2 class="m-b-5 font-strong" style="color:white">{{ $countUrgents }}</h2>
                                        <div class="m-b-5" style="color:white; font-weight: bold; font-size: 16px;">SỐ LƯỢNG ĐƠN HÀNG BH KHẨN
                                        </div><i style="color:white" class="fa fa-money widget-stat-icon"></i>
                                        <div style="padding-right: 45px;color:white"><small style="font-size: 16px !important;">Đơn hàng đặt phụ tùng khẩn</small></div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-body">
                    <div class="flexbox mb-4">
                        <div>
                            <h3 class="m-0 h3-sec-ttl">Kế toán</h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-6">
                            <div class="ibox bg-success color-white widget-stat">

                                <div class="ibox-body">
                                    <h2 class="m-b-5 font-strong" style="color:white">Tổng bán hàng </h2>
                                    <div class="m-b-5" style="color:white; font-weight: bold; font-size: 16px;">{{ $totalQuantityMotor }} xe +
                                        {{ $totalQuantityAccessory }} phụ tùng + {{ $totalQuantityRepair }} sửa chữa
                                    </div><i class="ti-shopping-cart widget-stat-icon" style="color:white"></i>
                                    <div style="padding-right: 45px;color:white"><small style="font-size: 16px !important;">Doanh thu:
                                            {{ number_format($totalSale) }} VNĐ</small>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="ibox bg-info color-white widget-stat">
                                <a href="{{ route('tienich.canhbaochitiet.khachhangnoquahan.index') }}">
                                    <div class="ibox-body">
                                        <h2 class="m-b-5 font-strong" style="color:white">{{ $totalOverDueCustomer }}
                                            Khách hàng</h2>
                                        <div class="m-b-5" style="color:white; font-weight: bold; font-size: 16px;">CẢNH BÁO NỢ QUÁ HẠN</div><i
                                            style="color:white" class="ti-bar-chart widget-stat-icon"></i>
                                        <div style="padding-right: 45px;color:white"><small style="font-size: 16px !important;">Khách hàng nợ quá hạn 7
                                                ngày</small></div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
