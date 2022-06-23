@extends('layouts.app')
@section('content')
    <div class="text-center">
        <h1>投稿No: {{ $post->id }} の詳細</h1>
    </div>
    <table class="table table-bordered table-striped">
        <tr>
            <th>No</th>
            <th>タイトル</th>
            <th>相談したいこと</th>
            <th>希望のスタイル</th>
            <th>相談したい洋服</th>
            <th>投稿日時</th>
        </tr>
        <tr>
            <td>{{ $post->id }}</td>
            <td>{{ $post->title }}</td>
            <td>{{ $post->content }}</td>
            <td>{{ $post->style }}</td>
            <td><img src="{{ asset('uploads')}}/{{$post->image}}" alt="{{ $post->image }}"></td>
            <td>{{ $post->created_at }}</td>
        </tr>
    </table>

    @if($post->user->id === Auth::id())
    <div class="row mt-3">
        {!! link_to_route('posts.edit', '編集' , ['id' => $post->id ],['class' => 'btn btn-primary col-sm-6']) !!}
        
        {!! Form::open(['route' => ['posts.destroy', 'id' => $post->id ], 'method' => 'DELETE', 'class' => 'col-sm-6']) !!}
            {!! Form::submit('削除', ['class' => 'btn btn-danger btn-block col-sm-12']) !!}
        {!! Form::close() !!}

    </div>
    <div class="row mt-3">
        <table class="offset-sm-4 col-sm-4 table table-borderblack table-striped">
            <tr>
                <th>回答者</th>
            </tr>
            @foreach($rooms as $room)
            <tr>
                <td class="respondentbox"><a href="/rooms/{{ $room->id }}" class="respondent">{{ $room->user->profile->nickname }}</a></td>
            </tr>
            @endforeach
        </table>
    </div>
    
    @endif

    @if(Auth::user()->role === 1)
        @if(!$post->is_room_exist(Auth::id()))
        <div class="row">
            <form action="/rooms" method="POST" class="offset-sm-5 col-sm-2 row">
                <input type="hidden" name="post_id" value="{{ $post->id }}">
                {{ csrf_field() }}
                <button type="submit" class="btn btn-primaryr">回答ルーム作成</button>
            </form>
        </div>
        @else
        <div class="row mt-3">
            <a href="/rooms/{{ $room->id }}" class="offset-sm-4 col-sm-4 btn btn-primaryr">回答ルームへ</a>
        </div>
        @endif
    @endif
    
@endsection