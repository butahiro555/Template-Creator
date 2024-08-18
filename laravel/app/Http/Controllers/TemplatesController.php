<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Template;
use Auth;

class TemplatesController extends Controller
{   
    // トップページ
    public function index()
    {   
        $user = Auth::user();
        
        return view('templates.index', ['user' => $user]);
    }
    
    // 作成機能
    public function store(Request $request)
    {   
        // 文字数制限オーバー時のエラーを回避するためのvalidate
        $this->validate($request, [
            'title' => 'required|max:20',
            'content' => 'required|max:191',
        ]);
        
        $user = Auth::user();
        $templates = new Template;
        
        $request->user()->templates()->create([
            'title' => $request->title,
            'content' => $request->content,
        ]);
        
        // Saveボタンが押されたら、Template listに遷移させる
        return redirect()->route('templates.show');
    }
    
    // 上書き保存機能
    public function update(Request $request, $id)
    {   
        $this->validate($request, [
            'title' => 'required|max:20',
            'content' => 'required|max:191',
        ]);
        
        $user = Auth::user();
        
        // Updateするテンプレートを見つけてくる
        $templates = Template::find($id);
        
        // saveを使うことでupdated_atカラムも自動的に更新してくれる
        $templates->title = $request->title;
        $templates->content = $request->content;
        $templates->save();
        
        return redirect()->route('templates.show');
    }
    
    // テンプレートの一覧表示ページ
    public function show()
    {   
        $user = Auth::user();
        
        // 他のユーザーの一覧は見れないようにする
        if($user->id == Auth::id()) {
            
        $templates = $user->templates()->sortable()->paginate(10);
        
        return view('templates.show', ['templates' => $templates]);
        }
    }
    
    // 検索機能
    public function search(Request $request)
    {   
        $user = Auth::user();
        
        // ログインユーザーのテンプレートのみ取得する($query = Template::query()だと、他のユーザーのテンプレートまで引っ張ってきてしまう)
        $template = $user->templates();
        
        // 検索ワードと同じtitleをとってくる
        $templates = $template->where('title', '=', $request->keyword)->sortable()->paginate(10);
        
        return view('templates.show', ['templates' => $templates]);
    }
    
    // 削除機能
    public function destroy($id)
    {
        $templates = Template::find($id);
        $templates->delete();
        
        return redirect()->route('templates.show');
    }
}

