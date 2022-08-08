<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Register;
use App\Models\Item;
use App\Models\Tool;
use App\Models\Room;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        return view('home', compact('user'));
    }
    public function calendar()
    {
        $user = Auth::user();
        $register = Register::where('user_id', $user['id'])->get();
        $items = Item::get();
        $rooms = Room::get();
        return view('calendar', compact('user', 'items', 'register', 'rooms'));
    }
    /**
     *
     *
     */
    public function edit(Request $request)
    {
        $user = Auth::user();

        if(
            $user['name'] != $request['name'] ||
            $user['adress'] != $request['adress'] ||
            $user['tel'] != $request['tel'] ||
            $user['email'] != $request['email']

        )
        {
            if($user['email'] != $request['email']){
                $data = $request->validate([
                    'name' => ['required', 'string', 'max:255'],
                    'adress' => ['required', 'string', 'max:255'],
                    'tel' => ['required', 'string', 'max:20'],
                    'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                ]);
                $data = User::where('id', $user['id'])->update([
                    'name' => $data['name'],
                    'adress' => $data['adress'],
                    'tel' => $data['tel'],
                    'email' => $data['email'],
                ]);
            }
            else{
                $data = $request->validate([
                    'name' => ['required', 'string', 'max:255'],
                    'adress' => ['required', 'string', 'max:255'],
                    'tel' => ['required', 'string', 'max:20'],
                ]);
                $data = User::where('id', $user['id'])->update([
                    'name' => $data['name'],
                    'adress' => $data['adress'],
                    'tel' => $data['tel'],
                ]);

            }
            return redirect()->route('home')->with('status', '編集完了しました');
        }
        else
        {

            return redirect()->route('home');
        }

    }
    public function management()
    {
        $register = Register::join('rooms', 'registers.room_id', '=', 'rooms.id')
        ->select('registers.*', 'rooms.name as rname', 'rooms.id as rid')
        ->get();
        $badding = Register::select(DB::raw('week ,COUNT(week) AS count_week, period, room_id'))
        ->groupBy('week', 'period', 'room_id')
        ->having('count_week', '>=', 2)
        ->orderBy('week')
        ->get();

        $data = Register::join('users', 'registers.user_id', '=', 'users.id')
        ->join('rooms', 'registers.room_id', '=', 'rooms.id')
        ->orderBy('week')
        ->orderBy('registers.period')
        ->select('users.name as uname', 'registers.*', 'rooms.name as rname')
        ->paginate(10)
        ;
        $items = Tool::join('items', 'tools.item_id', '=', 'items.id')
        ->select('tools.register_id as rid', 'items.*')
        ->get();
        ;
        $rooms = Room::get();
        $allUser = User::get();
        return view('management', compact('data', 'items', 'allUser', 'badding', 'register', 'rooms'));
    }
    public function select(Request $request)
    {
        $badding = Register::select(DB::raw('week ,COUNT(week) AS count_week, period', 'room_id'))
        ->groupBy('week', 'period', 'room_id')
        ->having('count_week', '>=', 2)
        ->orderBy('week')
        ->get();

        $rooms = Room::get();

        $selectedData = Register::join('users', 'registers.user_id', '=', 'users.id')
        ->join('rooms', 'registers.room_id', '=', 'rooms.id')
        ;
        if($request['week'] != 0){
            $selectedData->where('week', $request['week']);
        }
        if($request['user_id'] != 0){
            $selectedData->where('user_id', $request['user_id']);
        }
        if($request['room_id'] != 0){
            $selectedData->where('room_id', $request['room_id']);
        }
        $data = $selectedData->orderBy('week')
        ->orderBy('registers.period')
        ->select('users.name as uname', 'registers.*', 'rooms.name as rname')
        ->paginate(10)
        ;
        $items = Tool::join('items', 'tools.item_id', '=', 'items.id')
            ->select('tools.register_id as rid', 'items.name as name')
            ->get();
            ;
        $flg = 1;
        $allUser = User::get();
        return view('management', compact('data', 'flg', 'items', 'allUser', 'badding', 'rooms'));
    }

    public function chenge($id)
    {
        $badding = Register::select(DB::raw('week ,COUNT(week) AS count_week, period', 'room_id'))
        ->groupBy('week', 'period', 'room_id')
        ->having('count_week', '>=', 2)
        ->orderBy('week')
        ->get();


        $data = Register::join('users', 'registers.user_id', '=', 'users.id')
        ->orderBy('week')
        ->orderBy('registers.period')
        ->where('users.id', $id)
        ->select('users.name as uname', 'registers.*')
        ->paginate(10)
        ;
        $items = Tool::join('items', 'tools.item_id', '=', 'items.id')
            ->select('tools.register_id as rid', 'items.name as name')
            ->get();
            ;
        $flg = 1;
        $allUser = User::get();
        $rooms = Room::get();
        return view('management', compact('data', 'flg', 'items', 'allUser', 'badding', 'rooms'));
    }
    public function cale($id)
    {
        $badding = Register::select(DB::raw('week ,COUNT(week) AS count_week, period', 'room_id'))
        ->groupBy('week', 'period', 'room_id')
        ->having('count_week', '>=', 2)
        ->orderBy('week')
        ->get();


        $data = Register::join('users', 'registers.user_id', '=', 'users.id')
        ->where('week', $id)
        ->orderBy('week')
        ->orderBy('registers.period')
        ->select('users.name as uname', 'registers.*')
        ->paginate(10)
        ;
        $items = Tool::join('items', 'tools.item_id', '=', 'items.id')
            ->select('tools.register_id as rid', 'items.name as name')
            ->get();
            ;
        $flg = 1;
        $allUser = User::get();
        $rooms = Room::get();
        return view('management', compact('data', 'flg', 'items', 'allUser', 'badding', 'rooms'));
    }
    public function sort($id1, $id2, $id3)
    {
        $badding = Register::select(DB::raw('week ,COUNT(week) AS count_week, period', 'room_id'))
        ->groupBy('week', 'period', 'room_id')
        ->having('count_week', '>=', 2)
        ->orderBy('week')
        ->get();


        $data = Register::join('users', 'registers.user_id', '=', 'users.id')
        ->join('rooms', 'registers.room_id', '=', 'rooms.id')
        ->where('week', $id1)
        ->where('period', $id2)
        ->where('rooms.id', $id3)
        ->orderBy('week')
        ->orderBy('registers.period')
        ->select('users.name as uname', 'registers.*', 'rooms.name as rname')
        ->paginate(10)
        ;
        $items = Tool::join('items', 'tools.item_id', '=', 'items.id')
            ->select('tools.register_id as rid', 'items.name as name')
            ->get();
            ;
        $flg = 1;
        $allUser = User::get();
        $rooms = Room::get();
        return view('management', compact('data', 'flg', 'items', 'allUser', 'badding', 'rooms'));
    }
    public function check($id1, $id2)
    {
        $badding = Register::select(DB::raw('week ,COUNT(week) AS count_week, period', 'room_id'))
        ->groupBy('week', 'period', 'room_id')
        ->having('count_week', '>=', 2)
        ->orderBy('week')
        ->get();


        $data = Register::join('users', 'registers.user_id', '=', 'users.id')
        ->join('rooms', 'registers.room_id', '=', 'rooms.id')
        ->where('week', $id1)
        ->where('period', $id2)
        ->orderBy('week')
        ->orderBy('registers.period')
        ->select('users.name as uname', 'registers.*', 'rooms.name as rname')
        ->paginate(10)
        ;
        $items = Tool::join('items', 'tools.item_id', '=', 'items.id')
            ->select('tools.register_id as rid', 'items.name as name')
            ->get();
            ;
        $flg = 1;
        $allUser = User::get();
        $rooms = Room::get();
        return view('management', compact('data', 'flg', 'items', 'allUser', 'badding', 'rooms'));
    }
}
