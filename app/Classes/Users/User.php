<?php

namespace App\Classes\Users;

use App\Classes\Constants\AlertsMessages;
use App\Models\UserAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use \App\Models\User as UserModel;
use Illuminate\Support\Facades\Mail;

class User {

    public function createUser($data) {
        $user = UserModel::findOrCreate($data->get('email'));
        if(!$user){
            $user = new UserModel();
            $user->first_name = $data->get('firstName');
            $user->last_name = $data->get('lastName') ?? '';
            $user->phone = $data->get('phone') ?? '';
            $user->password = Hash::make($data->get('password'));
            $user->role_id = $data->get('roleId');
            $user->email = $data->get('email');
            $user->status = 1;
            $user->save();

            //Создаем связку между юзерам и его досутпами
            $this->makeBunchUserCategory($user->id,$data->get('categories'));

            $this->sendPasswordToMail([
                'email'    => $data->get('email'),
                'password' => $data->get('password'),
            ]);
            $message = AlertsMessages::NEW_USER_CREATED;
        }else{
            $message = AlertsMessages::USER_ALREADY_CREATED;
        }

        return collect([
            'user' => $user,
            'message' => $message,
        ]);
    }

    public function makeBunchUserCategory($userId, $categories) {
        $categoriesByUsers = [];
        foreach($categories as $category){
            $categoriesByUsers[] = [
                'user_id'     => $userId,
                'category_id' => $category,
                'created_at'  => now(),
                'updated_at'  => now(),
            ];
        }

        return UserAccess::insert($categoriesByUsers);
    }

    public function sendPasswordToMail($mailData) {
        Mail::send('email.new_user_password', $mailData, function ($message) use ($mailData) {
            $message->to($mailData["email"], $mailData["email"])
                    ->subject('Вас добавили в приложение');
        });
    }
}
