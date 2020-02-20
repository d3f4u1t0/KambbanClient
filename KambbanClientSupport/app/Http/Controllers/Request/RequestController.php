<?php

namespace App\Http\Controllers\Request;

use App\Helpers\HttpRequestResponse;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Repository\RequestRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class RequestController extends ApiController
{

    protected $httpRequestResponse;
    protected $request;
    protected $requestRepository;
    protected $validator;

    public function __construct(
        HttpRequestResponse $httpRequestResponse,
        Request $request,
        RequestRepository $requestRepository
    )
    {
        $this->request = $request;
        $this->httpRequestResponse = $httpRequestResponse;
        $this->requestRepository = $requestRepository;
    }

    public function index(){
        $request = $this->request->query();
        $data = $this->requestRepository->all($request);

        return response()->json([
            'messagge' => $this->httpRequestResponse->getResponseOk(),
            "data" => $data],
            $this->httpRequestResponse->getResponseOk()
        );
    }

    public function store(){

        $result = [];
        $request = $this->request->json()->all();
        $statusCode = $this->httpRequestResponse->getResponseOk();

        $validator = Validator::make($request, $rules = [
           'request' => 'required',
           /**
            * pendiente
            *
            *
            */

        ]);

    }

    public function find(){
        $request = $this->request->query();

        $data = $this->requestRepository->find($request['id']);

        return response()->json([
            'message' => $this->httpRequestResponse->getResponseOk(),
            'data' => $data],
            $this->httpRequestResponse->getResponseOk());
    }

    public function update()
    {

        $response = [];
        $request = $this->request->json()->all();
        $statusCode = $this->httpRequestResponse->getResponseOk();


        $update = $this->requestRepository->update($request, $request['id']);

        if (isset($update->user->id)){
            $updateuser = $this->requestRepository->update($request,$update->user->id);

            if (isset($updateuser['error'])){
                $statusCode = $this->httpRequestResponse->getResponseInternalServerError();

            }
            $response[] = $this->requestRepository->find($request['id']);
        }else{
            $response[] = $update;
        }


        return response()->json([
            'status' => $statusCode,
            'data'   => $response
        ], $statusCode);
    }

    public function destroy()
    {
        $request = $this->request->json()->all();
        $response = [];
        $statusCode = $this->httpRequestResponse->getResponseOk();

        $datadelete = $this->requestRepository->find($request['id']);

        $deleteRequest = $this->requestRepository->delete($datadelete);

        if(isset($deleteUser['error'])){
            $statusCode = $this->httpRequestResponse->getResponseInternalServerError();
        }

        $response[] = "Eliminado: {$deleteRequest['name']}";


        return response()->json([
            'status' => $statusCode,
            'data'   => $response
        ], $statusCode);
    }
}
