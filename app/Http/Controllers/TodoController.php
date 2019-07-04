<?php

namespace App\Http\Controllers;

use App\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{

    /**
     * @OA\OpenApi(
     *     @OA\Info(
     *         version="1.0.0",
     *         title="Swagger",
     *         description="This is test server",
     *         @OA\Contact(
     *             email="kiddydhana@gmail.com"
     *         ),
     *         @OA\License(
     *             name="GLU",
     *         )
     *     ),
     *     @OA\Server(
     *         description="Todo Host",
     *         url="https://apitodo.hudya.xyz"
     *     ),
     * )
     */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * @OA\Get(
     *     path="/todo",
     *     tags={"Todo"},
     *     description="Show the last 10 items of Todo",
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *     ),
     * )
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request){
        $todo = Todo::where('status', 'Y')->orderBy('id', 'desc')->take(10)->get();

        return response()->json([
            'code' => 200,
            'values' => $todo
        ], 200);
    }

    /**
     * @OA\Get(
     *     path="/todo/{id}",
     *     summary="get Todo by Id",
     *     description="Returns a single todo",
     *     tags={"Todo"},
     *     @OA\Parameter(
     *         description="ID of todo to return",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(
     *           type="integer",
     *           format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="return Todo single",
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Invalid ID"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Todo Not Found"
     *     ),
     *     security={
     *       {"api_key": {}}
     *     }
     * )
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id){
        if(!$id){
            return response()->json([
                'code' => 400,
                'message' => "Invalid ID!"
            ], 400);
        }


        $todo = Todo::where('id', $id)->first();

        if(!$todo){
            return response()->json([
                'code' => 404,
                'message' => "Todo Not Found!"
            ], 404);
        }

        return response()->json([
            'code' => 200,
            'values' => $todo
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/todo",
     *     tags={"Todo"},
     *     summary="Add a new todo",
     *     description="",
     *     @OA\Parameter(
     *      in="body",
     *      name="body",
     *      description="list of parameter needed",
     *      required=true,
     *      @OA\Schema(
     *          @OA\Property(property="title", type="string"),
     *          @OA\Property(property="description", type="string"),
     *      )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *     ),
     * )
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request){

        $todo = new Todo();
        $todo->title = $request->title;
        $todo->description = $request->description;
        $todo->save();

        return response()->json([
            'code' => 200,
            'message' => "Successfully create todo!",
            'values' => $todo
        ], 200);
    }

    /**
     * @OA\Put(
     *     path="/todo/{id}",
     *     tags={"Todo"},
     *     summary="Update a todo",
     *     description="",
     *     @OA\Parameter(
     *         description="ID of todo to return",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(
     *           type="integer",
     *           format="int64"
     *         )
     *     ),
     *     @OA\Parameter(
     *     in="body",
     *     name="body",
     *     description="list of parameter needed",
     *     required=true,
     *     @OA\Schema(
     *     @OA\Property(property="title", type="string"),
     *     @OA\Property(property="description", type="string"),
     *     @OA\Property(property="status", type="string"),
     *     )
     *   ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Status should be Y or N",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Id Not Found",
     *     ),
     * )
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id){
        $array = ["Y", "N"];

        if(!in_array($array, $request->status)){
            return response()->json([
                'code' => 400,
                'message' => "Your status should be Y or N!",
            ], 400);
        }

        $todo = Todo::where('id', $id)->first();

        if(!$todo){
            return response()->json([
                'code' => 404,
                'message' => "Todo Not Found!",
            ], 404);
        }

        $todo->title = $request->title;
        $todo->description = $request->description;
        $todo->status = $request->status;
        $todo->save();

        return response()->json([
            'code' => 200,
            'message' => "Successfully update todo!",
            'values' => $todo
        ], 200);
    }
}
