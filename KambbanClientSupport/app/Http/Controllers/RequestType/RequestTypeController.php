<?php

namespace App\Http\Controllers\RequestType;

use App\Helpers\HttpRequestResponse;
use App\Http\Controllers\ApiController;
use App\Repository\RequestTypeRepository;
use App\RequestType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RequestTypeController extends ApiController
{
    protected $httpRequestResponse;
    protected $request;
    protected $requestTypeRepository;
    protected $validator;

    public function __construct(
        Request $request,
        HttpRequestResponse $httpRequestResponse,
        RequestTypeRepository $requestTypeRepository
    )
    {
        $this->request = $request;
        $this->httpRequestResponse = $httpRequestResponse;
        $this->requestTypeRepository = $requestTypeRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $request = $this->request->query();
        $data = $this->requestTypeRepository->all($request);

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
        $statusCode = $this->httpRequestResponse->getResponseOk();

        $validator = Validator::make($request, [
            'name' => 'required|unique:requests_types',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], $this->httpRequestResponse->getResponseBadRequest());
        }

        $create = $this->requestTypeRepository->create($request);
        if (isset($create['error'])) {
            $statusCode = $this->httpRequestResponse->getResponseInternalServerError();
        }

        if ($create->id) {
            $data['id'] = $create->id;
            if ($create) {
                $result[] = $create;
            }
            if (isset($createUser['error'])) {
                $statusCode = $this->httpRequestResponse->getResponseInternalServerError();
            }
        }

        return response()->json([
            'status' => $statusCode,
            'data' => $result
        ], $statusCode);
    }

    /**
     * Display the specified resource.
     *
     * @param RequestType $requestType
     * @return JsonResponse
     */
    public function find()
    {
        $request = $this->request->query();
        $data = $this->requestTypeRepository->find($request);

        return response()->json([
            'message' => $this->httpRequestResponse->getResponseOk(),
            'data' => $data],
            $this->httpRequestResponse->getResponseOk());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param RequestType $requestType
     * @return JsonResponse
     */
    public function update()
    {
        $response = [];
        $request = $this->request->json()->all();
        $statusCode = $this->httpRequestResponse->getResponseOk();
        $update = $this->requestTypeRepository->update($request, $request['id']);

        if (isset($update->userType->id)) {
            $updateUserType = $this->requestTypeRepository->update($request['values'], $update->requestType->id);
            if (isset($updateUserType['error'])) {
                $statusCode = $this->httpRequestResponse->getResponseInternalServerError();
            }
            $response[] = $this->requestTypeRepository->find($request['id']);
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
     * @param RequestType $requestType
     * @return JsonResponse
     */
    public function destroy()
    {
        $request = $this->request->json()->all();
        $response = [];
        $statusCode = $this->httpRequestResponse->getResponseOk();
        $datadelete = $this->requestTypeRepository->find($request['id']);
        $deleteRequestType = $this->requestTypeRepository->delete($datadelete);

        if (isset($deleteCompany['error'])) {
            $statusCode = $this->httpRequestResponse->getResponseInternalServerError();
        }

        $response[] = "Eliminado: {$deleteRequestType['name']}";

        return response()->json([
            'status' => $statusCode,
            'data' => $response
        ], $statusCode);
    }
}
