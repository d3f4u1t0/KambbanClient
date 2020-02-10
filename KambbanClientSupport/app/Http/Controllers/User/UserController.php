<?php

namespace App\Http\Controllers\User;

use App\Company;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\User;
use App\UserType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends ApiController
{
    public function index()
    {
        $usuarios = User::all();
        return $this->showAll($usuarios);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $user, UserType $userType, Company $company)
    {
        $validator = Validator::make($request->all(), $rules = [
           'name' => 'required',
            'email' => 'required|email|unique:users',
            'username' => 'required|unique:users',
            'password' => 'required|min:6|confirmed',
            'user_type_id' => 'required',
            'company_id' => 'required'
        ]);

        if($validator->fails()){
            $validator->errors()->getMessages();
        }

        $campos = $request->all();
        $campos['password'] = bcrypt($request->password);
        $campos['verified'] = User::USUARIO_NO_VERIFICADO;
        $campos['verification_token'] = User::generarVerificationToken();
        $campos['user_type_id'] = $userType->id = $request->get('user_type_id');
        $campos['company_id'] = $company->id = $request->get('company_id');

        $usuario = User::create($campos);
        return $this->showOne($usuario, 201);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return $this->showOne($user);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {

        /**
         * Preguntar sobre la implementación del update
         */


        $validator = Validator::make($request->all(), $rules = [
            'email' => 'email|unique:users,email,' . $user->id,
            'password' => 'min:6|confirmed',
            'username' => 'unique:users,username,' . $user->username,
        ]);

        if($validator->fails()){
            $validator->errors()->getMessages();
        }

        if($request->has('name')){
            $user->name = $request->name;
        }

        if($request->has('email') && $user->email != $request->email){
            $user->email = $request->email;
        }

        if($request->has('password')){
            $user->password = bcrypt($request->password);
        }

        if(!$user->isClean()){
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422);
        }

        $user->save();
        return $this->showOne($user,200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return $this->showOne($user);
    }
}
