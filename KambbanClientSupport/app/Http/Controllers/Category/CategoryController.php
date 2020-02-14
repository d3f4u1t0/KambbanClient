<?php

namespace App\Http\Controllers\Category;

use App\Helpers\HttpRequestResponse;
use App\Http\Controllers\ApiController;
use App\Repository\CategoryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends ApiController
{

    protected $httpRequestResponse;
    protected $request;
    protected $categoryRepository;
    protected $validator;

    public function __construct(
        Request $request,
        HttpRequestResponse $httpRequestResponse,
        CategoryRepository $categoryRepository
    )
    {
        $this->request = $request;
        $this->httpRequestResponse = $httpRequestResponse;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $request = $this->request->query();

        $data = $this->categoryRepository->all($request);

        return response()->json([
            'message' => $this->httpRequestResponse->getResponseOk(),
            "data"    => $data],
            $this->httpRequestResponse->getResponseOk()
        );
    }


    public function store(Request $request)
    {
        $result = [];
        $request = $this->request->json()->all();
        $statusCode = $this->httpRequestResponse->getResponseOk();

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required'
        ]);

        if($validator->fails()){
            return response()->json(['message' => $validator->errors()], $this->httpRequestResponse->getResponseBadRequest());
        }

        $create = $this->categoryRepository->create($request);


        if(isset($create['error'])){
            $statusCode = $this->httpRequestResponse->getResponseInternalServerError();
        }

        if ($create->id){
            $data['id'] = $create->id;

            $createCategory = $this->categoryRepository->create($data);

            if ($createCategory){
                $result[] = $createCategory;
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
     * @param  \App\Category  $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function find()
    {
        $request = $this->request->query();
        $data = $this->categoryRepository->find($request['id']);

        return response()->json([
            'message' => $this->httpRequestResponse->getResponseOk(),
            'data' => $data],
            $this->httpRequestResponse->getResponseOk());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Category $category)
    {
        $response = [];
        $request = $this->request->json()->all();
        $statusCode = $this->httpRequestResponse->getResponseOk();



        $update = $this->categoryRepository->update($request, $request['id']);

        if (isset($update->userType->id)){
            $updateUserType = $this->categoryRepository->update($request['values'],$update->category->id);

            if (isset($updateUserType['error'])){
                $statusCode = $this->httpRequestResponse->getResponseInternalServerError();
            }
            $response[] = $this->categoryRepository->find($request['id']);
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
     * @param  \App\Category  $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Category $category)
    {
        $request = $this->request->json()->all();
        $response = [];
        $statusCode = $this->httpRequestResponse->getResponseOk();


        $dataDelete = $this->categoryRepository->find($request['id']);
        /*dump($dataDelete);
        exit;*/
        $deleteUserType = $this->categoryRepository->delete($request);

        if(isset($deleteUserType['error'])){
            $statusCode = $this->httpRequestResponse->getResponseInternalServerError();

        }
        $deleteUserType = $this->categoryRepository->delete($dataDelete['id']);



        $response[] = "Eliminado: {$deleteUserType}";


        return response()->json([
            'status' => $statusCode,
            'data'   => $response
        ], $statusCode);
    }
}
