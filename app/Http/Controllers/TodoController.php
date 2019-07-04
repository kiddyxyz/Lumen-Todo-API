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
     *         description="This is a sample server Petstore server.  You can find out more about Swagger at [http://swagger.io](http://swagger.io) or on [irc.freenode.net, #swagger](http://swagger.io/irc/).  For this sample, you can use the api key `special-key` to test the authorization filters.",
     *         termsOfService="http://swagger.io/terms/",
     *         @OA\Contact(
     *             email="apiteam@swagger.io"
     *         ),
     *         @OA\License(
     *             name="Apache 2.0",
     *             url="http://www.apache.org/licenses/LICENSE-2.0.html"
     *         )
     *     ),
     *     @OA\Server(
     *         description="OpenApi host",
     *         url="https://petstore.swagger.io/v3"
     *     ),
     *     @OA\ExternalDocumentation(
     *         description="Find out more about Swagger",
     *         url="http://swagger.io"
     *     )
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
     */
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
