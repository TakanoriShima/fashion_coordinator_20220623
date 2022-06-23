@extends('layouts.basic')
@section('title', 'fatching')
@section('content')
    @if(!Auth::check())
    <div class="top">
          <h2 class="worries">こんな悩みはありませんか？</h2>
      </div>
      <div class="box fadeIn">
        <p>買ったけど何に合わせていいかわからない、何年もクローゼットの奥底に眠っていて肥やしになっている
        <br>好きでよく着ているけど毎回同じ着こなしになってしまう
        <br>そんな悩みの服を1着は持っていると思います</p>
      </div>
      <div class="box fadeIn" class="active">
          <h2 class="worries">あと、こんな悩みもありませんか？</h2>
      </div>
      <div class="box fadeIn" class="active">
        <p>相談したいけど店員さんと話すのが苦手、服を持っていって聞くのは恥ずかしい、子育てや仕事で時間がない
        <br>こう思っている方も多いと思います。
        <br>ですが、〇〇を使えばいつでもどこでも相談できます！
        <br>相談方法はとても簡単です。
        <br>相談したい服の写真を撮って、困っていること聞きたいことを記入して投稿するだけ！
        <br>現役のアパレルスタッフがあなたの悩みを解決してくれます。
        <br>メッセージ機能で直接やりとりもでき、より詳しく聞くことができます！
        <br>まずは会員登録から始めましょう♪</p>
      </div>
      <!--<div class="box fadeIn" class="active">-->
        <h3>
          <a href="/signup" class="btn btn-primary" class="go">GO!!</a>
        </h3>
      <!--</div>-->
      @endif
@endsection