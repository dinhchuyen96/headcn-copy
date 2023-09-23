<div class="head">
    <div class="right">
        <img src="/assets/img/logos/logoPL.png" alt="phulienlogo">
    </div>
    <div class="content">
        <h5 style="text-align: center;">CỘNG HÒA XÃ HỘI CHỦ NGHĨA-VIỆT NAM</h5>
        <h5 style="text-align: center;">Độc lập - Tự do - Hạnh phúc</h5>
        <h3>{{ $headCompany }}</h3>
        <div class="info-head">
            <span>{{ $headName }}</span>
            <br>
            <span>Địa chỉ: {{ $headAddress }}</span>
            <br>
            <span>ĐT: {{ $headPhoneNumber }}</span>
        </div>
    </div>
    <div class="left" style="width: 30%">
    </div>
</div>
<div style="display: flex;justify-content: space-between;">
    <div class="right"></div>
    <div class="left"><span>Hôm nay,ngày {{ $day < 10 ? '0' . $day : $day }} tháng {{ $month < 10 ? '0' . $month : $month }}
            năm {{ $year }}</span></div>
</div>
<div class="body">
    <h3><u>BÊN MUA:</u></h3>
    <span>Ông, Bà: </span><span>{{ $customer->name }}</span>
    <br>
    <span>Địa chỉ: </span><span>{{ $address }}</span>
    <br>
    <span>Điện thoại: </span><span>{{ $customer->phone }} </span>
    <h3><u>BÊN BÁN:</u>{{ $headCompany }} Bán cho bên mua một chiếc xe máy </h3>
    <table>
        <tr>
            <td> <span>Loại xe:</span><span> {{ $motorbike->model_code }}</span></td>
            <td> <span>Màu:</span><span>{{ $motorbike->color }}</span></td>
        </tr>
        <tr>
            <td>
                <span>Số khung:</span><span>{{ $motorbike->chassic_no }}</span>
            </td>
            <td>
                <span>Số máy:</span><span>{{ $motorbike->engine_no }}</span>
            </td>
        </tr>
        <tr>
            <td><span>Bán xe theo thỏa thuận</span></td>
            <td>
                <input type="checkbox" /> <span>ĐK+BH</span>
                <input type="checkbox" /> <span>ĐK</span>
                <input type="checkbox" /> <span>TGT</span>
            </td>
        </tr>
        <tr>
            <td><span>Còn nợ: {{ number_format($motorbike->price) }}đ
                    {{ '(' . docsothanhchu($motorbike->price) . ')' }}</span></td>
        </tr>
    </table>
    <span><strong>Thời gian lấy BS+DK 15-20 NGÀY KHÔNG KỂ T7 + CN</strong></span>
    <ul>
        <li><strong>Thời gian trả Biển và đăng ký:</strong></li>
        <li><strong>Thời gian trả Biển và đăng ký:</strong></li>
        <li><strong>Buổi chiều: từ 13h30 đến 17h30</strong></li>
        <li><strong>khi đến lấy Biển và đăng ký ai đi lấy mang theo giấy bán xe này.</strong></li>
    </ul>
    <span><strong>Bên mua cam kết thực hiện các điều khoản sau:</strong></span>
    <br>
    <span>1. Hẹn đúng đến ngày....tháng.......năm.......(dương lịch) sẽ trả hết số nợ trên</span>
    <br>
    <span>2. Nếu đến đúng ngày hẹn mà bên mua không trả đủ tiền, thì phải đem xe đến trả lại cho bên bán. Bên bán có
        toàn quyền định đoạt chiếc xe trên và bên mua phải chịu mọi phí tổn do bên bán ấn định.</span>
    <br>
    <span>
        3. Sau khi mua 30 ngày (theo quy định của pháp luật) bên mua phải đến thanh toán và làm thủ tục đăng ký xe.
        Nếu quá 30 ngày bên bán hoàn toàn không chịu trách nhiệm.
    </span>
    <br>
    <span>
        *Bên mua cam kết thực hiện đúng những điều khoản trên. Nếu sai bên mua hoàn toàn chịu trách nhiệm trước pháp
        luật và không có bất cứ khiếu nại gì.
    </span>
    <br>
    <span>
        Sau khi nhận giấy tờ 15 ngày nếu Quý khách không đi đăng ký
        Cửa hàng không chịu trách nhiệm.
        Nếu sai hẹn sẽ tính 2.000đ/1.000.000đ/1 ngày.
    </span>
</div>
<div class="footer-content">
    <div class="lfotter">
        <span>BÊN MUA</span>
    </div>
    <div class="rfooter">
        <span>BÊN BÁN</span>
    </div>

</div>
<script>
    window.addEventListener('DOMContentLoaded', (event) => {
        window.print();
    });
</script>
