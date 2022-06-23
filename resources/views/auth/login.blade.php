@extends('layouts.basic')
@section('title', 'ログイン')
@section('content')
      <main class="form-signin w-100 m-auto">
        <form method="POST" action="/login">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <div class="form-floating">
            <input type="email" class="form-control" id="floatingInput"  name="email" placeholder="メールアドレス">
          </div>
          <div class="form-floating">
            <input type="password" class="form-control" id="floatingPassword" name="password" placeholder="パスワード">
          </div>
          <button class="w-100 btn btn-lg" type="submit">サインイン</button>
          <hr>
        </form>
          <!--<button class="w-100 btn btn-lg" type="submit">-->
            <a href="/signup" class="singup w-100 btn btn-l">新規会員登録</a>
          <!--</button>-->
      </main>
@endsection