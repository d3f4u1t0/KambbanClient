<?php

namespace App\Http\Controllers\ExternalUserType;

use App\Helpers\HttpRequestResponse;
use App\Http\Controllers\ApiController;
use App\Repository\ExternalUserTypeRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExternalUserTypeController extends ApiController
{
    protected $httpRequestResponse;
    protected $request;
    protected $externalUserTypeRepository;
    protected $validator;

    public function __construct(
        Request $request,
        HttpRequestResponse $httpRequestResponse,
        ExternalUserTypeRepository $externalUserTypeRepository
    )
    {
        $this->request = $request;
        $this->httpRequestResponse = $httpRequestResponse;
        $this->externalUserTypeRepository = $externalUserTypeRepository;
    }

    public function index()
    {
        $request = $this->request->query();

        $data = $this->externalUserTypeRepository->all($request);

        return response()->json([
            'message' => $this->httpRequestResponse->getResponseOk(),
            "data" => $data],
            $this->httpRequestResponse->getResponseOk()
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store()
    {
        $result = [];
        $request = $this->request->json()->all();
        $statuscode = $this->httpRequestResponse->getResponseOk();


        $validator = Validator::make($request, [
            'user_type'     => 'required|unique:users_types',
            'status'        => 'required',
            'attrs'         => 'nullable',
            'permission_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], $this->httpRequestResponse->getResponseBadRequest());
        }

        $create = $this->externalUserTypeRepository->create($request);
        if (isset($create['error'])) {
            $statuscode = $this->httpRequestResponse->getResponseInternalServerError();

        }
        if ($create->id) {
            if ($create) {
                $result[] = $create;
            }
            if (isset($createUser['error'])) {
                $statuscode = $this->httpRequestResponse->getResponseInternalServerError();
            }
        }

        return response()->json([
            'status' => $statuscode,
            'data' => $result
        ], $statuscode);
    }

    /**
     * Display the specified resource.
     *
     * @param UserType $userType
     * @return JsonResponse
     */
    public function find()
    {
        $request = $this->request->query();
        $data = $this->externalUserTypeRepository->find($request['id']);

        return response()->json([
            'message' => $this->httpRequestResponse->getResponseOk(),
            'data' => $data],
            $this->httpRequestResponse->getResponseOk());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param UserType $userType
     * @return JsonResponse
     */
    public function update()
    {
        $response = [];
        $request = $this->request->json()->all();
        $statusCode = $this->httpRequestResponse->getResponseOk();

        $update = $this->externalUserTypeRepository->update($request, $request['id']);

        if (isset($update->externalUserType->id)) {
            $updateUserType = $this->externalUserTypeRepository->update($request['values'], $update->externalUserType->id);

            if (isset($updateUserType['error'])) {
                $statusCode = $this->httpRequestResponse->getResponseInternalServerError();
            }
            $response[] = $this->externalUserTypeRepository->find($request['id']);
        } else {
            $response[] = $update;
        }


        return response()->json([
            'status' => $statusCode,
            'data' => $response
        ], $statusCode);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param UserType $userType
     * @return JsonResponse
     */
    public function destroy()
    {
        $request = $this->request->json()->all();
        $response = [];
        $statusCode = $this->httpRequestResponse->getResponseOk();

        $dataDelete = $this->userTypeRepository->find($request['id']);
        $deleteUserType = $this->userTypeRepository->delete($dataDelete);

        if (isset($deleteUserType['error'])) {
            $statusCode = $this->httpRequestResponse->getResponseInternalServerError();
        }

        $response[] = "Eliminado: {$deleteUserType['name']}";

        return response()->json([
            'status' => $statusCode,
            'data' => $response
        ], $statusCode);
    }
}
