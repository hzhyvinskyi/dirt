<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    const IS_DRAFT = 0;
    const IS_PUBLIC = 1;

    use Sluggable;

    protected $fillable = ['title', 'body', 'intro'];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

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
    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany('App\Comment');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany('App\Tag');
    }

    /**
     * @param $fields
     * @return Post
     */
    public static function add($fields)
    {
        $post = new static;
        $post->fill($fields);
        $post->save();

        return $post;
    }

    /**
     * @param $fields
     */
    public function edit($fields)
    {
        $this->fill($fields);
        $this->save();
    }

    /**
     * @throws \Exception
     */
    public function remove()
    {
        $this->delete();
    }

    /**
     * @param $image
     */
    public function uploadImage($image)
    {
        if ($image == null) {
            return;
        }
        Storage::delete('uploads/' . $this->image);
        $filename = time() . str_random(10) . '.' . $image->extension();
        $image->saveAs('uploads', $filename);
        $this->image = $filename;
        $this->save();
    }

    /**
     * @return string
     */
    public function getImage()
    {
        if ($this->image == null) {
            return '/img/no-img.jpeg';
        } else {
            return '/uploads/' . $this->image;
        }
    }

    /**
     * @param $id
     */
    public function setCategory($id)
    {
        if ($id == null) {
            return;
        }
        $this->category_id = $id;
        $this->save();
    }

    /**
     * @param array $ids
     */
    public function setTags(array $ids)
    {
        if ($ids == null) {
            return;
        }
        $this->tags()->sync($ids);
    }

    private function setDraft()
    {
        $this->status = self::IS_DRAFT;
        $this->save();
    }

    private function setPublic()
    {
        $this->status = self::IS_PUBLIC;
        $this->save();
    }

    /**
     * @param $value
     */
    public function toggleStatus($value)
    {
        if ($value == null) {
            return $this->setDraft();
        } else {
            return $this->setPublic();
        }
    }

    private function setStandart()
    {
        $this->is_recommended = self::IS_DRAFT;
        $this->save();
    }

    private function setFeatured()
    {
        $this->is_recommended = self::IS_PUBLIC;
        $this->save();
    }

    /**
     * @param $value
     */
    public function toggleRecommended($value)
    {
        if ($value == null) {
            return $this->setStandart();
        } else {
            return $this->setFeatured();
        }
    }
}
