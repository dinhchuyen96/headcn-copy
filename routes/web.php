<?php

use App\Http\Controllers\ServiceListController;
use App\Http\Controllers\SuplierController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => ['user', 'cors']], function () {
    Route::get('/', 'HomeController@index')->name('dashboard');
    Route::get('/profile', 'HomeController@profile')->name('profile');
    Route::get('/support', 'HomeController@support')->name('support');
    Route::get('/edit', 'HomeController@edit')->name('edit');
    Route::post('/update', 'HomeController@update')->name('update');

    // nguoidung
    Route::prefix('nguoiDung')->group(function () {
        Route::get('/taomoi', 'NguoiDungController@create')->name('nguoiDung.create.index');
        Route::post('/luuthongtin', 'NguoiDungController@store')->name('nguoiDung.store');
        Route::get('/{id}/suathongtin', 'NguoiDungController@edit')->name('nguoiDung.edit.index');
        Route::patch('/{id}', 'NguoiDungController@update')->name('nguoiDung.update');
        Route::patch('/capnhatquyen', 'NguoiDungController@updateRole')->name('nguoiDung.update_role');
        Route::get('/', 'NguoiDungController@index')->name('nguoiDung.index');
    });
    Route::group(['prefix' => 'phanquyen'], function () {
        Route::get('/', 'RoleController@index')->name('roles.index');
        Route::get('/suathongtin/{id}', 'RoleController@edit')->name('roles.edit.index');
        Route::get('/taomoi', 'RoleController@create')->name('roles.create.index');
        Route::post('/luu', 'RoleController@store')->name('roles.store');
        Route::get('/xemthongtin/{id}', 'RoleController@show')->name('roles.show');
        Route::post('/capnhat/{id}', 'RoleController@update')->name('roles.update');
    });
    // kế toán
    Route::prefix('ketoan')->group( function () {
        Route::get('/xuatpdf/{id}', 'KetoanController@xuatpdf')->name('ketoan.xuatpdf.index');
        Route::get('/in-phieu-chi/{id}', 'KetoanController@inPhieuChi')->name('ketoan.inphieuchi.index');
        Route::get('/thu', 'KetoanController@thu')->name('ketoan.thu.index');
        Route::get('/chi', 'KetoanController@chi')->name('ketoan.chi.index');
        Route::prefix('baocao')->group(function () {
            Route::get('tong-hop-thu', 'KetoanController@baocaotonghopthu')->name('ketoan.baocao.tonghopthu.index');
            Route::get('chi-tiet-thu', 'KetoanController@baocaochitietthu')->name('ketoan.baocao.chitietthu.index');
            Route::get('tong-hop-chi', 'KetoanController@baocaotonghopchi')->name('ketoan.baocao.tonghopchi.index');
            Route::get('chi-tiet-chi', 'KetoanController@baocaochitietchi')->name('ketoan.baocao.chitietchi.index');
            Route::get('danh-sach-tra-gop', 'KetoanController@danhsachtragop')->name('ketoan.baocao.dstragop.index');
            Route::get('danh-sach-thu', 'KetoanController@danhsachthu')->name('ketoan.baocao.dsthu.index');
            Route::get('danh-sach-chi', 'KetoanController@danhsachchi')->name('ketoan.baocao.dschi.index');
            Route::get('so-quy', 'KetoanController@soquy')->name('ketoan.baocao.soquy.index');
            Route::get('doanh-thu-tai-chinh', 'KetoanController@doanhthutaichinh')->name('ketoan.baocao.doanhthutaichinh.index');
        });
    });


    Route::group(['prefix' => 'xemay'], function () {
        Route::get('/donhang/danhsachdonhang', 'OrderBuyMotorbikeController@index')->name('motorbikes.orders.index');
        Route::get('/donhang/danhsachdonhangprint/{id}', 'OrderBuyMotorbikeController@print')->name('motorbikes.orders.print');
        Route::get('/donhang/sua/{id}', 'OrderBuyMotorbikeController@edit')->name('order-buy-motorbike.edit');
        Route::get('/donhang/xem/{id}', 'OrderBuyMotorbikeController@show')->name('order-buy-motorbike.show');
        Route::post('/donhang/capnhat/{id}', 'OrderBuyMotorbikeController@update')->name('order-buy-motorbike.update');
    });

    Route::get('xemay/ban-buon', 'MotorbikeController@banBuon')->name('motorbikes.ban-buon.index');
    Route::get('xemay/ban-le', 'MotorbikeController@banLe')->name('motorbikes.ban-le.index');
    Route::get('xemay/buy', 'MotorbikeController@buy')->name('motorbikes.buy.index');
    Route::get('xemay/danhsach', 'MotorbikeController@index')->name('motorbikes.list.index');
    Route::get('xemay/dich-vu-khac', '\App\Http\Controllers\Service\OtherServiceController@index')->name('xemay.dichvukhac.index');
    Route::get('xemay/dich-vu-khac/them', '\App\Http\Controllers\Service\OtherServiceController@create')->name('xemay.dichvukhac.create.index');
    Route::get('xemay/dich-vu-khac/xem/{id}', '\App\Http\Controllers\Service\OtherServiceController@show')->name('xemay.dichvukhac.show.index');
    Route::get('xemay/dich-vu-khac/sua/{id}', '\App\Http\Controllers\Service\OtherServiceController@edit')->name('xemay.dichvukhac.edit.index');
    Route::get('xemay/bao-gia', 'MotorbikeController@proposal')->name('motorbikes.bao-gia.index');

    Route::group(['prefix' => 'phutung'], function () {
        Route::get('/ban-buon', 'PhuTungController@index')->name('phutung.banbuon.index');
        Route::get('/ban-le', 'PhuTungController@banle')->name('phutung.banle.index');
        Route::get('/orders', 'OrderBuyAccessoriesController@index')->name('accessories.orders');
        Route::get('/orders/edit/{id}', 'OrderBuyAccessoriesController@edit')->name('order-buy-accessories.edit');
        Route::get('/orders/show/{id}', 'OrderBuyAccessoriesController@show')->name('order-buy-accessories.show');
        Route::post('/orders/update/{id}', 'OrderBuyAccessoriesController@update')->name('order-buy-accessories.update');
        Route::get('/nhaphang', '\App\Http\Controllers\Accessary\OrderBuyController@index')->name('phutung.nhapphutung.index');
        Route::get('/in-hoa-don-phu-tung/{id}', 'PhuTungController@inhoadonbanle')->name('phutung.inhoadonbanle.index');

    });


    Route::get('/phutung/ds-phu-tung-nhap/{id}/xem', '\App\Http\Controllers\Accessary\OrderBuyController@show')->name('accessary.order-buy.show');

    Route::get('phutung/ds-don-hang', '\App\Http\Controllers\Accessary\OrderBuyController@orderBuyList')->name('phutung.dsdonhang.index');
    Route::get('phutung/ds-phu-tung-nhap', '\App\Http\Controllers\Accessary\OrderBuyController@orderBuyAccessary')->name('phutung.dsphutungnhap.index');

    Route::get('phutung/bao-cao', '\App\Http\Controllers\Accessary\OrderBuyController@baoCao')->name('phutung.baocao.index');

    Route::prefix('dichvu')->group(function () {
        Route::get('/bao-duong-dinh-ki', '\App\Http\Controllers\Service\MantainController@index')->name('dichvu.bao-duong-dinh-ki.index');
        Route::get('/ds-xe-ngoai', '\App\Http\Controllers\Service\OutMotorbikeController@index')->name('dichvu.dsxengoai.index');
        Route::get('/ds-don-hang', '\App\Http\Controllers\Service\ListServiceController@index')->name('dichvu.dsdonhang.index');
        Route::get('/in-ds-don-hang/{id}', '\App\Http\Controllers\Service\ListServiceController@print')->name('dichvu.dsdonhang.print');
        Route::get('/in-ktdk', '\App\Http\Controllers\Service\ListServiceController@printCheckNo')->name('dichvu.dsdonhang.printCheckNo');
        Route::get('/sua-chua-thong-thuong', '\App\Http\Controllers\Service\RepairController@index')->name('dichvu.sua-chua-thong-thuong.index');
        Route::get('/sua-chua-thong-thuong/xem/{id}', '\App\Http\Controllers\Service\RepairController@show')->name('dichvu.sua-chua-thong-thuong.xem.index');
        Route::get('/sua-chua-thong-thuong/sua/{id}', '\App\Http\Controllers\Service\RepairController@edit')->name('dichvu.sua-chua-thong-thuong.sua.index');


        Route::get('/ds-phu-tung-cho-thay-the', '\App\Http\Controllers\Service\AtrophyAccessoryController@index')->name('dichvu.ds-phu-tung-cho-thay-the.index');

        Route::get('/bao-cao-theo-cong-viec', '\App\Http\Controllers\Service\RepairController@reportByWork')->name('dichvu.baocaotheocongviec.index');
        Route::get('/bao-cao-theo-tho', '\App\Http\Controllers\Service\RepairController@reportByWorker')->name('dichvu.baocaotheotho.index');
        Route::get('/bao-cao-kiem-tra-dinh-ky', '\App\Http\Controllers\Service\MantainController@report')->name('dichvu.baocao.ktdk.index');
        Route::get('/bao-cao-sua-chua-thong-thuong', '\App\Http\Controllers\Service\RepairController@report')->name('dichvu.baocao.sctt.index');
    });
    Route::prefix('cskh')->group(function () {
        Route::get('/cham-soc-khach-hang', '\App\Http\Controllers\Service\CustomerCareController@index')->name('cskh.cham-soc-khach-hang.index');
        Route::get('/dich-vu-cham-soc-khach-hang', '\App\Http\Controllers\Service\CustomerCareController@serviceSupport')->name('cskh.dich-vu-cham-soc-khach-hang.index');
        Route::get('/lich-su-lien-he-khach-hang/{id}', '\App\Http\Controllers\Service\CustomerCareController@contactHistory')->name('cskh.lich-su-lien-he-khach-hang.index');
        Route::get('/ds-lien-he-khach-hang', '\App\Http\Controllers\Service\CustomerCareController@dslienhekhachhang')->name('cskh.ds-lien-he-khach-hang.index');
    });

    //Route::view('ketoan/bao-cao', 'ketoan.baocao')->name('ketoan.baocao');
    Route::get('tienich/canh-bao', '\App\Http\Controllers\Ultilities\WarningController@index')->name('tienich.canhbao.index');
    Route::get('tienich/canh-bao-chi-tiet/khong-bao-cao-dung-thoi-gian', '\App\Http\Controllers\Ultilities\WarningController@warningDetailWrongTime')->name('tienich.canhbaochitiet.khongbaocaodungthoigian.index');
    Route::get('tien-ich/canh-bao-chi-tiet/canh-bao-nop-tien-muon', '\App\Http\Controllers\Ultilities\WarningController@warningLatePayment')->name('tienich.canhbaochitiet.noptienmuon.index');
    Route::get('tien-ich/canh-bao-chi-tiet/khieu-nai-khai-bao', '\App\Http\Controllers\Ultilities\WarningController@warrantyClaim')->name('tienich.canhbaochitiet.khieunaibaohanh.index');
    Route::get('tien-ich/canh-bao-chi-tiet/canh-bao-urgent', '\App\Http\Controllers\Ultilities\WarningController@warningUrgent')->name('tienich.canhbaochitiet.canhbaourgent.index');
    Route::get('tien-ich/canh-bao-chi-tiet/chap-thuan-bao-hanh', '\App\Http\Controllers\Ultilities\WarningController@applyInsurance')->name('tienich.canhbaochitiet.chapthuanbaohanh.index');
    Route::get('tien-ich/canh-bao-chi-tiet/khach-hang-no-qua-han', '\App\Http\Controllers\Ultilities\WarningController@overdueCustomer')->name('tienich.canhbaochitiet.khachhangnoquahan.index');


    Route::get('/mtoc', '\App\Http\Controllers\MTOCS\MTOCController@index')->name('mtoc.index');
    Route::get('/mtoc/create', '\App\Http\Controllers\MTOCS\MTOCController@create')->name('mtoc.create.index');
    Route::get('/mtoc/edit/{id}', '\App\Http\Controllers\MTOCS\MTOCController@edit')->name('mtoc.edit.index');
    Route::get('/mtoc/show/{id}', '\App\Http\Controllers\MTOCS\MTOCController@show')->name('mtoc.show.index');

    Route::get('/quatang', '\App\Http\Controllers\GiftController@index')->name('quatang.index');
    Route::get('/quatang/create', '\App\Http\Controllers\GiftController@create')->name('quatang.create.index');
    Route::get('/quatang/edit/{id}', '\App\Http\Controllers\GiftController@edit')->name('quatang.edit.index');
    Route::get('/quatang/xem/{id}', '\App\Http\Controllers\GiftController@show')->name('quatang.show.index');
    Route::get('/quatang/caidat', 'GiftController@setting')->name('quatang.setting.index');

    Route::get('khachhang/dskhachhang', 'CustomerController@index')->name('customers.index');
    Route::get('khachhang/themmoi', 'CustomerController@create')->name('customers.create.index');
    Route::get('customer/get-customer-by-phone-or-name', 'CustomerController@getCustomerByPhoneOrName')->name('customers.getCustomerByPhoneOrName.index');
    Route::get('customer/get-customer-by-phone-or-name-with-id', 'CustomerController@getCustomerByPhoneOrNameWithId')->name('customers.getCustomerByPhoneOrNameWithId.index');

    Route::post('khachhang/luu', 'CustomerController@store')->name('customers.store');
    Route::get('khachhang/suathongtin/{id}', 'CustomerController@edit')->name('customers.edit.index');
    Route::get('khachhang/doiquatang/{id}', 'CustomerController@giftChange')->name('customers.gift-change.index');
    Route::get('khachhang/xemchitiet/{id}', 'CustomerController@show')->name('customers.show.index');
    Route::post('khachhang/capnhat/{id}', 'CustomerController@update')->name('customers.update');


    Route::prefix('nhacungcap')->group(function () {
        Route::get('/ds-nhacungcap', 'SupplierController@index')->name('nhacungcap.dsnhacungcap.index');
        Route::get('/themmoi', 'SupplierController@create')->name('nhacungcap.themmoi.index');
        Route::get('/capnhat/{id}', 'SupplierController@edit')->name('nhacungcap.capnhat.index');
        Route::get('/xemthongtin/{id}', 'SupplierController@show')->name('nhacungcap.xemthongtin.index');
    });

    Route::prefix('quanlykho')->group(function () {

        Route::get('/baocaokhoxemay', 'QuanlykhoController@baocaokhoxemay')->name('quanlykho.baocaokhoxemay.index');
        Route::get('/thaydoiphutungxe', 'QuanlykhoController@thaydoiphutungxe')->name('quanlykho.thaydoiphutungxe.index');
        Route::get('/baocaoxetheomodel', 'QuanlykhoController@baocaoxetheomodel')->name('quanlykho.baocaoxetheomodel.index');
        Route::get('/baocaophutungtheorank', 'QuanlykhoController@baocaophutungtheorank')->name('quanlykho.baocaophutungtheorank.index');
        Route::get('/chuyenkhoxemay', 'QuanlykhoController@chuyenkhoxemay')->name('quanlykho.chuyenkhoxemay.index');
        Route::get('/baocaokhophutung', 'QuanlykhoController@baocaokhophutung')->name('quanlykho.baocaokhophutung.index');
        Route::get('/baocaosudungphutung', 'QuanlykhoController@baocaosudungphutung')->name('quanlykho.baocaosudungphutung.index');
        Route::get('/chuyenkhophutung', 'QuanlykhoController@chuyenkhophutung')->name('quanlykho.chuyenkhophutung.index');
        Route::get('/lichsuchuyenkhoxe', 'QuanlykhoController@lichsuchuyenkhoxe')->name('quanlykho.lichsuchuyenkhoxe.index');
        Route::get('/lichsuchuyenphutung', 'QuanlykhoController@lichsuchuyenphutung')->name('quanlykho.lichsuchuyenphutung.index');
        Route::get('/nhapngoaile', 'NhapngoaileController@index')->name('quanlykho.nhapngoaile.index');
        Route::get('/xuatngoaile', 'XuatngoaileController@index')->name('quanlykho.xuatngoaile.index');
        Route::get('/lichsunhapxuatngoaile', 'LichsuNhapxuatNgoaileController@index')->name('quanlykho.lichsunhapxuatngoaile.index');
        Route::get('/quatang', 'QuanlykhoController@quatang')->name('quanlykho.quatang.index');
        Route::get('/quatang/xem/{id}', 'QuanlykhoController@giftShow')->name('quanlykho.quatang.show');
    });

    Route::prefix('kho')->group(function () {
        Route::get('/danhsach', 'WarehouseController@index')->name('kho.danhsach.index');
        Route::get('/dskhoquatang', 'WarehouseController@giftlist')->name('kho.danhsachgift.index');
        Route::get('/themmoi', 'WarehouseController@create')->name('kho.themmoi.index');
        Route::get('/capnhat/{id}', 'WarehouseController@edit')->name('kho.capnhat.index');
        Route::get('/xemthongtin/{id}', 'WarehouseController@show')->name('kho.xemthongtin.index');
    });
    Route::prefix('danhmucmaphutung')->group(function () {
        Route::get('/themmoi', 'DanhmucmaPhuTungController@create')->name('danhmucmaphutung.themmoi.index');
        Route::get('/danhsach', 'DanhmucmaPhuTungController@index')->name('danhmucmaphutung.danhsach.index');
        Route::get('/capnhat/{id}', 'DanhmucmaPhuTungController@edit')->name('danhmucmaphutung.capnhat.index');
    });
    Route::group(['prefix' => 'chinoibo'], function () {
        Route::get('/danh-sach', 'ChinoiboController@index')->name('chinoibo.index');
        Route::get('/them', 'ChinoiboController@create')->name('chinoibo.create');
        Route::get('/xem/{id}', 'ChinoiboController@show')->name('chinoibo.show');
        Route::get('/sua/{id}', 'ChinoiboController@edit')->name('chinoibo.edit');
    });
    Route::group([
        'prefix' => 'api',
    ], function () {
        Route::get('/smsGateway', 'Api\SmsGatewayController@send')->name('api.sendSMS');
    });

    Route::get('/bank', '\App\Http\Controllers\BankController@index')->name('bank.index');
    Route::get('/bank/create', '\App\Http\Controllers\BankController@create')->name('bank.create.index');

    Route::get('/work-content', '\App\Http\Controllers\WorkContentController@index')->name('work-content.index');
    Route::get('/work-content/create', '\App\Http\Controllers\WorkContentController@create')->name('work-content.create.index');
    Route::get('/work-content/edit/{id}', '\App\Http\Controllers\WorkContentController@edit')->name('work-content.edit.index');

    Route::get('/servicelist', '\App\Http\Controllers\ServiceListController@index')->name('servicelist.index');
    Route::get('/servicelist/create', '\App\Http\Controllers\ServiceListController@create')->name('servicelist.create.index');
    Route::get('/servicelist/edit/{id}', '\App\Http\Controllers\ServiceListController@edit')->name('servicelist.edit.index');
    Route::post('/servicelist/editworkstatus/{id}', '\App\Http\Controllers\ServiceListController@updataWorkStatus')->name('servicelist.edit.workstatus');

    Route::prefix('hangtralai')->group(function () {
        Route::get('/ban', 'SellReturnController@index')->name('hangtralai.ban.index');
        Route::get('/ban/them', 'SellReturnController@create')->name('hangtralai.ban.create');
        Route::get('/ban/sua/{id}', 'SellReturnController@edit')->name('hangtralai.ban.edit');
        Route::get('/mua', 'BuyReturnController@index')->name('hangtralai.mua.index');
        Route::get('/mua/them', 'SellReturnController@create')->name('hangtralai.mua.create');
        Route::get('/mua/sua/{id}', 'SellReturnController@edit')->name('hangtralai.mua.edit');
    });

    Route::get('/installment-company', '\App\Http\Controllers\InstallmentCompanyController@index')->name('installment-company.index');
    Route::get('/installment-company/create', '\App\Http\Controllers\InstallmentCompanyController@create')->name('installment-company.create.index');
    Route::get('/installment-company/edit/{id}', '\App\Http\Controllers\InstallmentCompanyController@edit')->name('installment-company.edit.index');

    Route::get('/contact', '\App\Http\Controllers\ContactController@index')->name('contact.index');
    Route::get('/contact/create', '\App\Http\Controllers\ContactController@create')->name('contact.create.index');
    Route::get('/contact/edit{id}', '\App\Http\Controllers\ContactController@edit')->name('contact.edit.index');
});

Route::view('user/login', 'user.login')->name('user.login');
Route::post('user/do-login', 'UserController@doLogin')->name('user.doLogin');
Route::get('user/logout', 'UserController@doLogout')->name('user.doLogout');
