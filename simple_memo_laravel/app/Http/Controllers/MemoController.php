<?php

namespace App\Http\Controllers;

use App\Models\Memo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Memo Controller
 */
class MemoController extends Controller
{
    /**
     * メモ画面表示
     *
     * @return void
     */
    public function index()
    {
        $memos = Memo::where('user_id', Auth::id())->orderBy('updated_at', 'desc')->get();

        return view('memo', [
            'name'        => $this->getLoginUserName(),
            'memos'       => $memos,
            'select_memo' => session()->get('select_memo')
        ]);
    }

    /**
     * メモを追加する
     *
     * @return void
     */
    public function add()
    {
        Memo::create([
            'user_id' => Auth::id(),
            'title'   => '新規メモ',
            'content' => '',
        ]);

        return redirect()->route('memo.index');
    }

    /**
     * メモを選択する
     *
     * @param Request $request
     * @return void
     */
    public function select(Request $request)
    {
        $memo = Memo::find($request->id);
        session()->put('select_memo', $memo);

        return redirect()->route('memo.index');
    }

    /**
     * メモを更新する
     *
     * @param Request $request
     * @return void
     */
    public function update(Request $request)
    {
        $memo = Memo::find($request->edit_id);
        $memo->title = $request->edit_title;
        $memo->content = $request->edit_content;

        if ($memo->update()) {
            session()->put('select_memo', $memo);
        } else {
            session()->remove('select_memo');
        }

        return redirect()->route('memo.index');
    }

    /**
     * メモを削除する
     *
     * @param Request $request
     * @return void
     */
    public function delete(Request $request)
    {
        Memo::find($request->edit_id)->delete();
        session()->remove('select_memo');

        return redirect()->route('memo.index');
    }

    /**
     * ログインユーザ名を返却する
     *
     * @return void
     */
    private function getLoginUserName()
    {
        $user = Auth::user();

        $name = '';
        if ($user) {
            if (7 < mb_strlen($user->name)) {
                $name = mb_substr($user->name, 0, 7) . "...";
            } else {
                $name = $user->name;
            }
        }

        return $name;
    }
}
