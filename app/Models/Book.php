<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'thumbnail',
        'place_id',
        'employee_id'
    ];

    /**
     * 単テーブルへのアクセス
     * @param string $order
     * @return mixed
     */
    public function getBooksOrderBy($order = 'ASC')
    {
        return Book::where('deleted_at', null)->orderBy('name', $order)->get();
    }

    // /**
    //  * 関連テーブル込ですべて取得
    //  * @return mixed
    //  */
    // public function bookAll()
    // {
    //     $query = Book::all();

    //     return $query->get();
    // }

    public function queryBuilderJoin()
    {
        $query = Book::join('places', 'books.place_id', 'places.id')->join('users', 'books.user_id', 'users.id');
        dump($query->toSql(), $query->getBindings());

        return $query->get();
    }

    //　belongsTo設定

    /**
     * 保管場所テーブルとの紐付け
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function place()
    {
        return $this->belongsTo(Place::class);
    }

    /**
     * ユーザーテーブルとの紐付け
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        //return $this->belongsTo(User::class);
        return $this->belongsTo(User::class)->withDefault([
            'name' => 'なし'
        ]);
    }
}
