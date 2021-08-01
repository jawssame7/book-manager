<?php


namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use App\Models\Book;
use App\Models\Place;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

/**
 * 書籍管理
 * Class BookController
 * @package App\Http\Controllers\admin
 */
class BookController extends Controller
{

    /**
     * 一覧アクション
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        // クエリ取得
        $name = $request->query('name');
        $placeId = $request->query('place_id');
        $empId = $request->query('employee_id');
        $deletedAt = $request->query('deleted_at');

        $sqlQuery = Book::query();

        // join句
        $sqlQuery->leftJoin('places', 'books.place_id', '=', 'places.id');
        $sqlQuery->leftJoin('employees', 'books.employee_id', '=', 'employees.id');
        // selectのフィールド名指定
        $sqlQuery->select('books.*', 'places.name as place_name', 'employees.name as employee_name');

        // 名前
        if (!empty($name)) {
            $sqlQuery->where('books.name', 'like', '%' . $name . '%');
        }
        // 保管場所
        if (!empty($placeId)) {
            $sqlQuery->where('books.place_id', '=', $placeId);
        }
        // 使用者
        if (!empty($empId)) {
            $sqlQuery->where('books.employee_id', '=', $empId);
        }
        // 削除済を含む(soft_delete時)
        if (!empty($deletedAt)) {
            $sqlQuery->withTrashed();
        }
        $books = $sqlQuery->paginate(10);

        // sqlの表示 debug
        // dd($sqlQuery->toSql(), $sqlQuery->getBindings());
        // dd($books);

        // 場所
        $places = Place::get();
        // 使用者
        $employees = Employee::get();
//        dd($employees);

        return view('admin.books.index',
            [
                'pagenate_params' => [
                    'name' => $name,
                    'place_id' => $placeId,
                    'emp_id' => $empId,
                    'deleted_at' => $deletedAt
                ],
                'books' => $books,
                'places' => $places,
                'employees' => $employees

            ]);
    }

    /**
     * 新規作成 アクション
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('admin.books.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'required|max:255',
            'thumbnail' => 'max:255'
        ]);

        $params = $request->all();
        if (!isset($params['thumbnail'])) {
            $params['thumbnail'] = '';
        }
//        // 空値を設定
//        $params['place_id'] = 0;
//        $params['employee_id'] = 0;

        $book = Book::create($params);

        if ($book) {
            return redirect()->action([BookController::class, 'index'])
                ->withSuccess('データを登録しました。');
        } else {
            return redirect()->action([BookController::class, 'index'])
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
        $book = Book::find($id);
        // 場所
        $places = Place::get();
        // 使用者
        $employees = Employee::get();

        // dump($places);
        return view('admin.books.edit', [
            'book' => $book,
            'places' => $places,
            'employees' => $employees
        ]);
    }

    /**
     * ユーザーの更新アクション
     * @param Request $request
     * @param Book $book
     * @return mixed
     */
    public function update(Request $request, Book $book)
    {
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'required|max:255',
            'thumbnail' => 'max:255',
            'place_id' => 'required'
        ]);

        $params = $request->all();
        if (!isset($params['thumbnail'])) {
            $params['thumbnail'] = '';
        }

        $book->update($params);

        return redirect()->action([BookController::class, 'index'])
            ->withSuccess('データを更新しました。');
    }

    /**
     * ユーザーの削除アクション
     * @param Book $book
     * @return mixed
     */
    public function destroy(Book $book)
    {
        $book->delete();

        return redirect()->action([BookController::class, 'index'])
            ->withSuccess('データを削除しました。');
    }

    /**
     * ファイルアップロード ajax 非同期通信
     * @param Request $request
     */
    public function uploadThumbnail(Request $request)
    {
        $data = [];

        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:png,jpg,jpeg|max:2048'
        ]);

        if ($validator->fails()) {
            $data['success'] = false;
            $data['error'] = $validator->errors()->first('file');// Error response
        } else {
            if($request->file('file')) {

                $file = $request->file('file');
                // ファイル名
                $filename = $file->getClientOriginalName();

                // ファイルの拡張子
                $extension = $file->getClientOriginalExtension();

                // File upload location
                $location = 'files';

                // ファイル
                $path = Storage::putFile(config('const.THUMBNAIL_BASE_PATH'), $file);
//                $file->move($location, $filename);

                // File path
//                $filepath = url('files/'.$filename);

                // Response
                $data['success'] = true;
                $data['message'] = 'Uploaded Successfully!';
                $data['filePath'] = Storage::url($path);
                // $pathには、「/public/thumbnails/ファイル名」みたいな値なので
                // ファイル名のみを取り出す
                $data['fileName'] = str_replace(config('const.THUMBNAIL_BASE_PATH').'/', '', $path);;
                $data['extension'] = $extension;
            }else{
                // Response
                $data['success'] = false;
                $data['message'] = 'File not uploaded.';
            }
        }

        // json レスポンス
        return response()->json($data);
    }

    /**
     * ファイル削除 ajax 非同期通信
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function clearThumbnail(Request $request)
    {
        $data = [];

        $validator = Validator::make($request->all(), [
            'fileName' => 'required'
        ]);


        if ($validator->fails()) {
            $data['success'] = false;
            $data['error'] = $validator->errors()->first('file');// Error response
        } else {
            $fileName = $request->input('fileName');
            $path = config('const.THUMBNAIL_BASE_PATH') . $fileName;
            // ファイル
            if (Storage::exists($path)) {
                if (Storage::delete($path)) {
                    $data['success'] = true;
                    $data['message'] = 'delete file Successfully!';
                } else {
                    $data['success'] = false;
                    $data['error'] = 'ファイルの削除に失敗しました。';
                }
            } else {
                $data['success'] = false;
                $data['error'] = 'ファイルが存在しないかすでに削除されています。';
            }

        }

        // json レスポンス
        return response()->json($data);
    }
}
