<?php

namespace App\Http\Controllers;

use App\Repository\CompanyRepository;
use App\RequestType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\HttpRequestResponse;

class CompanyController extends ApiController
{

    protected $httpRequestResponse;
    protected $request;
    protected $validator;
    protected $companyRepository;
    protected $repository;

    public function __construct(
        Request $request,
        HttpRequestResponse $httpRequestResponse,
        CompanyRepository $companyRepository
    )
    {
     $this->request = $request;
     $this->httpRequestResponse = $httpRequestResponse;
     $this->companyRepository = $companyRepository;
    }

    public function index()
    {
        $request = $this->request->query();

        $data = $this->companyRepository->all($request);

        return response()->json([
            'message' => $this->httpRequestResponse->getResponseOk(),
            "data"    => $data],
            $this->httpRequestResponse->getResponseOk());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store()
    {
        $result = [];
        $request = $this->request->json()->all();
        $statuscode = $this->httpRequestResponse->getResponseOk();

        $validator = Validator::make($request, [
                'name'=>'required|unique:companies',
            ]);

        if($validator->fails()){
            return response()->json(['message' => $validator->errors()], $this->httpRequestResponse->getResponseBadRequest());
        }

        $create = $this->companyRepository->create($request);


        if(isset($create['error'])){
            $statuscode = $this->httpRequestResponse->getResponseInternalServerError();
        }

        if ($create->id){
            $data['id'] = $create->id;



            if ($create){
                $result[] = $create;
            }

            if(isset($createUser['error'])){
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
     * @param  \App\Company  $company
     * @return \Illuminate\Http\JsonResponse
     */
    public function find()
    {

        $request = $this->request->query();

        $data = $this->companyRepository->find($request['id']);

        return response()->json([
            'message' => $this->httpRequestResponse->getResponseOk(),
            'data' => $data],
            $this->httpRequestResponse->getResponseOk());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Company  $company
     * @return \Illuminate\Http\JsonResponse
     */

    public function update()
    {
       $response = [];
       $request = $this->request->json()->all();
       $statusCode = $this->httpRequestResponse->getResponseOk();


        dump($request);
        exit;
        $update = $this->companyRepository->update($request, $request['id']);
           if (isset($update->company->id)){
               $updatecompany = $this->companyRepository->update($request,$update->company->id);

               if (isset($updatecompany['error'])){
                   $statusCode = $this->httpRequestResponse->getResponseInternalServerError();

               }
               $response[] = $this->companyRepository->find($request['id']);
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
     * @param  \App\Company  $company
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy()
    {
        $request = $this->request->json()->all();
        $response = [];
        $statusCode = $this->httpRequestResponse->getResponseOk();

        $datadelete = $this->companyRepository->find($request['id']);
        $deleteCompany = $this->companyRepository->delete($datadelete['id']);

            if(isset($deleteCompany['error'])){
                $statusCode = $this->httpRequestResponse->getResponseInternalServerError();
                ;
            }

            $response[] = "Eliminado: {$deleteCompany}";


        return response()->json([
            'status' => $statusCode,
            'data'   => $response
        ], $statusCode);
    }
}
