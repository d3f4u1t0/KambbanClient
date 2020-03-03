<?php

namespace App\Repository;

use App\Models\InternalClient;
use App\Interfaces\RepositoriesInterface;
use App\Traits\RepositoryTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class InternalClientRepository implements RepositoriesInterface{

    use RepositoryTrait;

    private $model;
    private $fields = [
        'internal_clients.id',
        'internal_clients.name',
        'internal_clients.nit',
        'internal_clients.attrs',
        'internal_clients.created_at',
        'internal_clients.updated_at'
    ];

    public function __construct(InternalClient $internalClient)
    {
        $this->model = $internalClient;
    }

    public function all($paginate)
    {
        $limit = $paginate['rowsPerPage']??0;
        $start = $paginate['page']??-1;

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
            "records"              => $query->toArray()
        ];

        return $json_response;
    }

    public function find($id)
    {
        try {
            return $this->model->select($this->fields)
                ->where('internal_clients.id', '=', $id)
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
        try {
            $this->model->destroy($id);
            return $id;
        } catch (QueryException $ex) {
            return [
                'message' => 'Se ha producido un error',
                'error' => $ex
            ];
        }
    }

}
