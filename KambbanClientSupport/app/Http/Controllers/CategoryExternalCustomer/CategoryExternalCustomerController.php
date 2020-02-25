<?php

namespace App\Http\Controllers\CategoryExternalCustomer;

use App\Helpers\HttpRequestResponse;
use App\Http\Controllers\ApiController;
use App\Repository\CategoryExternalCustomerRepository;
use App\RequestType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryExternalCustomerController extends ApiController
{
    protected $httpRequestResponse;
    protected $request;
    protected $categoryExternalCustomer;
    protected $validator;

    public function __construct(
        Request $request,
        HttpRequestResponse $httpRequestResponse,
        CategoryExternalCustomerRepository $categoryExternalCustomer
    )
    {
        $this->request = $request;
        $this->httpRequestResponse = $httpRequestResponse;
        $this->categoryExternalCustomer = $categoryExternalCustomer;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $request = $this->request->query();
        $data = $this->categoryExternalCustomer->all($request);

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
            'external_customer_id' => 'required',
            'category_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], $this->httpRequestResponse->getResponseBadRequest());
        }

        $create = $this->categoryExternalCustomer->create($request);

        if (isset($create['error'])) {
            $statuscode = $this->httpRequestResponse->getResponseInternalServerError();
        }
        if ($create->id) {
            $data['id'] = $create->id;
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
     * @param RequestType $requestType
     * @return JsonResponse
     */
    public function find()
    {
        $request = $this->request->query();
        $data = $this->categoryExternalCustomer->find($request);

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
        $update = $this->categoryExternalCustomer->update($request, $request['id']);

        if (isset($update->userType->id)) {
            $updateUserType = $this->categoryExternalCustomer->update($request['values'], $update->categoryExternalCustomer->id);
            if (isset($updateUserType['error'])) {
                $statusCode = $this->httpRequestResponse->getResponseInternalServerError();
            }
            $response[] = $this->categoryExternalCustomer->find($request['id']);
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
        $dataDelete = $this->categoryExternalCustomer->find($request['id']);

        $deleteRelation = $this->categoryExternalCustomer->delete($dataDelete);

        if (isset($deleteRelation['error'])) {
            $statusCode = $this->httpRequestResponse->getResponseInternalServerError();
        }

        $response[] = "Eliminado: {$deleteRelation['name']}";

        return response()->json([
            'status' => $statusCode,
            'data' => $response
        ], $statusCode);
    }

}
