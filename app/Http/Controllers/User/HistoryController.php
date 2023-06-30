<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\SentMailLog;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index(){
        $history = SentMailLog::where('user_id',auth()->id())->orderBy('created_at','desc')->get();
        return view('pages.history.index',['history' => $history]);
    }
}
