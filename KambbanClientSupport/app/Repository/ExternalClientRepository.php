<?php

namespace App\Repository;

use App\Interfaces\RepositoriesInterface;
use App\Models\ExternalClient;
use App\Traits\RepositoryTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

class ExternalClientRepository implements RepositoriesInterface
{

    use RepositoryTrait;

    private $model;
    private $fields = [
        'external_clients.id',
        'external_clients.name',
        'external_clients.nit',
        'external_clients.attrs',
        'external_clients.internal_client_id',
        'external_clients.created_at',
        'external_clients.updated_at'
    ];

    public function __construct(ExternalClient $externalClient)
    {
        $this->model = $externalClient;
    }

    public function all($paginate)
    {
        $limit = $paginate['rowsPerPage'] ?? 0;
        $start = $paginate['page'] ?? -1;

        $totaldata = $this->model->count();

        $query = $this->model->select($this->fields)
            ->with('internalClient')
            ->orderBy('id', 'desc');

        if ($limit && $start != -1) {
            $query = $query
                ->skip($start)
                ->take($limit);
        }

        $query = $query->get();
        $totalFiltered = $query->count();

        $json_response = [
            "recordsTotal" => $totaldata,
            "recordsFiltered" => $totalFiltered,
            "records" => $query->toArray()
        ];

        return $json_response;
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

    public function find($id)
    {
        try {
            return $this->model->select($this->fields)
                ->where('external_clients.id', '=', $id)
                ->with('internalClient')
                ->first();
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
