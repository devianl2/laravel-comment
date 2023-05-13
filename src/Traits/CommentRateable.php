<?php

namespace Devianl2\CommentRateable\Traits;

use \Devianl2\RateableComment\Models\Comment;
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
     * @param $parent
     *
     * @return static
     */
    public function comment($data, Model $author)
    {
        return (new Comment())->createComment($this, $data, $author);
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
     * @param int $roundDecimal
     * @param bool $onlyApproved 
     * @return int 
     */
    public function averageRating($roundDecimal= null, $onlyApproved= true)
    {
        $conditions = $onlyApproved ? [['approved', '1']] : [];
        $avgExpression = null;

        if ($roundDecimal) {
            $avgExpression = 'ROUND(AVG(rating), ' . (int)$roundDecimal . ') as averageRating';
        } else {
            $avgExpression = 'AVG(rating) as averageRating';
        }

        return $this->comments()
            ->selectRaw($avgExpression)
            ->where($conditions)
            ->get()
            ->first()
            ->averageRating;
    }

    /**
     * @param bool $onlyApproved 
     * @param bool $paginate 
     * @param int $limit 
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Contracts\Pagination\LengthAwarePaginator 
     */
    public function getComments($onlyApproved = true, $paginate = false, $limit = 10)
    {
        $conditions = $onlyApproved ? ['is_approved' => 1] : [];

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
        $comments = $this->comments()->where(['is_approved' => 0]);

        return $this->getResults($comments, $paginate, $limit);
    }

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