@extends('layouts.app')
@section('content')
    <div class="text-center">
        <h1>チャットルームNo: {{ $room->id }} の詳細</h1>
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
    
    
    <div class="row">
        <div class="container chatroom_border">
            @foreach($chats as $chat)
                @if($chat->user_id === Auth::id())
                <div class="offset-sm-9 col-sm-3 chat_border">
                @else
                <div class="col-sm-3 chat_border">
                @endif
                    <small>{{ $chat->user->profile->nickname }} {{ $chat->created_at }}</small>
                    <p>{{ $chat->message }}</p>
                    <p><img src="{{ asset('uploads')}}/{{$chat->image}}" alt="{{ $chat->image }}"></p>
                </div>
            @endforeach
            <div class="row">
                <form action="/rooms/{{ $room->id }}/chats" method="POST" class="" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="col-3">
                        <input type="file" name="image" accept="image/*" onchange="previewImage(this);">
                    </div>
                    <input type="text" name="message" class="send">
                    <button type="submit" class="send">送信</button>
                </form>
            </div>
        </div>
    </div>
    
    @endsection