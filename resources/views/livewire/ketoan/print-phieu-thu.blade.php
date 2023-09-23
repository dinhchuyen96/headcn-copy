<div class="container">
    <div class="clearfix">
        <div style="float: left;text-align: center;">
            <p>{{ env('HEAD_COMPANY') }}</p>
            <p>{{ env('HEAD_ADDRESS') }}</p>
        </div>
        <div style="float: right;text-align: center;">
            <p style="font-weight: bold;">Mẫu số 01-TT</p>
            <p><em>( Ban hành theo QĐ số 15/2006/QĐ-BTC</em></p>
            <p><em>ngày 20/03/2006 của Bộ trưởng BTC )</em></p>
        </div>
    </div>

    <div style="display: flex; align-items:center">
        <div style=" flex: 25%;"></div>
        <div style=" flex: 50%;; text-align:center">
            <h1>PHIẾU THU</h1>
        </div>
        <div style=" flex: 25%;">
            <p>Quyển số: .......................</p>
        </div>

    </div>
    <div style="display: flex">
        <div style=" flex: 25%;"></div>
        <div style=" flex: 50%; text-align:center">
            <p><b>Ngày {{ $data['day'] }} tháng {{ $data['month'] }} năm {{ $data['year'] }} </b></p>
        </div>
        <div style=" flex: 25%;">
            <p>Số: {{ 'PTK' . sprintf("%'.09d\n", $data['number']) }}</p>
            <p>Nợ: {{ $data['account_code'] }}</p>
            <p>Có: {{ $data['have'] }}</p>
        </div>
    </div>
    <p style="text-align:left">Họ tên người nộp tiền:
        {{ $data['name'] }}</p>
    <p style="text-align:left">Địa chỉ: {{ $data['address'] }}</p>
    <p style="text-align:left">Lý do nộp: {{ $data['reason'] }}</p>
    <p style="text-align:left">Số tiền: <b>{{ number_format($data['totalPrice']) }} VND</b></p>
    <p style="text-align:left">Viết bằng chữ: <b>{{ docsothanhchu($data['totalPrice']) }}</b></p>
    <p style="text-align:left">Kèm theo:
        ...............................................................................................................................
    </p>
    <div class="clearfix">
        <div style="float: left;padding: 0 50px">
            <p><strong>Giám đốc</strong></p>
        </div>
        <div style="float: left;padding: 0 50px">
            <p><strong>Kế toán trưởng</strong></p>

        </div>
        <div style="float: left;padding: 0 50px">
            <p><strong>Người nộp</strong></p>

        </div>
        <div style="float: left;padding: 0 50px">
            <p><strong>Người lập</strong></p>

        </div>
        <div style="float: left;padding: 0px 50px">
            <p><strong>Thủ quỹ</strong></p>
        </div>
    </div>
    <div class="clearfix">
        <div style="float: left;padding: 0 30px 0 20px">
            <p><small>(Ký,họ tên,đóng dấu)</small></p>
        </div>
        <div style="float: left;padding: 0 20px 0px 60px">
            <p><small>(Ký,họ tên)</small></p>

        </div>
        <div style="float: left;padding: 0 40px 0 120px">
            <p><small>(Ký,họ tên)</small></p>

        </div>
        <div style="float: left;padding: 0 80px">
            <p><small>(Ký,họ tên)</small></p>

        </div>
        <div style="float: left;padding: 0 30px">
            <p><small>(Ký,họ tên)</small></p>
        </div>
    </div>
    <p style="margin-top: 50px">Đã nhận đủ số tiền (Viết bằng chữ) :
        {{ docsothanhchu($data['totalPrice']) }}
    </p>
</div>
<script>
    window.addEventListener('DOMContentLoaded', (event) => {
        window.print();
    });
</script>
