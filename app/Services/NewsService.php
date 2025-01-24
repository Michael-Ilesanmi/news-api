<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Repositories\NewsRepository;

class NewsService
{
    /**
     * Create a new class instance.
     */
    public function __construct(protected NewsRepository $newsRepository) {}

    public function create(array $data)
    {
        return $this->newsRepository->create($data);
    }

    public function update($data)
    {
        return $this->newsRepository->updateOrCreate($data);
    }

    /**
     * Build a query based on the provided request parameters.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Database\Query\Builder
     */
    public function search($request)
    {
        $query = $this->newsRepository->getQuery();

        $this->applyFilters($query, $request);

        return $query->paginate(20);
    }

    /**
     * Applies filters to the query based on the request parameters.
     */
    private function applyFilters($query, $request)
    {
        $this->applyFilter($query, $request, 'sources', 'source');
        $this->applyFilter($query, $request, 'categories', 'category');
        $this->applyFilter($query, $request, 'authors', 'author');
        $this->applyDateFilter($query, $request);
    }

    /**
     * Applies a date range filter to the query based on start_date and end_date parameters.
     */
    private function applyDateFilter($query, $request)
    {
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('published_at', [
                $request->start_date,
                $request->end_date
            ]);
        } elseif ($request->has('start_date')) {
            $query->where('published_at', '>=', $request->start_date);
        } elseif ($request->has('end_date')) {
            $query->where('published_at', '<=', $request->end_date);
        }
    }

    /**
     * Applies a filter to the query based on the request parameter.
     *
     * @param string $parameter The request parameter to check.
     * @param string $column The column to filter on.
     */
    private function applyFilter($query, $request, $parameter, $column)
    {
        if ($request->has($parameter)) {
            $values = explode(',', $request->$parameter);
            $query->where(function ($q) use ($column, $values) {
                for ($i = 0; $i < count($values); $i++) {
                    $q->orWhere($column, 'like',  '%' . $values[$i] . '%');
                }
            });
        }
    }
}
