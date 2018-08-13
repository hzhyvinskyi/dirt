<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    const ADMIN = 10;
    const NORMAL = 0;
    const BANNED = 2;

    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function posts()
    {
        return $this->hasMany('App\Post');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany('App\Comments');
    }

    /**
     * @param $fields
     * @return User
     */
    public static function add($fields)
    {
        $user = new static;
        $user->fill($fields);
        $user->password = bcrypt($fields['password']);
        $user->save();

        return $user;
    }

    public function edit($fields)
    {
        $this->fill($fields);
        $this->password = bcrypt($fields['password']);
        $this->save();
    }

    public function remove()
    {
        Storage::delete('uploads/avatars/' . $this->avatar);
        $this->delete();
    }

    public function uploadAvatar($image)
    {
        if ($image == null) {
            return;
        }
        Storage::delete('uploads/avatars/' . $this->avatar);
        $filename = time() . str_random(10) . '.' . $image->extension;
        $image->saveAs('uploads/avatars/', $filename);
        $this->avatar = $filename;
        $this->save();
    }

    public function getAvatar()
    {
        if ($this->avatar == null) {
            return '/img/no-user-image.jpeg';
        } else {
            return '/uploads/avatars/' . $this->avatar;
        }
    }

    private function makeAdmin()
    {
        $this->status = self::ADMIN;
        $this->save();
    }

    private function makeNormal()
    {
        $this->status = self::NORMAL;
        $this->save();
    }

    private function ban()
    {
        $this->status = self::BANNED;
        $this->save();
    }

    public function toggleStatus($value)
    {
        switch ($value) {
            case 10:
                return $this->makeAdmin();
            case 2:
                return $this->ban();
            default:
                return $this->makeNormal();
        }
    }
}
