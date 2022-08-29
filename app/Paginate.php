<?php

namespace App;

class Paginate {
    public int $total;
    public int $count;
    public $data;
    public int $perPage;
    public $range;

    public function __construct(array $items, int $page = 1, $perPage = 8)
    {
        // $this->data = collect($items)->forPage(1, 5);
        $this->data = \collect($items)->forPage($page, $perPage);
        $this->total = \count($items);
        $this->count = \count($this->data);
        $this->perPage = $perPage;
        $this->range = \collect($items)->offsetGet(3);
    }

    public function page()
    {
        
    }

    public function perPage(int $perPage = 5)
    {
        // $this->data = (array_splice($this->data, 0, $perPage - $this->total));
        // $this->data = (array_splice($this->data, 0, -4));
        return $this->perPage = $perPage;
    }

    public function result() {
        return (object) [
            'total' => $this->total,
            'count' => $this->count,
            'data' => $this->data,
            'per_page' => $this->perPage,
            'range' => $this->range
        ];
    }
}