<?php

namespace App\Services;

use App\Models\User;
use App\Services\Contracts\ProfileContract;

class ProfileService implements ProfileContract
{

    /**
     * @return array
     */
    public function getProfile(): array
    {
        $user = User::where('id',auth()->id())->with('posts')->first();
        return ['user' => $user];
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function getPublicProfile($id)
    {
        if(auth()->check() && $id == auth()->id()){
            return redirect()->route('profile');
        }
        return view('profile', ['user' => User::findOrFail($id)]);
    }
}
