<?php

namespace App\Repository;

use App\Models\RequestType;
use App\Interfaces\RepositoriesInterface;
use App\Models\UserPermission;
use App\Traits\RepositoryTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;


class UserPermissionRepository implements RepositoriesInterface
{

    use RepositoryTrait;

    private $model;
    private $fields = [
        'user_permissions.id',
        'user_permissions.permission',
        'user_permissions.description',
        'user_permissions.attrs',
        'user_permissions.created_at',
        'user_permissions.updated_at'
    ];

    public function __construct(UserPermission $userPermission)
    {
        $this->model = $userPermission;
    }

    public function all($paginate)
    {
        $limit = $paginate['rowsPerPage'] ?? 0;
        $start = $paginate['page'] ?? -1;

        $totaldata = $this->model->count();

        $query = $this->model->select($this->fields)
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

    public function find($id)
    {
        try {
            return $this->model->select($this->fields)
                ->where('user_permissions.id', '=', $id)
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
