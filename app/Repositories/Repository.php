<?php

namespace App\Repositories;

class Repository
{
    protected $model;

    public function get()
    {
        return $this->model::get();
    }

    public function find($id)
    {
        return $this->model::findOrFail($id);
    }
}
