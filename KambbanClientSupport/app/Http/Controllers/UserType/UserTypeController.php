<?php

namespace App\Http\Controllers\UserType;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\UserType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserTypeController extends ApiController
{

    public function index()
    {
        $userTypes = UserType::all();
        return $this->showAll($userTypes);
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
            'name'=>'required|unique:users_types',
        ]);

        if($validator->fails()){
            $validator->errors()->getMessages();
        }

        $campos = $request->all();
        $userType = UserType::create($campos);
        return $this->showOne($userType, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\UserType  $userType
     * @return \Illuminate\Http\Response
     */
    public function show(UserType $userType)
    {
        return $this->showOne($userType);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\UserType  $userType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserType $userType)
    {
        $userType->fill($request->only([
            'name',
            'description',
        ]));

        if($userType->isClean()){
            return $this->errorResponse('Debe especificar al menos un valor diferente para actualizar', 422);
        }

        $userType->save();
        return $this->showOne($userType);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\UserType  $userType
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserType $userType)
    {
        $userType->delete();
        return $this->showOne($userType);
    }
}
