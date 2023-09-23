<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginFormRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    private $user;

    /**
     * UserController constructor.
     * @param User $user
     */
    public function __construct(User $user){
        $this->user = $user;
    }

    /**
     * @param LoginFormRequest $request
     * @return RedirectResponse
     */
    public function doLogin(LoginFormRequest $request){
        $fields = $request->validated();
        $data = $this->user->doLogin($fields);
        if ($data['status']){
            return redirect()->route('dashboard');
        }

        return redirect()->back()->with('error','Thông tin đăng nhập hoặc mật khẩu không đúng');
    }

    /**
     * @param user
     * @return RedirectResponse
     * kill session and redirect to login route
     */
    public function doLogout(){
        $this->user['status']=false;
        return redirect()->route('user.login');
    }

}
