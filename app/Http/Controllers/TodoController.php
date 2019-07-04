<?php

namespace App\Http\Controllers;

use App\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function index(Request $request){
        $todo = Todo::where('status', 'Y')->orderBy('id', 'desc')->take(10)->get();

        return response()->json([
            'code' => 200,
            'values' => $todo
        ]);
    }

    public function show(Request $request, $id){
        $todo = Todo::where('id', $id)->first();

        return response()->json([
            'code' => 200,
            'values' => $todo
        ]);
    }

    public function store(Request $request){

        $todo = new Todo();
        $todo->title = $request->title;
        $todo->description = $request->description;
        $todo->save();

        return response()->json([
            'code' => 200,
            'message' => "Successfully create todo!",
            'values' => $todo
        ]);
    }

    public function update(Request $request, $id){
        $array = ["Y", "N"];

        if(!in_array($array, $request->status)){
            return response()->json([
                'code' => 400,
                'message' => "Your status should be Y or N!",
            ]);
        }

        $todo = Todo::where('id', $id)->first();
        $todo->title = $request->title;
        $todo->description = $request->description;
        $todo->status = $request->status;
        $todo->save();

        return response()->json([
            'code' => 200,
            'message' => "Successfully update todo!",
            'values' => $todo
        ]);
    }
}
