<?php

namespace App\Http\Controllers\ExternalUserPermission;

use App\Helpers\HttpRequestResponse;
use App\Http\Controllers\ApiController;
use App\Repository\ExternalUserPermissionsRepository;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExternalUserPermissionsController extends ApiController
{
    protected $httpRequestResponse;
    protected $request;
    protected $externalUserPermissionRepository;
    protected $validator;

    public function __construct(
        HttpRequestResponse $httpRequestResponse,
        Request $request,
        ExternalUserPermissionsRepository $externalUserPermissionRepository
    )
    {
        $this->request = $request;
        $this->httpRequestResponse = $httpRequestResponse;
        $this->externalUserPermissionRepository = $externalUserPermissionRepository;
    }

    public function index(){
        $request = $this->request->query();
        $data = $this->externalUserPermissionRepository->all($request);

        return response()->json([
            'message' => $this->httpRequestResponse->getResponseOk(),
            "data" => $data],
            $this->httpRequestResponse->getResponseOk()
        );
    }

    public function find(){
        try {
            $request = $this->request->json()->all();
            $data = $this->externalUserPermissionRepository->find($request['id']);
            return response()->json([
                'message' => $this->httpRequestResponse->getResponseOk(),
                "data" => $data,
                $this->httpRequestResponse->getResponseOk()
            ]);
        } catch (RequestException $exception) {
            return $this->httpRequestResponse->getResponseBadRequest();
        }
    }

    public function store(){
        $result = [];
        $request = $this->request->json()->all();
        $statusCode = $this->httpRequestResponse->getResponseOk();

        $validator = Validator::make($request, $rules = [
            'permission' => 'required|unique:external_user_permissions',
            'description' => 'required',
            'attrs' => 'nullable'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], $this->httpRequestResponse->getResponseBadRequest());
        }

        $create = $this->externalUserPermissionRepository->create($request);

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

    public function update()
    {
        $response = [];
        $request = $this->request->json()->all();
        $statusCode = $this->httpRequestResponse->getResponseOk();
        $update = $this->externalUserPermissionRepository->update($request, $request['id']);

        if (isset($update->userPermission->id)) {
            $updateUserPermission = $this->externalUserPermissionRepository->update($request['values'], $update->userPermission->id);

            if (isset($updateUserPermission['error'])) {
                $statusCode = $this->httpRequestResponse->getResponseInternalServerError();
            }
            $response[] = $this->externalUserPermissionRepository->find($request['id']);
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

        $dataDelete = $this->externalUserPermissionRepository->find($request['id']);

        $deleteExternalUserPermission = $this->externalUserPermissionRepository->delete($dataDelete);

        if (isset($deleteExternalUserPermission['error'])) {
            $statusCode = $this->httpRequestResponse->getResponseInternalServerError();
        }

        $response[] = "Eliminado: {$deleteExternalUserPermission['permission']}";

        return response()->json([
            'status' => $statusCode,
            'data' => $response
        ], $statusCode);
    }
}
