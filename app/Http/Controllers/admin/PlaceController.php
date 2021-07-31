<?php


namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePlaceRequest;
use App\Http\Requests\UpdatePlaceRequest;
use Illuminate\Pagination\Paginator;
use App\Models\Place;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * 場所管理
 * Class PlaceController
 * @package App\Http\Controllers\admin
 */
class PlaceController extends Controller
{

    /**
     * 保管場所一覧アクション
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        // リクエスト取得
        // 受け取りたいパラメータを指定して、連想配列で受け取る場合
        $attributes = $request->only(['search_name', 'deleted_at']);

        $places = $this->_getPlaces($attributes);


        return view('admin.places.index', ['pagenate_params' => $attributes, 'places' => $places]);
    }

    /**
     * 保管場所の作成 (FormRequestクラスを作成してバリデーション)
     * @param StorePlaceRequest $request フォームリクエスト
     * @return mixed
     */
    public function store(StorePlaceRequest $request)
    {
        // フィールドのユニークチェック (placesテーブル内の有効な名前)
//        $request->validate([
//            'name' => ['required', 'max:255', Rule::unique('places', 'name')->whereNull('deleted_at')]
//        ]);

        // バリデーション済みデータの取得
        $validated = $request->validated();

        // リクエスト取得
        // 受け取りたいパラメータを指定して、連想配列で受け取る場合
        // $attributes = $request->only(['search_name', 'deleted_at']);
        // $places = $this->_getPlaces($attributes);

        $place = Place::create($validated);

        if ($place) {
            return redirect()->action([PlaceController::class, 'index'])
                ->withSuccess('データを登録しました。');
        } else {
            return redirect()->action([PlaceController::class, 'index'])
                ->withError('データの登録に失敗しました。');
        }

        //return view('admin.places.index', ['pagenate_params' => $attributes, 'places' => $places]);
    }

    /**
     * 保管場所更新アクション
     * @param UpdatePlaceRequest $request
     * @return mixed
     */
    public function update(UpdatePlaceRequest $request)
    {

        // バリデーション済みデータの取得
        $validated = $request->validated();

        $attributes = $request->only(['id', 'name']);
        $place = Place::find($attributes['id']);

        if ($place) {
            $place->update($validated);

            return redirect()->action([PlaceController::class, 'index'])
                ->withSuccess('データを更新しました。');
        } else {
            return redirect()->action([PlaceController::class, 'index'])
                ->withError('データの更新に失敗しました。');
        }

    }

    /**
     * 保管場所削除アクション
     * @param Place $place
     * @return mixed
     */
    public function destroy(Place $place)
    {
        $place->delete();

        return redirect()->action([PlaceController::class, 'index'])
            ->withSuccess('データを削除しました。');
    }


    /**
     * 検索結果の一覧を返す
     * @param array $attributes
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    private function _getPlaces($attributes = [])
    {
        // クエリビルダ
        $sqlQuery = Place::query();
        if (!empty($attributes['search_name'])) {
            $sqlQuery->where('name', 'like', '%' . $attributes['search_name'] . '%');
        }
        // 削除済を含む(soft_delete時)
        if (!empty($attributes['deleted_at'])) {
            $sqlQuery->withTrashed();
        }
        return $sqlQuery->get();
    }
}
