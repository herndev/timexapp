<?php

namespace App\Http\Controllers;

use App\Models\Task;
use GuzzleHttp\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    private $guard = 'api';


    public function __construct()
    {
        $this->middleware('jwt-check');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return DB::table('tasks')
            ->select('id', 'user_id', 'task', 'day', 'initial_time', 'end_time')
            ->paginate(50);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'task' => 'required',
            'day' => 'required',
            'initial_time' => 'required',
            'end_time' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $err = array(
                'task' => $errors->first('task'),
                'day' => $errors->first('day'),
                'initial_time' => $errors->first('initial_time'),
                'end_time' => $errors->first('end_time'),
            );

            return response()->json(array(
                'message' => 'Cannot process request',
                'errors' => $err
            ), 422);
        }

        $task = new Task;
        $task->task = $request->input("task");
        $task->day = $request->input("day");
        $task->initial_time = $request->input("initial_time");
        $task->end_time = $request->input("end_time");
        $task->user_id = auth($this->guard)->user()->id;
        $task->save();

        return response()->json(array(
            "message" => "Task created Successful",
            "task" => $task
        ), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $task = Task::find($id);
        if ($task == NULL) {
            return response()->json(array(
                "message" => "Task not found!",
            ), 404);
        }

        return response()->json(array($task), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $task = Task::find($id);
        if ($task == NULL) {
            return response()->json(array(
                "message" => "Task not found!",
            ), 404);
        }

        if ($request->has('task'))
            $task->task = $request->input('task');
        if ($request->has('day'))
            $task->day = $request->input('day');
        if ($request->has('initial_time'))
            $task->initial_time = $request->input('initial_time');
        if ($request->has('end_time'))
            $task->end_time = $request->input('end_time');
        $task->save();

        return response()->json(array(
            "message" => "Task is updated!",
        ));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task = Task::find($id);
        if ($task == NULL) {
            return response()->json(array(
                "message" => "Task not found!",
            ), 404);
        }

        $task->delete();

        return response()->json(array(
            "message" => "Task is deleted!",
        ));
    }



    // $users = User::where('status', 'VIP')->get();

    // $users->toQuery()->update([
    // 'status' => 'Administrator',
    // ]);
}
