<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use PHPUnit\Exception;
use Illuminate\Support\Facades\Validator;

trait RepositoryTrait{

    public function all($paginate){}

    public function find($id){
        try {
            return $this->model->find($id);
        }catch (ModelNotFoundException $ex){
            return [
                'message' => 'No se ha encontrado',
                "error" => $ex
            ];
        }
    }

    public function create(array $data){
        try {
            $result = $this->model->create($data);
            return $this->model->find($result->id);
        } catch (QueryException $ex){
            return [
                'Message' => 'Se ha producido un error',
                "error" => $ex
            ];
        }
    }

    public function update(array $data, $id){
        try {
            $result = $this->model->findOrFail($id);
            $result->update($data);
            return $this->model->find($id);
        }catch (ModelNotFoundException $ex){
            return [
                'message' => 'No se ha encontrado',
                "error" => $ex
            ];
        }
    }

    public function delete($id){
        try {
            $this->model->destroy($id);
            return $id;
        } catch (QueryException $ex){
            return [
                'message' => 'Se ha producido un error',
                "error" => $ex
            ];
        }
    }
}
