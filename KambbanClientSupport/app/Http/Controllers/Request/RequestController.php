<?php

namespace App\Http\Controllers\Request;

use App\Helpers\HttpRequestResponse;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\AuthController;
use App\Repository\RequestRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Expr\New_;

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

    public function index()
    {
        $request = $this->request->query();
        $data = $this->requestRepository->all($request);

        return response()->json([
            'messagge' => $this->httpRequestResponse->getResponseOk(),
            "data" => $data],
            $this->httpRequestResponse->getResponseOk()
        );
    }

    public function store()
    {

        $result = [];
        $request = $this->request->json()->all();
        $statusCode = $this->httpRequestResponse->getResponseOk();
        $request['user_id'] = auth('api')->user()->id;

        $validator = Validator::make($request, $rules = [
            'request' => 'required',
            'request_type_id' => 'required',
            'category_id' => 'required',
            'status' => 'required',
            'user_id' => 'nullable',
            'external_user_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], $this->httpRequestResponse->getResponseBadRequest());
        }

        $create = $this->requestRepository->create($request);
        /* dump($create);
         exit();*/

        if(isset($create['error'])){
            $statusCode = $this->httpRequestResponse->getResponseInternalServerError();
        }
        if($create->id){
            $data['id'] = $create->id;
            if($create){
                $result[] = $create;
            }
            if (isset($create['error'])) {
                $statusCode = $this->httpRequestResponse->getResponseInternalServerError();
            }
        }
        return response()->json([
            'status' => $statusCode,
            'data' => $result
        ], $statusCode);
    }

    public function find()
    {
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

        if (isset($update->user->id)) {
            $updateuser = $this->requestRepository->update($request, $update->requestRepository->id);
            if (isset($updateuser['error'])) {
                $statusCode = $this->httpRequestResponse->getResponseInternalServerError();
            }
            $response[] = $this->requestRepository->find($request['id']);
        } else {
            $response[] = $update;
        }

        return response()->json([
            'status' => $statusCode,
            'data' => $response
        ], $statusCode);
    }

    public function destroy()
    {
        $request = $this->request->json()->all();
        $response = [];
        $statusCode = $this->httpRequestResponse->getResponseOk();
        $datadelete = $this->requestRepository->find($request['id']);
        $deleteRequest = $this->requestRepository->delete($datadelete);

        if (isset($deleteRequest['error'])) {
            $statusCode = $this->httpRequestResponse->getResponseInternalServerError();
        }

        $response[] = "Eliminado: {$deleteRequest['request']}";

        return response()->json([
            'status' => $statusCode,
            'data' => $response
        ], $statusCode);
    }
}
