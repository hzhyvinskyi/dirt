<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    const ALLOWED = 1;
    const DISALLOWED = 0;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function post()
    {
        return $this->belongsTo('App\Post');
    }

    private function allow()
    {
        $this->status = self::ALLOWED;
        $this->save();
    }

    private function disallow()
    {
        $this->status = self::DISALLOWED;
        $this->save();
    }

    public function toggleStatus()
    {
        if ($this->status == 0) {
            return $this->allow();
        } else {
            return $this->disallow();
        }
    }

    /**
     * @throws \Exception
     */
    public function remove()
    {
        $this->delete();
    }
}
