<?php

namespace App\Http\Controllers\Company;

use App\Company;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\RequestType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CompanyController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $companies = Company::all();
        return $this->showAll($companies);
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
                'name'=>'required|unique:companies',
            ]);

        if($validator->fails()){
            $validator->errors()->getMessages();
        }

        $campos = $request->all();
        $company = Company::create($campos);
        return $this->showOne($company, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        return $this->showOne($company);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, Company $company)
    {
        $company->fill($request->only([
            'name',
        ]));

        if($company->isClean()){
            return $this->errorResponse('Debe especificar al menos un valor diferente para actualizar', 422);
        }

        $company->save();
        return $this->showOne($company);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
        $company->delete();

        return $this->showOne($company);
    }
}
