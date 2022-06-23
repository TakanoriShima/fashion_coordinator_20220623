<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use App\Post;

class ToppagesController extends Controller
{
    // Toppage表示
    public function index()
    {
        // Cookie::queue(Cookie::forget('count'));
        
        $min = 1; //クッキーの有効期限（分）
        
        // クッキーからアクセスカウント値を取得
        $count = Cookie::get('count');
        
        // 初回アクセスならば
        if($count === null){
            $count = 1;
            // クッキーにアクセス回数として１を保存
            Cookie::queue('count', $count, 1);
            // welcome.blade.phpを表示
            return view('welcome');
        }else{ // 2回目以降の訪問ならば
            // 訪問回数を増やして
            $count++;
            // クッキーにアクセス回数を上書き保存
            Cookie::queue('count', $count, $min);
            // ログインにリダイレクト
            return redirect('/login');
            // return view('welcome');
        }
    }
    
    public function top(){
        if(\Auth::user()->role === 1){
            // プロフィールがまだない場合
            if(\Auth::user()->profile()->get()->first() === null){
                return redirect('profiles/create');
            }else{
                $posts = Post::all();
                return view('admin_top', compact('posts'));
            }
            
        }else{
            // viewの呼び出し
            // return view('top');
             // プロフィールがまだない場合
            if(\Auth::user()->profile()->get()->first() === null){
                return redirect('profiles/create');
            }else{
                return view('top');
            }
        }
    }
}
