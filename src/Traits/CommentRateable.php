<?php

namespace Devianl2\CommentRateable\Traits;

use Devianl2\CommentRateable\Models\Comment;
use Illuminate\Database\Eloquent\Model;

trait CommentRateable
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * @param $data
     * @param $author
     *
     * @return static
     */
    public function comment($data, Model $author)
    {
        return (new Comment())->createComment($this, $data, $author);
    }

    /**
     * @param int $commentId
     * @param array $data
     * @return Comment
     */
    public function updateComment($commentId, $data)
    {
        $comment = $this->comments()->find($commentId);
        $comment->update($data);
        return $comment;
    }

    /**
     * @param bool $onlyApproved
     * @return int
     */
    public function countComments($onlyApproved = true)
    {
        if ($onlyApproved) {
            return $this->comments()->where('is_approved', 1)->count();
        }

        return $this->comments()->count();
    }

    /**
     * @param bool $onlyApproved
     * @param int|null $roundDecimal
     * @return int|null
     */
    public function averageRating($onlyApproved= true, $roundDecimal= null)
    {
        $defaultRating = config('comment.enable_default_rating') ? config('comment.default_rating', 5) : null;
        $conditions = $onlyApproved ? [['is_approved', 1]] : [];
        $avgExpression = null;

        if ($roundDecimal) {
            $avgExpression = 'ROUND(AVG(rating), ' . (int)$roundDecimal . ') as averageRating';
        } else {
            $avgExpression = 'AVG(rating) as averageRating';
        }

        $averageRating = $this->comments()
            ->selectRaw($avgExpression)
            ->where($conditions)
            ->get()
            ->first()
            ->averageRating;

        return $averageRating ? $averageRating : $defaultRating;
    }

    /**
     * @param bool $onlyApproved
     * @param bool $paginate
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getComments($onlyApproved = true, $paginate = false, $limit = 10)
    {
        $conditions = $onlyApproved ? [['is_approved', 1]] : [];

        $comments = $this->comments()->where($conditions);

        return $this->getResults($comments, $paginate, $limit);
    }

    /**
     * @param bool $onlyApproved
     * @param bool $paginate
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getRejectedComments($paginate = false, $limit = 10)
    {
        $comments = $this->comments()->where('is_approved', 0);

        return $this->getResults($comments, $paginate, $limit);
    }

    /**
     * @param mixed $comments 
     * @param bool $paginate 
     * @param int $limit 
     * @return mixed 
     */
    protected function getResults($comments, $paginate = false, $limit = 10)
    {
        if (!$paginate) {
            // limit 0 means no limit for comments
            if ($limit > 0) {
                $comments = $comments->limit($limit);
            }
            return $comments->get();
        } else {
            // Paginating comments should have a limit of 10 comments per page by default
            $limit = $limit > 0 ? $limit : 10;
            return $comments->paginate($limit);
        }
    }
}
