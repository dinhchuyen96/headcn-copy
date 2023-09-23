<?php

namespace App\Utils;

use App\Models\Role;
use Illuminate\Support\Facades\Request;

class MenuUtils
{
    public function build()
    {
        $menuItems = config('menu');
        if (empty($menuItems)) {
            return [];
        }

        $auth = auth()->user();
        foreach ($menuItems as $key => &$item) {
            $item = $this->parseItem($item, $auth);
            if (empty($item)) unset($menuItems[$key]);
        }

        return $menuItems;
    }

    private function parseItem(&$item, $auth)
    {
        if ($this->hasPermission($item, $auth) == false) {
            return null;
        }
        $item['href'] = $this->parseHref($item);
        $item['active'] = $this->parseActive($item);
        if (isset($item['sub_menu']) && count($item['sub_menu']) > 0) {
            foreach ($item['sub_menu'] as $key => &$subItem) {
                $subItem = $this->parseItem($subItem, $auth);
                if (empty($subItem)) unset($item['sub_menu'][$key]);
            }
            if (empty($item['sub_menu'])) return null;
        }

        return $item;
    }

    private function hasPermission($item, $auth)
    {
        if (empty($auth)) {
            return false;
        }
        if (empty($item['can']) || $auth->hasRole('administrator') || $auth->hasAnyDirectPermission($item['can'])) {
            return true;
        }

        return false;
    }

    private function parseHref(&$item)
    {
        if (isset($item['route'])) {
            $href = route($item['route']);
            unset($item['route']);
        }
        if (isset($item['url'])) {
            $href = url($item['url']);;
            unset($item['url']);
        }
        return $href ?? 'javascript:void(0)';
    }

    private function parseActive(&$item) : bool
    {
        if (!empty($item['href']) && request()->url() == $item['href']) {
            return true;
        }
        $arrActive = $item['active'] ?? [];
        if (isset($item['sub_menu']) && count($item['sub_menu']) > 0) {
            $arrRoute = array_column($item['sub_menu'], 'route');
            $arrActive = array_merge($arrActive, array_column($item['sub_menu'], 'active'));

            if (!empty(array_column($item['sub_menu'], 'sub_menu'))) {
                $arrSubMenu = array_column($item['sub_menu'], 'sub_menu');
                $arrSubMenu = array_merge(...$arrSubMenu);
                $arrRoute = array_merge($arrRoute, array_column($arrSubMenu, 'route'));
                $arrActive = array_merge($arrActive, array_column($arrSubMenu, 'active'));
            }
            $arrRoute = array_map(function ($v) {
                return route($v);
            }, $arrRoute);

            if (in_array(request()->url(), $arrRoute)) {
                return true;
            }
        }
        foreach ($arrActive as $pattern) {
            if (Request::is($pattern)) {
                return true;
            }
        }

        return false;
    }
}
