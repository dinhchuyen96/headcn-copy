<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Gift;
use Illuminate\Http\Request;

class GiftController extends Controller
{
    public function index()
    {
        return view('Gift.dsGift');
    }
    public function create()
    {
        return view('Gift.themmoi');
    }
    public function edit($id)
    {
        $giftItem = Gift::findOrFail($id);
        return view('Gift.sua', ['giftItem' => $giftItem]);
    }
    public function show($id)
    {
        $giftItem = Gift::findOrFail($id);
        return view('Gift.show', ['giftItem' => $giftItem]);
    }
    public function setting()
    {
        return view('Gift.setting');
    }
}
