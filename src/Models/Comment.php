<?php

namespace Devianl2\CommentRateable\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    /**
     * @var string
     */
    protected $table = 'comments';

    /**
     * @var array
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function commentable()
    {
        return $this->morphTo(__FUNCTION__, 'commentable_type', 'commentable_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function author()
    {
        return $this->morphTo(__FUNCTION__);
    }

    /**
     * @param Model $commentable
     * @param $data
     * @param Model $author
     *
     * @return static
     */
    public function createComment(Model $commentable, $data, Model $author)
    {
        $comment = new static();
        $comment->fill(array_merge($data, [
            'author_id' => $author->id,
            'author_type' => $author->getMorphClass(),
        ]));

        $commentable->comments()->save($comment);

        return $comment;
    }
}