<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class HomeController extends Controller{
    private $user;

    /**
     * HomeController constructor.
     * @param User $user
     */
    public function __construct(User $user){
        $this->user = $user;
    }

    public function index(){
        return view('dashboard');
    }

    public function profile()
    {
        $user=Auth::user();
        return view('profile',compact('user'));
    }

    public function edit()
    {
        return view('edit');
    }

    public function update(Request $request)
    {
        if(Auth::Check())
        {
            $this->validate($request, [
                'current_password' => 'required',
                'password' => 'required',
                'confirm_password' => 'required|same:password',
                ], [
                'current_password.required' =>'Mật khẩu hiện tại là bắt buộc',
                'password.required' => 'Mật khẩu mới là bắt buộc',
                'password.regex' => 'Mật khẩu mới không hợp lệ',
                'confirm_password.same' => 'Mật khẩu xác nhận không trùng khớp với mật khẩu mới'
                ], [
                'current_password' => 'Mật khẩu hiện tại',
                'password' => 'Mật khẩu mới',
                'confirm_password' => 'Mật khẩu xác nhận',
            ]
        );

            if(Hash::check($request->current_password, Auth::user()->password))
            {
                $user = Auth::user();
                $user->password = Hash::make($request->password);
                $user->save();
                return redirect()->route('dashboard')
                    ->with('success','Đổi mật khẩu thành công');
            }
            else
            {
                return redirect()->route('edit')->with('error','Nhập sai mật khẩu hiện tại');
            }
        }
        else
        {
            return redirect()->to('/');
        }
    }

    public function support()
    {
        return view('support');
    }

}
