<?php

namespace App\Http\Livewire\Common\Modal;

use App\Models\Menu;
use App\Models\UserHasMenu;
use App\Service\UserHasMenuService;
use Livewire\Component;

class ModalConfigMenu extends Component
{
    public function render()
    {
        $menuList = Menu::all();
        $menuChecked = UserHasMenuService::getMenu();
        foreach ($menuList as $menu){
            foreach ($menuChecked as $option){
                if ($menu->id == $option->menu_id){
                    $menu->isChecked = true;
                    break;
                }
            }
        }
        return view('livewire.common.modal.modal-config-menu',['menu'=>$menuList]);
    }
}
