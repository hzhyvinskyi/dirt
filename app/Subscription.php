<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    /**
     * @param $email
     * @return Subscription
     */
    public static function add($email)
    {
        $sub = new static;
        $sub->email = $email;
        $sub->token = str_random(32);
        $sub->save();

        return $sub;
    }

    /**
     * @throws \Exception
     */
    public function remove()
    {
        $this->delete();
    }
}
