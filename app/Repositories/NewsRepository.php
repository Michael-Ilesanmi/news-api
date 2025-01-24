<?php

namespace App\Repositories;

use App\Models\News;
use App\Interfaces\NewsRepositoryInterface;

class NewsRepository implements NewsRepositoryInterface
{
    public function all()
    {
        return News::all();
    }

    public function getQuery()
    {
        return News::query();
    }

    public function create(array $data)
    {
        return News::create($data);
    }

    public function update(array $data, $id)
    {
        $news = News::update($id);
        $news->update($data);

        return $news;
    }

    public function updateOrCreate($data)
    {
        return News::updateOrCreate(['url'=>$data['url']], $data) ;
    }

    public function delete($id)
    {
        $news = News::findOrFail($id);
        $news->delete();
    }

    public function find($id)
    {
        return News::findOrFail($id);
    }
}
