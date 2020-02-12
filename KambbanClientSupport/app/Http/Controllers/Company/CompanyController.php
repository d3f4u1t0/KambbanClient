<?php

namespace App\Http\Controllers\Company;

use App\Models\Company;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
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
     $this->repository = $companyRepository;
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
    public function store(Request $request)
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

        foreach ($request as $data){
            $create = $this->repository->create($data);

            if(isset($create['error'])){
                $statuscode = $this->httpRequestResponse->getResponseInternalServerError();
                break;
            }

            if($create->id){
                $data['id'] = $create->id;
                $createCompany = $this->companyRepository->create($data);

                if ($createCompany){
                    $result[] = $createCompany;
                }

                if (isset($createCompany['error'])){
                    $statuscode = $this->httpRequestResponse->getResponseInternalServerError();
                    break;
                }
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

       foreach ($request as $data){
           $update = $this->companyRepository->update($data['values'], $data['id']);

           if (isset($update->company->id)){
               $updatecompany = $this->repository->update($data['values'],$update->company->id);

               if (isset($updatecompany['error'])){
                   $statusCode = $this->httpRequestResponse->getResponseInternalServerError();
                   break;
               }
               $response[] = $this->companyRepository->find($data['id']);
           }else{
               $response[] = $update;
           }
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

        foreach ($request as $data){
            $datadelete = $this->companyRepository->find($data['id']);
            $deleteCompany = $this->companyRepository->delete($data['id']);

            if(isset($deleteCompany['error'])){
                $statusCode = $this->httpRequestResponse->getResponseInternalServerError();
                break;
            }

            $deleteCompany = $this->repository->delete($datadelete[0]['id']);

            if(isset($deleteCompany['error'])){
                $statusCode = $this->httpRequestResponse->getResponseInternalServerError();
                break;
            }

            $response[] = "Eliminado: {$deleteCompany}";
        }

        return response()->json([
            'status' => $statusCode,
            'data'   => $response
        ], $statusCode);
    }
}
