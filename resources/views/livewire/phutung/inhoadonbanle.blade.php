<style>
    table,
    td,
    th {
        border: 1px solid;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }
</style>
<div class="container">
    <div style="display: flex; align-items: center">
        <div style="flex: 25%"></div>
        <div style="flex: 50%; text-align: center">
            @if($data["type"] == 1)
            <h1>PHIẾU BÁN BUÔN PHỤ TÙNG</h1>
            @else
            <h1>PHIẾU BÁN LẺ PHỤ TÙNG</h1>
            @endif
        </div>
        <div style="flex: 25%"></div>
    </div>
    <p style="text-align: left">Họ tên khách hàng: {{ $data["name"] }}</p>
    <p style="text-align: left">Địa chỉ: {{ $data["address"] }}</p>
    <p style="text-align: left">
        Số tiền: <b>{{ number_format($data["totalPrice"]) }} VND</b>
    </p>
    <table style="text-align: center">
        <tr>
            <th>STT</th>
            <th>Mã phụ tùng</th>
            <th>Tên phụ tùng</th>
            <th>Kho xuất</th>
            <th>Vị trí kho</th>
            <th>Đơn Giá</th>
            <th>Số Lượng</th>
            <th>Tổng Tiền</th>
        </tr>

        @for ($i = 0; $i < $details["length"]; $i++)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $details["accessoryNumber"][$i] }}</td>
            <td>{{ $details["accessoryName"][$i] }}</td>
            <td>{{ $details["warehouseName"][$i] }}</td>
            <td>{{ $details["wareHousePositionName"][$i] }}</td>
            <td>{{ $details["price"][$i] }}</td>
            <td>{{ $details["qty"][$i] }}</td>
            <td>{{ $details["price"][$i] * $details["qty"][$i] }}</td>
        </tr>
        @endfor
    </table>
    <div class="clearfix">
        <div style="float: left; padding: 0 50px">
            <p><strong>Giám đốc</strong></p>
        </div>
        <div style="float: left; padding: 0 50px">
            <p><strong>Kế toán trưởng</strong></p>
        </div>
        <div style="float: left; padding: 0 50px">
            <p><strong>Người thu</strong></p>
        </div>
        <div style="float: left; padding: 0 50px">
            <p><strong>Người lập</strong></p>
        </div>
        <div style="float: left; padding: 0px 50px">
            <p><strong>Thủ quỹ</strong></p>
        </div>
    </div>
    <div class="clearfix">
        <div style="float: left; padding: 0 30px 0 20px">
            <p><small>(Ký,họ tên,đóng dấu)</small></p>
        </div>
        <div style="float: left; padding: 0 20px 0px 60px">
            <p><small>(Ký,họ tên)</small></p>
        </div>
        <div style="float: left; padding: 0 40px 0 120px">
            <p><small>(Ký,họ tên)</small></p>
        </div>
        <div style="float: left; padding: 0 80px">
            <p><small>(Ký,họ tên)</small></p>
        </div>
        <div style="float: left; padding: 0 30px">
            <p><small>(Ký,họ tên)</small></p>
        </div>
    </div>
</div>
<script>
    window.addEventListener("DOMContentLoaded", (event) => {
        window.print();
    });
</script>
