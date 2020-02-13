<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

Interface RepositoriesInterface
{
    public function all($paginate);
    public function create(array $data);
    public function update(array $data, $id);
    public function delete($id);
    public function find($id);
}