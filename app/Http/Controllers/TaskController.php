<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource with pagination considered.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $total  = Task::count();
        //if no task is found
        if($total == 0)
        {
          return response()->json(['message'=>'No task found','task'=>[], 'total_pages'=>0, 'per_page'=>0, 'page'=>0], 200);
        }

        $per_page = 10;
        $page = 1;

        $query = $request->all();
        if(array_key_exists('per_page', $query))
        {//check if per page is in query string
           $per_page = $query["per_page"];
        }
        if(array_key_exists('page', $query))
        {//check if per page is in query string
           $page = $query["page"];
        }
         $offset = ($page-1)*$per_page;

       
        $total_pages = ceil($total/$per_page);

        $offset = ($page-1)*$per_page;


        $task  = Task::offset($offset)->limit($per_page)->get();
        return response()->json(['task'=>$task, 'total_pages'=>$total_pages, 'per_page'=>$per_page, 'page'=>$page], 200);
    }

    /**
     * Store a newly created task in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255|unique:tasks',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $task = new Task();
        $task->title = $request->title;
        $task->slug =  md5(microtime());
        $task->description = $request->description;
        $task->save();

        return response()->json(['message'=>'task created successfully', 'task'=>$task],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $task = Task::where('id',$id)->first();
        if ($task == NULL) {
            return response()->json(['message'=>'task not found'],404);
        }
        return response()->json(['message'=>'task fetched successfully', 'task'=>$task],200);
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
        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
        ]);

        

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $task->description = $request->description;
        $task->title = $request->title;
        $task->save();
       
        return response()->json(['message'=>'task updated successfully', 'task'=>$task],200);
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
        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        $task->delete();

        return response()->json(['message' => 'Task deleted', 'task'=>NULL], 200);
    }
}
