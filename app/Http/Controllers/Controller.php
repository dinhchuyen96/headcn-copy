<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Route;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
	public function __construct()
	{
        $name = Route::getCurrentRoute()->getName();
        // $name = str_replace('update', 'edit', $name);
        // $name = str_replace('show', 'index', $name);
        // $name = str_replace('detail', 'index', $name);
        // $name = str_replace('store', 'create', $name);
        // $name = str_replace('duplicate', 'create', $name);

        $arr_role = explode(".", $name);
        $role_name = end($arr_role);
        $name = str_replace($role_name, 'index', $name);

        // if($action == 'show' || $action == 'detail'){
        //    $action = ['show', 'detail', 'index'];
        // }
        // if($action == 'update'){
        //     $action = ['edit', 'update'];
        // }
        // if($action == 'store' || $action == 'duplicate'){
        //    $action = ['store', 'create', 'duplicate'];
        // }
        $action = ['show', 'detail', 'index','edit', 'update','store', 'create'];
        self::middleware('role_or_permission:administrator|'
          . '|' . $name);
	}
    public function escapeLikeSentence($column, $str, $before = true, $after = true)
    {
        $result = str_replace('\\', '[\]', $this->mb_trim($str)); // \ -> \\
        $result = str_replace('%', '\%', $result); // % -> \%
        $result = str_replace('_', '\_', $result); // _ -> \_
        return [[$column, 'LIKE', (($before) ? '%' : '') . $result . (($after) ? '%' : '')]];
    }
    public function mb_trim($string)
    {
        $whitespace = '[\s\0\x0b\p{Zs}\p{Zl}\p{Zp}]';
        $ret = preg_replace(sprintf('/(^%s+|%s+$)/u', $whitespace, $whitespace), '', $string);
        return $ret;
    }
}
