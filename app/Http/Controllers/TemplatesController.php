<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Template;
use Auth;

class TemplatesController extends Controller
{   
    // 初期化するための変数を準備
    protected $validationsController;

    // コンストラクタでValidationsControllerのインスタンスを受け取り、プロパティに代入
    public function __construct(ValidationsController $validationsController)
    {
        // 受け取ったValidationsControllerインスタンスをプロパティに代入
        $this->validationsController = $validationsController;
    }

    // トップページ
    public function index()
    {   
        $user = Auth::user();
        
        return view('templates.index', ['user' => $user]);
    }
    
    // 作成機能
    public function store(Request $request)
    {   
        // バリデーションチェック
        $this->validationsController->validateTemplate($request);
        
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
        // バリデーションチェック
        $this->validationsController->validateTemplate($request);

        // Templateが見つからない場合は404エラーを返す
        $template = Template::findOrFail($id);
        
        $template->title = $request->title;
        $template->content = $request->content;
        $template->save();
        
        return redirect()->route('templates.show');
    }    
    
    // テンプレートの一覧表示ページ
    public function show(Request $request)
    {   
        $user = Auth::user();
        
        // 他のユーザーの一覧は見れないようにする
        if ($user->id != Auth::id()) {
            return redirect()->route('home')->withErrors('Unauthorized access.');
        }
        
        $sortColumn = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');
        $template = $user->templates()
                            ->orderBy($sortColumn, $sortDirection)
                            ->paginate(5);

        return view('templates.show', ['templates' => $template]);
    }

    // 削除機能
    public function destroy($id)
    {
		$template = Template::find($id);
		
		if (!$template) {
        		return redirect()->route('templates.show')->withErrors(['text' => trans('error_message.template_not_found')]);
    }
		
		$template->delete();
        
        return redirect()->route('templates.show');
    }

    // 検索機能
    public function search(Request $request)
    {
        // 検索ワードと並べ替えの条件を取得
        $keyword = $request->input('keyword');
        $sortColumn = $request->input('sort', 'created_at'); // デフォルトの並べ替え列
        $sortDirection = $request->input('direction', 'asc'); // デフォルトの並べ替え方向
    
        // 検索条件にマッチするテンプレートを取得
        $template = Template::where('title', 'like', '%' . $keyword . '%')
            ->orderBy($sortColumn, $sortDirection)
            ->paginate(5);
    
        // 検索結果が見つからなかった場合のエラーハンドリング
        if (count($template) === 0) {
            return redirect()->route('templates.show')->withErrors(['keyword' => trans('error_message.template_not_found')]);
        }
    
        // ビューに検索結果を渡す
        return view('templates.show', ['templates' => $template]);
    }
}