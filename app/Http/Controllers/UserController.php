<?php

namespace App\Http\Controllers;

use App\Events\SendMessage;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    public function index(){
        return User::all();
    }
    public function user(Request $request){
        return $request->user();
    }
    public function users(Request $request){
        return User::all();
    }
    public function getUser($id){
        return User::query()->find($id);
    }
    public function getChat(Request $request, $id){
        $user = $request->user();
        $recipient =  User::query()->find($id);
        if( $request->lastDate != null){
            $chat = Message::query()
                ->where(function ($query) use ($user, $recipient,$request) {
                    return $query
                        ->where('from_id', $user->id)
                        ->where('to_id', $recipient->id)
                        ->where('created_at', '>', $request->lastDate)
                        ->where('created_at', '!=', $request->lastDate);
                })
                ->orWhere(function ($query) use ($user, $recipient, $request) {
                    return $query
                        ->where('to_id', $user->id)
                        ->where('from_id', $recipient->id)
                        ->where('created_at', '>', $request->lastDate)
                        ->where('created_at', '!=', $request->lastDate);
                })
                ->orderBy('updated_at')
                ->get();
        } else{
            $chat = Message::query()
                ->where(function ($query) use ($user, $recipient) {
                    return $query
                        ->where('from_id', $user->id)
                        ->where('to_id', $recipient->id);
                })
                ->orWhere(function ($query) use ($user, $recipient) {
                    return $query
                        ->where('to_id', $user->id)
                        ->where('from_id', $recipient->id);
                })
                ->orderBy('updated_at')
                ->get();
        }
        return $chat;
    }
    public function send(Request $request, $id){
        $user = $request->user();

        $message = Message::create([
            'from_id' => $user->id,
            'to_id' => $id,
            'text' =>  $request->text
        ]);
        event(new SendMessage($message));
        return response()->json(['success' => 'message sent']);
    }
}
