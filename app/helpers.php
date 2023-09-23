<?php

/**
 * Return nav-here if current path begins with this path.
 *
 * @param string $path
 * @return string
 */

use App\Models\Customer;
use App\Models\HMSReceivePlan;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


function setActive($path)
{
    return \Request::is($path . '*') ? ' class=active' :  '';
}
function setActive2($path)
{
    return \Request::is($path . '*') ? ' active' :  '';
}
function setActive3($path)
{
    return \Request::is($path) ? ' class=active' :  '';
}

function setOpen($path)
{
    return \Request::is($path . '*') ? ' class=open' :  '';
}
function countChiPhiTap($data)
{
    $license_fee = $data->license_fee ?? 0;
    $partner_fee = $data->partner_fee ?? 0;
    $other_fee = $data->other_fee ?? 0;
    return $license_fee + $partner_fee + $other_fee;
}
function countChiPhi($data)
{
    $license_fee = $data->license_fee ?? 0;
    $partner_fee = $data->partner_fee ?? 0;
    $other_fee = $data->other_fee ?? 0;
    $count_tap = (int)$data->count_tap ?? 0;
    return ($license_fee + $partner_fee + $other_fee) * $count_tap;
}


function getAdminName($id)
{
    $result = \App\Admin::find($id);
    return $result['name'];
}

function reFormatDate($datetime, $format = 'd-m-Y')
{
    return (isset($datetime) && ($datetime != '0000-00-00 00:00:00') && ($datetime != '0000-00-00')) ? date($format, strtotime($datetime)) : '';
}

function numberFormat($money = 0, $dec_point = '.', $thousands_sep = ',')
{
    $arr = explode('.', sprintf("%.2f", $money));
    $decimal = (count($arr) > 1 && $arr[1] != '00') ? 2 : 0;
    return number_format($money, $decimal, $dec_point, $thousands_sep);
}

function getTotalMoney($data, $key, $id)
{
    $money = 0;
    for ($i = $key; $i >= 0; $i--) {
        if ($data[$i]->id == $id) {
            $money += $data[$i]->total_money;
        } else return $money;
    }
    return $money;
}

function countUnPaid($data, $key, $moneyUnpaidBefore)
{
    $money = $moneyUnpaidBefore;
    for ($i = $key; $i >= 0; $i--) {
        if ($data[$i]->type_table == 1) {
            $money -= $data[$i]->total_money;
        } else {
            $money += $data[$i]->total_money;
        }
    }
    return $money;
}

function getMoneyInArray($array, $id)
{
    return isset($array[$id]) ? $array[$id] : 0;
}
function decodeEmoji($content)
{
    return \App\Emoji::Decode($content);
}



function getToday()
{
    $today = date("m/d/Y");

    return $today . ' - ' . $today;
}

function get7Day()
{
    $today = date('m/d/Y');

    $sevenDay = date('m/d/Y', strtotime("-7 days"));

    return $sevenDay . ' - ' . $today;
}

function formatBirthday($value)
{
    if ($value)
        return Carbon::createFromFormat('Y-m-d', $value)->format('d/m/Y');
    return '';
}

function getTodayPicker()
{
    $today = date("m/d/Y");

    return $today . ' - ' . $today;
}

function checkRole($role, $role2)
{
    if ($role2) {
        if (Auth::user()->hasRole($role) || Auth::user()->hasRole('administrator') || Auth::user()->hasRole($role2)) return true;
        else return false;
    } else {
        if (Auth::user()->hasRole($role) || Auth::user()->hasRole('administrator')) return true;
        else return false;
    }
}
function checkPermission($permission)
{
    if (Auth::user()->hasRole('administrator')) {
        return true;
    }
    if (Auth::user()->can($permission)) {
        return true;
    }
    return false;
}
function getMailReply($id)
{
    $result = \App\Message::where(['parentId' => $id, 'status' => 1])->get();
    return $result;
}

function getMasterMailReply($id)
{
    $result = \App\Message::where(['parentId' => $id, 'status' => 1, 'senderUserId' => 1000000])->get();
    return $result;
}
function stringLimit($str, $limit = 30)
{
    return Str::limit($str, $limit);
}

function charCalculation($char, $number)
{
    $charList = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ'];
    foreach ($charList as $key => $value) {
        if ($value == $char) {
            $number += $key;
            break;
        }
    }
    return $charList[$number];
}

function myMedia($admin_id)
{
    if (Auth::user()->id == $admin_id || Auth::user()->is_manager == 1)
        return true;
    return false;
}
function removeFormatNumber($number, $specials = ['.', ','])
{
    foreach ($specials as $special) {
        $number = str_replace($special, '', $number);
    }
    return (int)$number;
}

function boldTextSearchV2($text, $searchTerm)
{
    if (!strlen($searchTerm)) return $text;
    $newText = strtolower(removeStringUtf8($text));
    $newSearchTerm = strtolower(removeStringUtf8($searchTerm));
    $lengText = strlen($newText);
    $lengSearchTerm = strlen($newSearchTerm);
    $index = 0;
    for ($i = 0; $i <= $lengText - $lengSearchTerm; $i++) {
        if ($newSearchTerm == substr($newText, $i, $lengSearchTerm)) {
            // dd($newSearchTerm, $i, $newText,mb_substr($text,0,$i+$index),mb_substr($text,$i+$index,$lengSearchTerm));
            $text = mb_substr($text, 0, $i + $index) . '<b>' . mb_substr($text, $i + $index, $lengSearchTerm) . '</b>' . mb_substr($text, $i + $index + $lengSearchTerm, $lengText - $i - $lengSearchTerm);
            $index += 7;
        }
    }
    return $text;
}

function removeStringUtf8($str)
{
    $hasSign = array(
        'à', 'á', 'ạ', 'ả', 'ã', 'â', 'ầ', 'ấ', 'ậ', 'ẩ', 'ẫ', 'ă', 'ằ', 'ắ', 'ặ', 'ẳ', 'ẵ', '&agrave;', '&aacute;', '&acirc;', '&atilde;',
        'è', 'é', 'ẹ', 'ẻ', 'ẽ', 'ê', 'ề', 'ế', 'ệ', 'ể', 'ễ', '&egrave;', '&eacute;', '&ecirc;',
        'ì', 'í', 'ị', 'ỉ', 'ĩ', '&igrave;', '&iacute;', '&icirc;',
        'ò', 'ó', 'ọ', 'ỏ', 'õ', 'ô', 'ồ', 'ố', 'ộ', 'ổ', 'ỗ', 'ơ', 'ờ', 'ớ', 'ợ', 'ở', 'ỡ', '&ograve;', '&oacute;', '&ocirc;', '&otilde;',
        'ù', 'ú', 'ụ', 'ủ', 'ũ', 'ư', 'ừ', 'ứ', 'ự', 'ử', 'ữ', '&ugrave;', '&uacute;',
        'ỳ', 'ý', 'ỵ', 'ỷ', 'ỹ', '&yacute;',
        'đ', '&eth;',
        'À', 'Á', 'Ạ', 'Ả', 'Ã', 'Â', 'Ầ', 'Ấ', 'Ậ', 'Ẩ', 'Ẫ', 'Ă', 'Ằ', 'Ắ', 'Ặ', 'Ẳ', 'Ẵ', '&Agrave;', '&Aacute;', '&Acirc;', '&Atilde;',
        'È', 'É', 'Ẹ', 'Ẻ', 'Ẽ', 'Ê', 'Ề', 'Ế', 'Ệ', 'Ể', 'Ễ', '&Egrave;', '&Eacute;', '&Ecirc;',
        'Ì', 'Í', 'Ị', 'Ỉ', 'Ĩ', '&Igrave;', '&Iacute;', '&Icirc;',
        'Ò', 'Ó', 'Ọ', 'Ỏ', 'Õ', 'Ô', 'Ồ', 'Ố', 'Ộ', 'Ổ', 'Ỗ', 'Ơ', 'Ờ', 'Ớ', 'Ợ', 'Ở', 'Ỡ', '&Ograve;', '&Oacute;', '&Ocirc;', '&Otilde;',
        'Ù', 'Ú', 'Ụ', 'Ủ', 'Ũ', 'Ư', 'Ừ', 'Ứ', 'Ự', 'Ử', 'Ữ', '&Ugrave;', '&Uacute;',
        'Ỳ', 'Ý', 'Ỵ', 'Ỷ', 'Ỹ', '&Yacute;',
        'Đ', '&ETH;',
    );
    $noSign = array(
        'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a',
        'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e',
        'i', 'i', 'i', 'i', 'i', 'i', 'i', 'i',
        'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o',
        'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u',
        'y', 'y', 'y', 'y', 'y', 'y',
        'd', 'd',
        'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A',
        'E', 'E', 'E', 'E', 'E', 'E', 'E', 'E', 'E', 'E', 'E', 'E', 'E', 'E',
        'I', 'I', 'I', 'I', 'I', 'I', 'I', 'I',
        'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O',
        'U', 'U', 'U', 'U', 'U', 'U', 'U', 'U', 'U', 'U', 'U', 'U', 'U',
        'Y', 'Y', 'Y', 'Y', 'Y', 'Y',
        'D', 'D'
    );

    $str = str_replace($hasSign, $noSign, $str);
    return $str;
}

function checkRoute($action)
{
    $routerName = Route::getCurrentRoute()->getName();
    $arr = explode('.', $routerName);

    return $arr[count($arr) - 2] == $action;
}

function getSexName($id)
{
    if ($id == 1)
        return 'Nam';
    elseif ($id == 2)
        return 'Nữ';
    else
        return;
}
function vn_to_str($str)
{

    $unicode = array(

        'a' => 'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',

        'd' => 'đ',

        'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',

        'i' => 'í|ì|ỉ|ĩ|ị',

        'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',

        'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',

        'y' => 'ý|ỳ|ỷ|ỹ|ỵ',

        'A' => 'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',

        'D' => 'Đ',

        'E' => 'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',

        'I' => 'Í|Ì|Ỉ|Ĩ|Ị',

        'O' => 'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',

        'U' => 'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',

        'Y' => 'Ý|Ỳ|Ỷ|Ỹ|Ỵ',

    );

    foreach ($unicode as $nonUnicode => $uni) {

        $str = preg_replace("/($uni)/i", $nonUnicode, $str);
    }
    //$str = str_replace(' ', '_', $str);

    return $str;
}
function docsothanhchu($sotien = 0)
{
    $Text = array("không", "một", "hai", "ba", "bốn", "năm", "sáu", "bảy", "tám", "chín");
    $TextLuythua = array("", "nghìn", "triệu", "tỷ", "ngàn tỷ", "triệu tỷ", "tỷ tỷ");
    if ($sotien <= 0) {
        return $textnumber = "Tiền phải là số nguyên dương lớn hơn số 0";
    }
    $textnumber = "";
    $length = strlen($sotien);
    for ($i = 0; $i < $length; $i++)
        $unread[$i] = 0;
    for ($i = 0; $i < $length; $i++) {
        $so = substr($sotien, $length - $i - 1, 1);
        if (($so == 0) && ($i % 3 == 0) && ($unread[$i] == 0)) {
            for ($j = $i + 1; $j < $length; $j++) {
                $so1 = substr($sotien, $length - $j - 1, 1);
                if ($so1 != 0)
                    break;
            }
            if (intval(($j - $i) / 3) > 0) {
                for ($k = $i; $k < intval(($j - $i) / 3) * 3 + $i; $k++)
                    $unread[$k] = 1;
            }
        }
    }
    for ($i = 0; $i < $length; $i++) {
        $so = substr($sotien, $length - $i - 1, 1);
        if ($unread[$i] == 1)
            continue;

        if (($i % 3 == 0) && ($i > 0))
            $textnumber = $TextLuythua[$i / 3] . " " . $textnumber;

        if ($i % 3 == 2)
            $textnumber = 'trăm ' . $textnumber;

        if ($i % 3 == 1)
            $textnumber = 'mươi ' . $textnumber;
        $textnumber = $Text[$so] . " " . $textnumber;
    }
    $textnumber = str_replace("không mươi", "lẻ", $textnumber);
    $textnumber = str_replace("lẻ không", "", $textnumber);
    $textnumber = str_replace("mươi không", "mươi", $textnumber);
    $textnumber = str_replace("một mươi", "mười", $textnumber);
    $textnumber = str_replace("mươi năm", "mươi lăm", $textnumber);
    $textnumber = str_replace("mươi một", "mươi mốt", $textnumber);
    $textnumber = str_replace("mười năm", "mười lăm", $textnumber);

    return ucfirst($textnumber . " đồng chẵn");
}

function isRepair($checkService)
{
    if (count($checkService) == 0)
        return false;
    $arrayRepair = [];
    for ($i = 11; $i <= 20; $i++) {
        $arrayRepair[] = $i;
    }
    for ($i = 31; $i <= 40; $i++) {
        $arrayRepair[] = $i;
    }
    for ($i = 51; $i <= 60; $i++) {
        $arrayRepair[] = $i;
    }
    for ($i = 71; $i <= 80; $i++) {
        $arrayRepair[] = $i;
    }
    for ($i = 91; $i <= 95; $i++) {
        $arrayRepair[] = $i;
    }
    for ($i = 101; $i <= 105; $i++) {
        $arrayRepair[] = $i;
    }

    $arrayIntersec = array_intersect($checkService, $arrayRepair);
    if (count($arrayIntersec) > 0)
        return true;
    return false;
}
function getAddressByUserId($customerId)
{
    $customer = Customer::where('id', $customerId)->with(['wardCustomer', 'districtCustomer', 'provinceCustomer'])->first();
    if ($customer)
        return $customer->address . (' ' . (isset($customer->wardCustomer) ? $customer->wardCustomer->name : '')) . (' ' . (isset($customer->districtCustomer) ? $customer->districtCustomer->name : '')) . (' ' . (isset($customer->provinceCustomer) ? $customer->provinceCustomer->name : ''));
    return '';
}

function getModelCodeOfMotorbike($chassicNo, $energyNo)
{
    $mtocMotorbike = HMSReceivePlan::where('chassic_no', $chassicNo)->where('engine_no', $energyNo)->first();
    if ($mtocMotorbike)
        return $mtocMotorbike->model_code;
    return '-';
}


function paginate($items, $perPage = 15, $page = null, $options = [])
{
    $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);

    $items = $items instanceof Collection ? $items : Collection::make($items);

    return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
}
