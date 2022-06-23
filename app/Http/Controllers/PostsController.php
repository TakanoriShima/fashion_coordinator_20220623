<?php

namespace App\Http\Controllers;

use App\Post;
use App\Room;
use App\Chat;
use Illuminate\Http\Request;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }
    
    public function myposts(){
        $posts = \Auth::user()->posts()->get();
        // dd($posts);
        return view('posts.myposts', compact('posts'));
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // 空のPostモデル作成
        $post = new Post();
        // view の呼び出し
        return view('posts.create', compact('post'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validation
        //for image ref) https://qiita.com/maejima_f/items/7691aa9385970ba7e3ed
        $this->validate($request, [
            'title' => 'required',
            'content' => 'required',
            'style' => 'required',
            'image' => [
                'required',
                'file',
                'mimes:jpeg,jpg,png'
            ]
        ]);
        
        // 入力情報の取得
        $title = $request->input('title');
        $content = $request->input('content');
        $style = $request->input('style');
        $file =  $request->image;
        
        // 画像のアップロード
        // https://qiita.com/ryo-program/items/35bbe8fc3c5da1993366
        if($file){
            // 現在時刻ともともとのファイル名を組み合わせてランダムなファイル名作成
            $image = time() . $file->getClientOriginalName();
            // アップロードするフォルダ名取得
            $target_path = public_path('uploads/');
            // アップロード処理
            $file->move($target_path, $image);
        }else{
            // 画像が選択されていなければ空文字をセット
            $image = '';
        }
        
        
        // 入力情報をもとに新しいインスタンス作成
        \Auth::user()->posts()->create(['title' => $title, 'content' => $content, 'style' => $style, 'image' => $image]);
        
        // トップページへリダイレクト
        return redirect('/top')->with('flash_message', '投稿を完了しました。');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        // その投稿に紐づいたチャット一覧を取得
        // お店側
        if(\Auth::user()->role === 1){
            $room = Room::where('post_id', $post->id)->where('user_id', \Auth::id())->get()->first();
            $users = array();
            $rooms = array();
        }else{ // ユーザー側
            // その投稿に回答したお店一覧
            $users = $users = $post->room_users()->get();
            $rooms = $post->rooms()->get();
            $room = null;
        }
        // dd($post);
        return view('posts.show', compact('post', 'room', 'rooms', 'users'));
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        // 注目している投稿がログインしている人のものならば
        if($post->user->id === \Auth::id()){
            return view('posts.edit', compact('post'));
        }else{
            return redirect('/top');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        // 注目している投稿がログインしているユーザーのものならば
        if($post->user->id === \Auth::id()){
            // validation
            //for image ref) https://qiita.com/maejima_f/items/7691aa9385970ba7e3ed
            $this->validate($request, [
                'title' => 'required',
                'content' => 'required',
                'style' => 'required',
                'image' => [
                    'file',
                    'mimes:jpeg,jpg,png'
                ]
            ]);
            
            // 入力情報の取得
            $title = $request->input('title');
            $content = $request->input('content');
            $style = $request->input('style');
            $file =  $request->image;
            
            // 画像アップロード
            // https://qiita.com/ryo-program/items/35bbe8fc3c5da1993366
            if($file){
                // 現在時刻ともともとのファイル名を組み合わせてランダムなファイル名作成
                $image = time() . $file->getClientOriginalName();
                // アップロードするフォルダ名取得
                $target_path = public_path('uploads/');
                // アップロード処理
                $file->move($target_path, $image);
            }else{
                // 画像が選択されていなければ、もとの画像名のまま
                $image = $post->image;
            }
            
            
            // 入力情報をもとにインスタンス情報の更新
            $post->title = $title;
            $post->content = $content;
            $post->style = $style;
            $post->image = $image;
    
            // データベース更新
            $post->save();
            
            // view の呼び出し
            return redirect('/top')->with('flash_message', '投稿ID: ' . $post->id . 'の画像投稿を更新しました。');
        
        }else{
            return redirect('/top');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        // 注目している投稿がログインしているユーザーのものならば
        if($post->user->id === \Auth::id()){
            // データベースから削除
            $post->delete();
            // リダイレクト
            return redirect('/top')->with('flash_message', '投稿id: ' . $post->id . 'の画像投稿を削除しました。');
        }else{
            return redirect('/top');
        }
    }
    
    // キーワード検索
    public function search(Request $request){
        
        // validation
        $this->validate($request, ['keyword' => 'required']);
        
        // 入力された検索キーワードを取得
        $keyword = $request->input('keyword');

        // 検索
        $posts = Post::where('title','like', '%' . $keyword . '%')->orWhere('content', 'like', '%' . $keyword . '%')->paginate(10);
        // フラッシュメッセージのセット
        $flash_message = '検索キーワード: 『' . $keyword . '』に' . $posts->count() . '件ヒットしました';
        
        // view の呼び出し
        return view('top', compact('posts', 'flash_message'));

    }
}
