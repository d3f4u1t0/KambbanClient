<?php

namespace App\Http\Controllers\UserPermission;

use App\Helpers\HttpRequestResponse;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Repository\UserPermissionRepository;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserPermissionController extends ApiController
{
    protected $httpRequestResponse;
    protected $request;
    protected $userPermissionRepository;
    protected $validator;

    public function __construct(
        HttpRequestResponse $httpRequestResponse,
        Request $request,
        UserPermissionRepository $userPermissionRepository
    )
    {
        $this->request = $request;
        $this->httpRequestResponse = $httpRequestResponse;
        $this->userPermissionRepository = $userPermissionRepository;
    }

    public function index(){
        $request = $this->request->query();
        $data = $this->userPermissionRepository->all($request);

        return response()->json([
            'message' => $this->httpRequestResponse->getResponseOk(),
            "data" => $data],
            $this->httpRequestResponse->getResponseOk()
        );
    }

    public function find(){
        try {
            $request = $this->request->json()->all();
            $data = $this->userPermissionRepository->find($request['id']);
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
            'permission' => 'required|unique:permissions',
            'description' => 'required',
            'attrs' => 'nullable'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], $this->httpRequestResponse->getResponseBadRequest());
        }

        $create = $this->userPermissionRepository->create($request);

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
        $update = $this->userPermissionRepository->update($request, $request['id']);

        if (isset($update->userPermission->id)) {
            $updateUserPermission = $this->userPermissionRepository->update($request['values'], $update->userPermission->id);

            if (isset($updateUserPermission['error'])) {
                $statusCode = $this->httpRequestResponse->getResponseInternalServerError();
            }
            $response[] = $this->userPermissionRepository->find($request['id']);
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

        $dataDelete = $this->userPermissionRepository->find($request['id']);

        $deleteUserPermission = $this->userPermissionRepository->delete($dataDelete);

        if (isset($deleteUserPermission['error'])) {
            $statusCode = $this->httpRequestResponse->getResponseInternalServerError();
        }

        $response[] = "Eliminado: {$deleteUserPermission['name']}";

        return response()->json([
            'status' => $statusCode,
            'data' => $response
        ], $statusCode);
    }
}
