<?php

namespace App\Repository;

use App\Models\Company;
use App\Interfaces\RepositoriesInterface;
use App\Traits\RepositoryTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class CompanyRepository implements RepositoriesInterface{

    use RepositoryTrait;

    private $model;
    private $fields = [
        'companies.id',
        'companies.name',
        'companies.created_at',
        'companies.updated_at'
    ];

    public function __construct(Company $company)
    {
        $this->model = $company;
    }

    public function all($paginate)
    {
        $limit = $paginate['rowsPerPage']??0;
        $start = $paginate['page']??-1;
        $search = $paginate['search']??null;

        $totaldata = $this->model->count();

        $query = $this->model->select($this->fields)
            ->orderBy('id', 'desc');

        if($limit && $start!=-1) {
            $query = $query
                ->skip($start)
                ->take($limit);
        }

        $query  = $query->get();
        $totalFiltered  = $query->count();

        $json_response = [
            "recordsTotal"      => $totaldata,
            "recordsFiltered"   => $totalFiltered,
            "data"              => $query->toArray()
        ];

        return $json_response;
    }

    public function find($id)
    {
        try {
            return $this->model->select($this->fields)
                ->where('companies.id', '=', $id)
                ->first();
        } catch (ModelNotFoundException $ex) {
            return [
                'message' => 'No se ha encontrado'
            ];
        }
    }

    public function update(array $data, $id)
    {
        try {
            $result = $this->model->findOrFail($id);
            $result->update($data);
            return $this->find($id);
        } catch (ModelNotFoundException $ex) {
            return [
                'message' => 'No se ha encontrado'
            ];
        }
    }

    public function create(array $data)
    {
        $result = $this->model->create($data);
        return $this->find($result->id);
    }

    public function delete($id)
    {
        // TODO: Implement delete() method.
    }

}