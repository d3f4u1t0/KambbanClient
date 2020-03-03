<?php

namespace App\Http\Controllers\InternalClient;

use App\Helpers\HttpRequestResponse;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Repository\InternalClientRepository;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InternalClientController extends ApiController
{
    protected $httpRequestResponse;
    protected $request;
    protected $internalClientRepository;
    protected $validator;

    public function __construct(
        Request $request,
        HttpRequestResponse $httpRequestResponse,
        InternalClientRepository $internalClientRepository
    )
    {

        $this->request = $request;
        $this->httpRequestResponse = $httpRequestResponse;
        $this->internalClientRepository = $internalClientRepository;
    }

    public function index(){
        $request = $this->request->query();
        $data = $this->internalClientRepository->all($request);

        return response()->json([
            'message' =>$this->httpRequestResponse->getResponseOk(),
            "data"    =>$data],
            $this->httpRequestResponse->getResponseOk()
        );
    }

    public function store(){
        $result = [];
        $request = $this->request->json()->all();
        $statusCode = $this->httpRequestResponse->getResponseOk();

        $validator = Validator::make($request, $rules = [
           'name' => 'required',
           'nit'  => 'required|unique:internal_clients',
           'attrs'=> 'nullable'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], $this->httpRequestResponse->getResponseBadRequest());
        }

        $create = $this->internalClientRepository->create($request);

        if (isset($create['error'])) {
            $statusCode = $this->httpRequestResponse->getResponseInternalServerError();
        }
        if ($create->id) {
            $data['id'] = $create->id;
            if ($create) {
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

    public function find(){

        try {
            $request = $this->request->json()->all();
            $data = $this->internalClientRepository->find($request['id']);
            return response()->json([
                'message' => $this->httpRequestResponse->getResponseOk(),
                "data"    => $data,
                $this->httpRequestResponse->getResponseOk()
            ]);
        }catch (RequestException $exception){
            $this->httpRequestResponse->getResponseBadRequest();
        }
    }

    public function update(){
        $response = [];
        $request = $this->request->json()->all();
        $statusCode = $this->httpRequestResponse->getResponseOk();
        $update = $this->internalClientRepository->update($request, $request['id']);

        if (isset($update->internalClient->id)) {
            $updateUserType = $this->internalClientRepository->update($request['values'], $update->internalClient->id);

            if (isset($updateUserType['error'])) {
                $statusCode = $this->httpRequestResponse->getResponseInternalServerError();
            }
            $response[] = $this->internalClientRepository->find($request['id']);
        } else {
            $response[] = $update;
        }

        return response()->json([
            'status' => $statusCode,
            'data' => $response
        ], $statusCode);
    }

    public function destroy(){
        $request = $this->request->json()->all();
        $response = [];
        $statusCode = $this->httpRequestResponse->getResponseOk();

        $dataDelete = $this->internalClientRepository->find($request['id']);

        $deleteInternalClient = $this->internalClientRepository->delete($dataDelete);

        if (isset($deleteInternalClient['error'])) {
            $statusCode = $this->httpRequestResponse->getResponseInternalServerError();
        }

        $response[] = "Eliminado: {$deleteInternalClient['name']}";

        return response()->json([
            'status' => $statusCode,
            'data' => $response
        ], $statusCode);
    }
}
