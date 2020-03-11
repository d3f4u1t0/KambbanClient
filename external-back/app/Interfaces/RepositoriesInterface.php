<?php

namespace App\Interfaces;

Interface RepositoriesInterface{
    public function all($paginate);
    public function create(array $data);
}
