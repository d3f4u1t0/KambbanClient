<?php

namespace App\Http\Controllers\ExternalCustomer;

use App\Http\Controllers\Company;
use App\ExternalCustomer;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExternalCustomerController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $externalCustomers = ExternalCustomer::all();
        return $this->showAll($externalCustomers);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Company $company)
    {
        $validator = Validator::make($request, $rules = [
           'name' => 'required|unique:external_customers',
            'company_id' => 'required',
        ]);

        if ($validator->fails()){
            $validator->errors()->getMessages();
        }

        $campos = $request->all();
        $campos['company_id'] = $company->id = $request->get('company_id');

        $externalCustomer = ExternalCustomer::create($campos);
        return $this->showOne($externalCustomer, 201);
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
