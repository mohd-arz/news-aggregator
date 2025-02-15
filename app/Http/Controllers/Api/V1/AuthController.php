<?php

namespace App\Http\Controllers\Api\V1;

use App\Action\Auth\RegisterAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Http\Resources\Api\Auth\UserResource;
use App\Models\User;
use App\Trait\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    use ResponseTrait;
    /**
     * @OA\Post(
     *      path="/api/v1/register",
     *      operationId="register",
     *      tags={"Auth"},
     *      summary="Register User",
     *      description="Register a new user and return user details along with an authentication token",
     *      @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/RegisterRequest")
     *     ),
     *      @OA\Response(
     *          response=201,
     *          description="User registered successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  @OA\Property(property="user", ref="#/components/schemas/UserResource"),
     *                  @OA\Property(property="token", type="string", description="Authentication token")
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation failed or something went wrong",
     *          @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *      )
     * )
     */
    public function register(RegisterRequest $request,RegisterAction $action){
        $response = $action->execute(collect($request->validated()));

        if($response['status']){
            return $this->successResponse('User Register Successfully',[
                'user' => UserResource::make($response['data']),
                'token' => $this->authenticateUser($response['data'])
            ],201);
        }
         return  $this->errorResponse('Something went wrong',$response['error'],422);
    }

   
    /**
     * @OA\Post(
     *      path="/api/v1/login",
     *      operationId="login",
     *      tags={"Auth"},
     *      summary="Login User",
     *      description="Authenticate user and return user details with an access token",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/LoginRequest")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="User Login Successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  @OA\Property(property="user", ref="#/components/schemas/UserResource"),
     *                  @OA\Property(property="token", type="string", description="Authentication token")
     *              )
     *          )
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Invalid Credentials",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Invalid Credentials"),
     *              @OA\Property(property="errors", type="object",example={})
     *          )
     *      )
     * )
     */

    public function login(LoginRequest $request){
        if(!Auth::attempt($request->validated())){
            return $this->errorResponse('Invalid Credentials',[],401);
        }
        /** @var User $user */
        $user = Auth::user();
        return $this->successResponse('User Login Successfully',[
            'user' => UserResource::make($user),
            'token' => $this->authenticateUser($user)
        ]);
    }

    /**
     * @OA\Post(
     *      path="/api/v1/logout",
     *      operationId="logout",
     *      tags={"Auth"},
     *      summary="Logout User",
     *      description="Logout User",
     *      security={{"sanctum": {}}},
     *      @OA\Response(
     *          response=200,
     *          description="User Logout Successfully",
     *          @OA\JsonContent(ref="#/components/schemas/SuccessResponse")
     *       )
     * )
     */
    public function logout(){
        /** @var User $user */
        $user = Auth::user();
        $user->tokens()->delete();
        return $this->successResponse('User Logout Successfully');
    }

    private function authenticateUser(User $user){
        Auth::login($user);
        return $user->createToken('authToken')->plainTextToken;
    }
}
