<?php

namespace App\Services;

use App\Models\User;

class GuestService
{
    public function createGuest($guest_id)
    {
        $user = User::where('guest_id', $guest_id)->first();
        
        if($user){
            return $user;
        }

        User::create([
            'name' => 'guest',
            'password' => $guest_id,
            'guest_id' => $guest_id,
            'is_guest' => true
        ]);

        return $user = User::where('guest_id', $guest_id)->first();
    }

    public static function getGuest($guestID){
        $user = auth()->user();
        if(!$user){
            $user = User::where('guest_id', $guestID)->firstOrFail();
        }
        return $user;
    }
    

}

?>