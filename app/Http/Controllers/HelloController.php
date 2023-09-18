<?php

namespace App\Http\Controllers;

use App\Models\Hello;
use Illuminate\Http\Request;
use App\Helpers\JsonResponse;
use Illuminate\Support\Facades\Validator;

class HelloController extends Controller
{

    use JsonResponse;


    /**
     * @OA\Get(
     * path="/api/hellos",
     * operationId="List",
     * tags={"Hello APIs"},
     * summary="List all Hellos",
     * security={{ "apiAuth": {} }},
     * description="This will display all the Hellos",
     *      @OA\Response(
     *          response=200,
     *          description="List Fetch Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function index()
    {
        $hellos = Hello::select('id','name', 'order')->orderBy('order')->get();

        return $this->setSuccess( 'Hello list fetched. ', [ 'data' => $hellos->toArray() ] );
    }

    /**
     * @OA\Post(
     * path="/api/hello/create",
     * operationId="Create",
     * tags={"Hello APIs"},
     * summary="Create a new Hello",
     * security={{ "apiAuth": {} }},
     * description="This will create a new Hello",
     *       @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"name"},
     *               @OA\Property(property="name", type="text")
     *            ),
     *        ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Hello created Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function create( Request $request )
    {
       try {
           $validator = Validator::make( $request->all(), [
               'name' => 'required:string|unique:hellos',
               'order' => 'sometimes|int'
           ]);

           if( $validator->fails() ) {
               return $this->setError( 'Validation Error: ' . $validator->messages()->first());
           }

           $newHello = Hello::create([
               'name' => $request->name,
               'order' => $request->order ?? null
           ]);

           return $this->setSuccess( 'The new Hello created successfully.', $newHello->toArray() );
       }
       catch (\Throwable $exception ) {
           return $this->setError( $exception->getMessage() );
       }
    }

    /**
     * @OA\Post(
     * path="/api/hello/update",
     * operationId="Update",
     * tags={"Hello APIs"},
     * summary="Update an existing Hello by Id",
     * security={{ "apiAuth": {} }},
     * description="This will update existing Hello",
     *       @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"name"},
     *               @OA\Property(property="name", type="text"),
     *               @OA\Property(property="id", type="int")
     *            ),
     *        ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Hello updated Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function update(Request $request)
    {
        try {
            $validator = Validator::make( $request->all(), [
                'id' => 'required:int|exists:hellos',
                'name' => 'required:string|unique:hellos,name,' . $request->id,
                'order' => 'sometimes'
            ]);

            if( $validator->fails() ) {
                return $this->setError( 'Validation Error: ' . $validator->messages()->first());
            }

            $hello = Hello::findOrFail($request->id);

            $hello->update([
                'name' => $request->name,
                'order' => $request->order ?? null
            ]);

            return $this->setSuccess( 'The Hello updated successfully.', $hello->refresh()->toArray() );
        }
        catch (\Throwable $exception ) {
            return $this->setError( $exception->getMessage() );
        }
    }

    /**
     * @OA\Get(
     * path="/api/hello/delete",
     * operationId="Delete",
     * tags={"Hello APIs"},
     * summary="Delete hello by Id",
     * security={{ "apiAuth": {} }},
     * description="This will delete the given Hello",
     *    @OA\Parameter(
     *         name="id",
     *         in="query",
     *         description="Hello Id",
     *         required=true,
     *      ),
     *     @OA\Response(
     *          response=200,
     *          description="Hello deleted Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function delete(Request $request )
    {
        try {
            $hello = Hello::findOrFail( $request->id );

            if( !$hello ) {
                return $this->setError('Unable to find Hello by Id' . $request->id );
            }

            $hello->delete();

            return $this->setSuccess( 'The Hello deleted successfully.' );
        }
        catch (\Throwable $exception ) {
            return $this->setError( $exception->getMessage() );
        }
    }
}
