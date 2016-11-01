<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Todo.
 *
 * @property string $id
 * @property int $user_id
 * @property string $content
 * @property bool $done
 */
class Todo extends Model
{
    public $incrementing = false;

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'done' => 'boolean',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'content',
        'done',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
