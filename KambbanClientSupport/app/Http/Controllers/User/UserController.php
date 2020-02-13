<?php

namespace App\Http\Controllers\User;

use App\Company;
use App\Helpers\HttpRequestResponse;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Repository\UserRepository;
use App\User;
use App\UserType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends ApiController
{
    protected $httpRequestResponse;
    protected $request;
    protected $repository;
    protected $userRepository;
    protected $validator;

    public function __construct(
        Request $request,
        HttpRequestResponse $httpRequestResponse,
        UserRepository $userRepository
    )
    {
        $this->request = $request;
        $this->httpRequestResponse = $httpRequestResponse;
        $this->userRepository = $userRepository;
    }


    public function index()
    {
        $request = $this->request->query();

        $data = $this->userRepository->all($request);

        return response()->json([
            'message' => $this->httpRequestResponse->getResponseOk(),
            "data"    => $data],
            $this->httpRequestResponse->getResponseOk()
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store()
    {

        $result = [];
        $request = $this->request->json()->all();
        $statuscode = $this->httpRequestResponse->getResponseOk();

        /*dump($request);
        exit;*/
        $validator = Validator::make($request, $rules = [
           'name' => 'required',
            'email' => 'required|email|unique:users',
            'username' => 'required|unique:users',
            'password' => 'required|min:6|confirmed',
            'user_type_id' => 'required',
            'company_id' => 'required'
        ]);

       /* dump($validator);
        exit;*/

        if($validator->fails()){
            return response()->json(['message' => $validator->errors()], $this->httpRequestResponse->getResponseBadRequest());
        }


            $create = $this->userRepository->create($request);

            if(isset($create['error'])){
                $statuscode = $this->httpRequestResponse->getResponseInternalServerError();
            }

            if ($create->id){
                $data['id'] = $create->id;

                $createUser = $this->userRepository->create($data);

                if ($createUser){
                    $result[] = $createUser;
                }

                if(isset($createUser['error'])){
                    $statuscode = $this->httpRequestResponse->getResponseInternalServerError();
                }
            }

        return response()->json([
           'status' => $statuscode,
           'data'   => $result
        ], $statuscode);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function find()
    {
        $request = $this->request->query();

        $data = $this->userRepository->find($request['id']);

        return response()->json([
            'message' => $this->httpRequestResponse->getResponseOk(),
            'data' => $data],
            $this->httpRequestResponse->getResponseOk());
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update()
    {

        $response = [];
        $request = $this->request->json()->all();
        $statusCode = $this->httpRequestResponse->getResponseOk();

        foreach ($request as $data){
            $update = $this->userRepository->update($data['values'], $data['id']);

            if (isset($update->user->id)){
                $updateuser = $this->repository->update($data['values'],$update->user->id);

                if (isset($updateuser['error'])){
                    $statusCode = $this->httpRequestResponse->getResponseInternalServerError();
                    break;
                }
                $response[] = $this->userRepository->find($data['id']);
            }else{
                $response[] = $update;
            }
        }

        return response()->json([
            'status' => $statusCode,
            'data'   => $response
        ], $statusCode);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy()
    {
        $request = $this->request->json()->all();
        $response = [];
        $statusCode = $this->httpRequestResponse->getResponseOk();

        foreach ($request as $data){
            $datadelete = $this->userRepository->find($data['id']);
            $deleteUser = $this->userRepository->delete($data['id']);

            if(isset($deleteUser['error'])){
                $statusCode = $this->httpRequestResponse->getResponseInternalServerError();
                break;
            }

            $deleteUser = $this->repository->delete($datadelete[0]['id']);

            if(isset($deleteUser['error'])){
                $statusCode = $this->httpRequestResponse->getResponseInternalServerError();
                break;
            }

            $response[] = "Eliminado: {$deleteUser}";
        }

        return response()->json([
            'status' => $statusCode,
            'data'   => $response
        ], $statusCode);
    }
}
