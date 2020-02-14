<?php

namespace App\Http\Controllers\RequestType;

use App\Helpers\HttpRequestResponse;
use App\Http\Controllers\ApiController;
use App\Repository\RequestTypeRepository;
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $request = $this->request->query();

        $data = $this->requestTypeRepository->all($request);

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
        /**
         * Preguntar sobre error al momento de crear
         *
         *
         *
         */
        $result = [];
        $request = $this->request->json()->all();
        $statusCode = $this->httpRequestResponse->getResponseOk();

        $validator = Validator::make($request, [
            'name' => 'required|unique:requests_types',
        ]);

        if($validator->fails()){
            return response()->json(['message' => $validator->errors()], $this->httpRequestResponse->getResponseBadRequest());
        }

        $create = $this->requestTypeRepository->create($request);


        if(isset($create['error'])){
            $statusCode = $this->httpRequestResponse->getResponseInternalServerError();
        }

        if ($create->id){
            $data['id'] = $create->id;

            $createRequestType = $this->requestTypeRepository->create($data);

            if ($createRequestType){
                $result[] = $createRequestType;
            }

            if(isset($createUser['error'])){
                $statusCode = $this->httpRequestResponse->getResponseInternalServerError();
            }
        }

        return response()->json([
            'status' => $statusCode,
            'data'   => $result
        ], $statusCode);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\RequestType  $requestType
     * @return \Illuminate\Http\JsonResponse
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
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\RequestType  $requestType
     * @return \Illuminate\Http\JsonResponse
     */
    public function update()
    {
        /**
         *
         * Preguntar funcionamiento
         *
         *
         */
        $response = [];
        $request = $this->request->json()->all();
        $statusCode = $this->httpRequestResponse->getResponseOk();


        $update = $this->requestTypeRepository->update($request, $request['id']);

        if (isset($update->userType->id)){
            $updateUserType = $this->requestTypeRepository->update($request['values'],$update->requestType->id);

            if (isset($updateUserType['error'])){
                $statusCode = $this->httpRequestResponse->getResponseInternalServerError();
            }
            $response[] = $this->requestTypeRepository->find($request['id']);
        }else{
            $response[] = $update;
        }


        return response()->json([
            'status' => $statusCode,
            'data'   => $response
        ], $statusCode);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\RequestType  $requestType
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy()
    {
        /**
         * Preguntar por este metodo
         *
         *
         *
         *
         *
         *
         *
         */
        $request = $this->request->json()->all();
        $response = [];
        $statusCode = $this->httpRequestResponse->getResponseOk();


        $dataDelete = $this->requestTypeRepository->find($request['id']);
        /*dump($dataDelete);
        exit;*/
        $deleteUserType = $this->requestTypeRepository->delete($request);

        if(isset($deleteUserType['error'])){
            $statusCode = $this->httpRequestResponse->getResponseInternalServerError();

        }
        $deleteUserType = $this->requestTypeRepository->delete($dataDelete['id']);



        $response[] = "Eliminado: {$deleteUserType}";


        return response()->json([
            'status' => $statusCode,
            'data'   => $response
        ], $statusCode);
    }
}
