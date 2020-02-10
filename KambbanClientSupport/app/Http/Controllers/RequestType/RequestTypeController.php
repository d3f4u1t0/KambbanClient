<?php

namespace App\Http\Controllers\RequestType;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\RequestType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RequestTypeController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $requestTypes = RequestType::all();
        return $this->showAll($requestTypes);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:requests_types',
        ]);

        if($validator->fails()){
            return $validator->errors()->getMessages();
        }

        $requestType = RequestType::create($request->all());

        return $this->showOne($requestType, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\RequestType  $requestType
     * @return \Illuminate\Http\Response
     */
    public function show(RequestType $requestType)
    {
        return $this->showOne($requestType);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\RequestType  $requestType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RequestType $requestType)
    {
        $requestType->fill($request->only([
            'name',
        ]));

        if($requestType->isClean()){
            return $this->errorResponse('Debe especificar al menos un valor diferente para actualizar', 422);
        }

        $requestType->save();
        return $this->showOne($requestType);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\RequestType  $requestType
     * @return \Illuminate\Http\Response
     */
    public function destroy(RequestType $requestType)
    {
        $requestType->delete();
        return $this->showOne($requestType);
    }
}
