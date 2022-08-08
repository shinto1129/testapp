<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Item;
use App\Models\Tool;
use App\Models\Room;
use App\Models\Register;

class RegisterController extends Controller
{
    public function registerPeriod(Request $request){
        $count = Item::get()->count();
        $user = Auth::user();
        $item = $request;
        $check = Register::where('user_id', $user['id'])
        ->where('period', $item['period'])
        ->where('week', $item['week'])
        ->count();
        if($check != 0){
            return redirect()->route('calendar')->with('status', 'すでに登録されています');
        }
        $id = Register::insertGetId([
            'user_id' => $user['id'],
            'week' => $item['week'],
            'period' => $item['period'],
            'room_id' => $item['room'],

        ]);
        for($i = 0; $i < $count; $i++){
            if(!empty($item['item'.$i])){
                Tool::insert([
                    'register_id' => $id,
                    'item_id' => $item['item'.$i]
                ]);
            }
        }
        return redirect()->route('calendar')->with('status', '登録しました');
    }

    public function delete($id){

        $data = Register::where('id', $id)
        ->delete();
        return redirect()->route('calendar')->with('status', '削除完了しました');
    }
}
