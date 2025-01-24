<?php

namespace App\Interfaces;

interface NewsRepositoryInterface
{
    public function all();

    public function create(array $data);

    public function update(array $data, $id);
    
    public function updateOrCreate(array $data);

    public function delete($id);

    public function find($id);
}
