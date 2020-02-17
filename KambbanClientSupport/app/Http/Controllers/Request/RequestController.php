<?php

namespace App\Http\Controllers\Request;

use App\Helpers\HttpRequestResponse;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Repository\RequestRepository;
use Illuminate\Http\Request;

class RequestController extends ApiController
{

    protected $httpRequestResponse;
    protected $request;
    protected $requestRepository;
    protected $validator;

    public function __construct(
        HttpRequestResponse $httpRequestResponse,
        Request $request,
        RequestRepository $requestRepository
    )
    {
        $this->request = $request;
        $this->httpRequestResponse = $httpRequestResponse;
        $this->requestRepository = $requestRepository;
    }

    public function index(){
        $request = $this->request->query();
        $data = $this->requestRepository->all($request);

        return response()->json([
            'messagge' => $this->httpRequestResponse->getResponseOk(),
            "data" => $data],
            $this->httpRequestResponse->getResponseOk()
        );
    }

    public function store(){

        $result = [];
        $request = $this->request->json()->all();
        $statusCode = $this->httpRequestResponse->getResponseOk();

    }
}
