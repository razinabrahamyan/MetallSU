<?php

namespace App\Http\Controllers\user;

use App\Classes\Constants\AlertsMessages;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Role;
use App\Models\User;
use App\Models\UserAccess;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use \App\Classes\Constants\Role as RoleConstants;
class UserController extends Controller {
    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function create(Request $request) {
        if(!empty($request->firstName) && !empty($request->email) && !empty($request->roleId)){
            //Создаем юзера
            $userHandler = new \App\Classes\Users\User();
            $createUser = $userHandler->createUser(collect([
                "firstName"  => $request->firstName,
                "lastName"   => $request->lastName ?? '',
                "phone"      => $request->phone ?? '',
                "password"   => Str::random($length = 10),
                "roleId"     => $request->roleId,
                "email"      => $request->email,
                "status"     => 1,
                "categories" => $request->categories,
            ]));

            $saveStatusMessage = $createUser->get('message');
        }else{
            $saveStatusMessage = AlertsMessages::NOT_ALL_PARAMS;
        }

        session()->flash('success', $saveStatusMessage);
        return redirect()->route('index');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Request $request) {
        $destinationPath = storage_path('/app/public/user/images/avatar');
        $image_name = Auth::user()->avatar;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image_name = time().'_'.$image->getClientOriginalName();
            $image->move($destinationPath, $image_name);
        }
        $user = User::find($request->id);
        $user->first_name = $request->firstName;
        $user->last_name = $request->lastName ?? '';
        $user->phone = $request->phone ?? '';
        $user->role_id = $request->roleId;
        $user->email = $request->email;
        $user->status = ($request->status == 'on') ? '1' : '0';
        $user->avatar = $image_name;
        $user->save();
        return back();
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function new(Request $request) {
        $roles = Role::all();
        $categoires = Category::where('user_id', auth()->id())
                              ->get();
        return view('pages.user.newUser')->with([
            'roles'          => $roles,
            'userCategories' => $categoires,
        ]);
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function settings(Request $request) {
        $categories = Category::where('user_id', auth()->id())
                              ->get();

        return view('pages.user.settings')->with([
            'roles'      => Role::all(),
            'user'       => auth()->user(),
            'categories' => $categories,
        ]);
    }

    public function list(){
        return view('pages.user.list')->with([
            'users' => User::where('role_id','!=',1)->get(),
            'roles' => Role::all(),
        ]);
    }
}
