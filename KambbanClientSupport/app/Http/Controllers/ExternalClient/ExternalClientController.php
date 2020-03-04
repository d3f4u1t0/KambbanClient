<?php

namespace App\Http\Controllers\ExternalClient;

use App\Helpers\HttpRequestResponse;
use App\Http\Controllers\ApiController;
use App\Repository\InternalClientRepository;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExternalClientController extends ApiController
{
    protected $httpRequestResponse;
    protected $request;
    protected $externalClientRepository;
    protected $validator;

    public function __construct(
        Request $request,
        HttpRequestResponse $httpRequestResponse,
        InternalClientRepository $externalClientRepository
    )
    {
        $this->request = $request;
        $this->httpRequestResponse = $httpRequestResponse;
        $this->externalClientRepository = $externalClientRepository;
    }

    public function index()
    {
        $request = $this->request->query();
        $data = $this->externalClientRepository->all($request);

        return response()->json([
            'message' => $this->httpRequestResponse->getResponseOk(),
            "data" => $data],
            $this->httpRequestResponse->getResponseOk()
        );
    }

    public function store()
    {
        $result = [];
        $request = $this->request->json()->all();
        $statusCode = $this->httpRequestResponse->getResponseOk();

        $validator = Validator::make($request, $rules = [
            'name' => 'required|unique:external_clients',
            'nit' => 'required|unique:external_clients',
            'internal_client_id' => 'required',
            'attrs' => 'nullable'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], $this->httpRequestResponse->getResponseBadRequest());
        }

        $create = $this->externalClientRepository->create($request);

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

    public function find()
    {

        try {
            $request = $this->request->json()->all();
            $data = $this->externalClientRepository->find($request['id']);
            return response()->json([
                'message' => $this->httpRequestResponse->getResponseOk(),
                "data" => $data,
                $this->httpRequestResponse->getResponseOk()
            ]);
        } catch (RequestException $exception) {
            $this->httpRequestResponse->getResponseBadRequest();
        }
    }

    public function update()
    {
        $response = [];
        $request = $this->request->json()->all();
        $statusCode = $this->httpRequestResponse->getResponseOk();
        $update = $this->externalClientRepository->update($request, $request['id']);

        if (isset($update->externalClient->id)) {
            $updateUserType = $this->externalClientRepository->update($request['values'], $update->externalClient->id);

            if (isset($updateUserType['error'])) {
                $statusCode = $this->httpRequestResponse->getResponseInternalServerError();
            }
            $response[] = $this->externalClientRepository->find($request['id']);
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

        $dataDelete = $this->externalClientRepository->find($request['id']);

        $deleteExternalClient = $this->externalClientRepository->delete($dataDelete);

        if (isset($deleteExternalClient['error'])) {
            $statusCode = $this->httpRequestResponse->getResponseInternalServerError();
        }

        $response[] = "Eliminado: {$deleteExternalClient['name']}";

        return response()->json([
            'status' => $statusCode,
            'data' => $response
        ], $statusCode);
    }
}

