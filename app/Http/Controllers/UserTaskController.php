<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserTaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        //
        $user = User::find($id);
        if ($user == NULL) {
            return response()->json(array(
                "message" => "User not found!",
            ), 404);
        }

        return $user->tasks()
            ->select('id', 'user_id', 'task', 'day', 'initial_time', 'end_time')
            ->get();
    }
}
