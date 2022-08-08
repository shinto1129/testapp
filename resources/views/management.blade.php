@extends('layouts.app')

@section('content')
<div class="container">
    @if (!empty($msg))
    {{ $msg }}
    @endif
    @if(!empty($register))
    @if(!empty($badding))
    <div class="red">
        <h2>バッティングしている時間があります</h2>
        <ul>
            @foreach ($badding as $bad)
                <li><a href="/sort/{{ $bad->week }}/{{ $bad->period }}/{{ $bad->room_id }}" class="red">
                    @switch($bad->week)
                        @case(1)
                            月曜日
                            @break
                        @case(2)
                            火曜日
                            @break
                        @case(3)
                            水曜日
                            @break
                        @case(4)
                            木曜日
                            @break
                        @case(5)
                            金曜日
                            @break
                        @case(6)
                            土曜日
                            @break
                        @case(7)
                            日曜日
                            @break
                        @default

                    @endswitch
                     {{ $bad->period }}限目 教室
                    @foreach ($rooms as $room)
                       @if ($bad->room_id == $room->id)
                           {{ $room->name }}
                           @break
                       @endif
                    @endforeach
                    </a></li>
            @endforeach
        </ul>
    </div>
    @endif
    <div class="week-wrapper">
        <table class="week-table">
            <tr>
                <th></th>
                <th>月</th>
                <th>火</th>
                <th>水</th>
                <th>木</th>
                <th>金</th>
                <th>土</th>
                <th>日</th>
            </tr>
            @for ($i = 1; $i <= 7; $i++)
                <tr>
                    @for ($j = 0; $j <= 7; $j++)
                        @if($j == 0)
                        <th class="aqua">{{ $i }}</th>
                        @else
                        @php
                            $box = 0;
                            $checkBox = 0;
                        @endphp
                            @foreach ($register as $re)
                                @if ($re->period == $i)
                                    @if ($re->week == $j)
                                        <td class="orenge sinsei">
                                        @foreach ($badding as $bad)
                                            @if ($bad->week == $re->week && $bad->period == $re->period)
                                                <a class="red small" style="cursor: hand; cursor:pointer;" href="/sort/{{ $re->week }}/{{ $re->period }}/{{ $re->rid }}">バッティング中<br>編集する</a></td>
                                                @php
                                                    $checkBox = 1;
                                                @endphp
                                                @break
                                            @endif
                                        @endforeach
                                        @if ( $checkBox == 0 )
                                        問題なし<br>
                                        <a href="/check/{{ $re->week }}/{{ $re->period }}">確認する</a></td>
                                        @endif
                                        @php
                                            $box = 1;
                                        @endphp
                                        @break
                                    @endif
                                @endif
                            @endforeach
                            @if ($box == 0)
                                <td class="orenge"></td>
                            @endif
                        @endif
                    @endfor
                </tr>
            @endfor
        </table>
    </div>
    @endif
    <h1>登録情報一覧</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>曜日</th>
                <th>時限</th>
                <th>教室</th>
                <th>必要なもの</th>
                <th>登録者</th>
            </tr>
        </thead>
        <tbody>
            @if(!empty($data) && $data->count())
            @php
                $setBox = 0;
                $setPeriod = 0;
                $subweek = 0;
                $subperiod = 0;
            @endphp
                @foreach($data as $key => $value)
                @php
                $subBox = $data[$key + 1];
                if(!empty($subBox)){
                    $subweek = $subBox['week'];
                    $subperiod = $subBox['period'];
                }else{
                    $subweek = 1;
                    $subperiod = 1;
                }
                @endphp
                    <tr>
                        <td><a href="/cale/{{ $value->week }}">
                            @switch($value->week)
                            @case(1)
                                月曜日
                                @break
                            @case(2)
                                火曜日
                                @break
                            @case(3)
                                水曜日
                                @break
                            @case(4)
                                木曜日
                                @break
                            @case(5)
                                金曜日
                                @break
                            @case(6)
                                土曜日
                                @break
                            @case(7)
                                日曜日
                                @break
                            @default

                        @endswitch
                        </a> @foreach ($badding as $b) @if($b->week == $value->week && $b->period == $value->period)<p class="red">バッティングしています</p> @endif @endforeach</td>
                        <td>{{ $value->period }}限目</td>
                        <td>{{ $value->rname }}</td>
                        <td>
                            @foreach ($items as $item)
                            @if ($value['id'] === $item['rid'])
                                {{ $item->name }}<br>
                            @endif
                            @endforeach
                        </td>
                        <td>{{ $value->uname }}</td>
                        @php
                            $setBox = $value->week;
                            $setPeriod = $value->period;
                        @endphp
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="10">登録情報がありません</td>
                </tr>
            @endif
        </tbody>
    </table>
    {!! $data->links() !!}
    <form action="/select" method="post" width="20px" class="hei">
        @csrf
        <h2>絞り込み</h2>
        <div class="siz">
            <label>{{ __('曜日') }}</label>
            <select id="week" class="form-control" name='week'>
                <option value="0">選択しない</option>
                <option value="1">月曜日</option>
                <option value="2">火曜日</option>
                <option value="3">水曜日</option>
                <option value="4">木曜日</option>
                <option value="5">金曜日</option>
                <option value="6">土曜日</option>
                <option value="7">日曜日</option>
            </select>
            <div class="mt">
            </div>
            <label>{{ __('講師選択') }}</label>
            <select name="user_id" id="user_id" class="form-control">
                <option value="0">選択しない</option>
                @foreach ($allUser as $user)
                    <option value="{{$user->id}}">{{ $user->name }}</option>
                @endforeach
            </select>
            <div class="mt">
            </div>
            <label>{{ __('教室選択') }}</label>
            <select name="room_id" id="room_id" class="form-control">
                <option value="0">選択しない</option>
                @foreach ($rooms as $room)
                    <option value="{{$room->id}}">{{ $room->name }}</option>
                @endforeach
            </select>
            <div class="mt">
                <button type="submit" class="btn btn-primary" id="week3">
                    {{ __('検索') }}
                </button>
            </div>
            @if(!empty($flg))
            <div class="boto">
                <a href="/management" id="week3">一覧に戻る</a>
            </div>
            @endif
        </div>

    </form>
    @if(!empty($allUser))
    <div class="mt">
        <h1>非常勤講師一覧</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>名前</th>
                    <th>メールアドレス</th>
                </tr>
            </thead>
            <tbody>
                @foreach($allUser as $key => $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td><a href="/chenge/{{ $user->id }}">{{ $user->name }}</a></td>
                        <td>{{ $user->email }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
@endsection
