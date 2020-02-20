<?php

namespace App\Http\Controllers\ExternalCustomer;

use App\Helpers\HttpRequestResponse;
use App\Http\Controllers\Company;
use App\ExternalCustomer;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Repository\ExternalCustomerRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExternalCustomerController extends ApiController
{
    protected $request;
    protected $httpRequestResponse;
    protected $externalCustomerRepository;

    public function __construct(
        Request $request,
        HttpRequestResponse $httpRequestResponse,
        ExternalCustomerRepository $externalCustomerRepository
    )
    {
        $this->request = $request;
        $this->httpRequestResponse = $httpRequestResponse;
        $this->externalCustomerRepository = $externalCustomerRepository;

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $request = $this->request->query();
        $data = $this->externalCustomerRepository->all($request);

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
    public function store(Request $request, Company $company)
    {
        $result = [];
        $request = $this->request->json()->all();
        $statusCode = $this->httpRequestResponse->getResponseOk();

        $validator = Validator::make($request, $rules = [
           'name' => 'required|unique:external_customers',
            'company_id' => 'required',
        ]);

        if ($validator->fails()){
            return response()->json(['message' => $validator->errors()], $this->httpRequestResponse->getResponseBadRequest());
        }

        $create = $this->externalCustomerRepository->create($request);

        if(isset($create['error'])){
            $statuscode = $this->httpRequestResponse->getResponseInternalServerError();
        }

        if ($create->id){
            $data['id'] = $create->id;


            if ($create){
                $result[] = $create;
            }

            if(isset($create['error'])){
                $statuscode = $this->httpRequestResponse->getResponseInternalServerError();
            }
        }

        return response()->json([
            'status' => $statuscode,
            'data'   => $result
        ], $statuscode);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ExternalCustomer  $externalCustomer
     * @return \Illuminate\Http\Response
     */
    public function show(ExternalCustomer $externalCustomer)
    {
        return $this->showOne($externalCustomer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ExternalCustomer  $externalCustomer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ExternalCustomer $externalCustomer)
    {
        $externalCustomer->fill($request->only([
            'name',
        ]));

        if($externalCustomer->isClean()){
            return $this->errorResponse('Debe especificar al menos un valor diferente para actualizar', 422);
        }

        $externalCustomer->save();
        return $this->showOne($externalCustomer);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ExternalCustomer  $externalCustomer
     * @return \Illuminate\Http\Response
     */
    public function destroy(ExternalCustomer $externalCustomer)
    {
        $externalCustomer->delete();
        return $this->showOne();
    }
}
