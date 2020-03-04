<?php

namespace App\Repository;

use App\Interfaces\RepositoriesInterface;
use App\Models\User;
use App\Traits\RepositoryTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class ExternalUserRepository implements RepositoriesInterface{

    use RepositoryTrait;

    private $model;
    private $fields = [
        'external_users.id',
        'external_users.name',
        'external_users.username',
        'external_users.email',
        'external_users.password',
        'external_users.external_user_type_id',
        'external_users.external_client_id',
        'external_users.created_at',
        'external_users.updated_at',
        'external_users.remember_token'
    ];

    public function __construct(User $user){
        $this->model = $user;
    }

    public function all($paginate)
    {
        $limit = $paginate['rowsPerPage']??0;
        $start = $paginate['page']??-1;
        $search = $paginate['search']??null;

        $totaldata = $this->model->count();

        $query = $this->model->select($this->fields)
            ->with('externalUserType')
            ->with('externalClient')
            ->orderBy('id', 'desc');

        if($limit && $start!=-1){
            $query = $query
                ->skip($start)
                ->take($limit);
        }

        $query = $query->get();
        $totalFiltered = $query->count();

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

                ->where('users.id', '=', $id)
                ->with('externalUserType')
                ->with('externalClient')
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



