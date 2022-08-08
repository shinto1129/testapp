@extends('layouts.app')

@section('content')
<div class="container">
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    <div class="card-header">{{ __('教室登録') }}</div>
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
                        @endphp
                            @foreach ($register as $re)
                                @if ($re->period == $i)
                                    @if ($re->week == $j)
                                        <td class="orenge sinsei">教室{{ $re->room_id }}<br><a href="/delete/{{$re->id}}" class="delete">取り消す</a></td>
                                        @php
                                            $box = 1;
                                        @endphp
                                        @break
                                    @endif
                                @endif
                            @endforeach
                            @if ($box == 0)
                                <td class="orenge"><button id="select" type="button" class="super" data-toggle="modal" data-target="#testModal" data-period="{{ $i }}" data-week="{{ $j }}"></button></td>
                            @endif
                        @endif
                    @endfor
                </tr>
            @endfor
        </table>
    </div>

    <!-- ボタン・リンククリック後に表示される画面の内容 -->
    <div class="modal fade" id="testModal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">教室登録画面</h4></h4>
                </div>
                <form method="post" action="/registerPeriod">
                    @csrf
                    <div class="row mb-3">
                        <label for="date" class="col-md-4 col-form-label text-md-end">{{ __('曜日') }}</label>

                        <div class="col-md-6">
                            <select id="week-select" class="form-control" name='week'>
                                <option value="1">月曜日</option>
                                <option value="2">火曜日</option>
                                <option value="3">水曜日</option>
                                <option value="4">木曜日</option>
                                <option value="5">金曜日</option>
                                <option value="6">土曜日</option>
                                <option value="7">日曜日</option>
                            </select>
                            @error('week')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="period" class="col-md-4 col-form-label text-md-end">{{ __('何限目') }}</label>

                        <div class="col-md-6">
                            <select id="period-select" class="form-control" name='period'>
                                <option value="1">１限目</option>
                                <option value="2">２限目</option>
                                <option value="3">３限目</option>
                                <option value="4">４限目</option>
                                <option value="5">５限目</option>
                                <option value="6">６限目</option>
                            </select>
                            @error('period')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="room" class="col-md-4 col-form-label text-md-end">{{ __('教室') }}</label>

                        <div class="col-md-6">
                            <select id="room" class="form-control" name='room'>
                                @foreach ($rooms as $room)
                                <option value="{{ $room->id }}">{{ $room->name }}</option>
                                @endforeach
                            </select>
                            @error('room')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="item" class="col-md-4 col-form-label text-md-end">{{ __('必要なもの') }}</label>

                        <div class="col-md-6">
                            @foreach ($items as $item)
                            <div>
                                <label for="checkbox{{ $item['id'] }}">{{ $item['name'] }}</label>
                                <input type="checkbox" id="checkbox{{ $item['id'] }}" name="item{{ $item['id'] }}" value="{{ $item['id'] }}">
                            </div>
                            @endforeach
                            @error('item')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                                {{ __('登録') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

