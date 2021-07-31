<?php


namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use App\Models\Employee;
use Illuminate\Http\Request;

/**
 * ユーザー管理
 * Class UserController
 * @package App\Http\Controllers\admin
 */
class EmployeeController extends Controller
{

    /**
     * ユーザー一覧アクション
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        // リクエスト取得
        // 受け取りたいパラメータを指定して、連想配列で受け取る場合
        // $attributes = $request->only(['name']);
        // クエリ文字列からのみ取得したい場合
        $name = $request->query('name');
        $deletedAt = $request->query('deleted_at');
        // 動的プロパティを使用すると、先にリクエストパラメータの中で名前が一致するものを探し、なければルーティングのパラメータを探しに行く
        // $name = $request->name;
        // inputを使用すると、リクエストパラメータを1つずつ取得
        // $name = $request->input('name');
        // 受け取りたくないパラメータを指定して、連想配列で受け取る場合
        // $attributes = $request->except(['name']);
        // 全てまとめてリクエストパラメータを受け取っちゃう場合
        // $attributes = $request->all();


//        if (!empty($name)) {
//            $employee = Employee::where('name', 'like', '%' . $name . '%')->paginate(1);
//        } else {
//            $employees = Employee::paginate(1);
//        }


        $sqlQuery = Employee::query();
        if (!empty($name)) {
            $sqlQuery->where('name', 'like', '%' . $name . '%');
        }
        // 削除済を含む(soft_delete時)
        if (!empty($deletedAt)) {
            $sqlQuery->withTrashed();
        }
        $employees = $sqlQuery->paginate(10);

        // return view('admin/users/index', compact('users'));
        // ↑でもOK


        return view('admin.employees.index',
            ['pagenate_params' => [
                'name' => $name,
                'deleted_at' => $deletedAt]
            , 'users' => $employees]);
    }

    /**
     * 新規作成アクション
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {

        return view('admin.employees.create');
    }

    /**
     * ユーザーの作成アクション
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {

        // 以下の実装であれば、遷移先とか指定しなくても
        // バリデーション後、画面遷移する
//        $request->validate([
//            'name' => ['required', 'max:255'],
//            'email' => ['required', 'email', 'max:255']
//        ]);

        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|max:255'
        ]);

        $employee = Employee::create($request->all());

        if ($employee) {
            return redirect()->action([EmployeeController::class, 'index'])
                ->withSuccess('データを登録しました。');
        } else {
            return redirect()->action([EmployeeController::class, 'index'])
                ->withError('データの登録に失敗しました。');
        }

    }

    /**
     * ユーザーの編集アクション
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $employee = Employee::find($id);
        return view('admin.employees.edit', ['user' => $employee]);
    }

    /**
     * ユーザーの更新アクション
     * @param Request $request
     * @param User $user
     * @return mixed
     */
    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|max:255'
        ]);

        $employee->update($request->all());

        return redirect()->action([EmployeeController::class, 'index'])
            ->withSuccess('データを更新しました。');
    }

    /**
     * ユーザーの削除アクション
     * @param User $user
     * @return mixed
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();

        return redirect()->action([EmployeeController::class, 'index'])
            ->withSuccess('データを削除しました。');
    }

}
