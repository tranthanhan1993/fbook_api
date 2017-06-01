<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\BookRepository;
use App\Http\Requests\Api\HomeFilterRequest;

class HomeController extends ApiController
{
    protected $bookSelect = [
        'id',
        'title',
        'description',
        'author',
        'publish_date',
        'total_page',
        'avg_star',
        'count_view',
        'status',
        'category_id',
        'office_id'
    ];

    protected $imageSelect = [
        'path',
        'size',
        'thumb_path',
        'target_id',
        'target_type',
    ];

    protected $categorySelect = [
        'id',
        'name',
    ];

    protected $officeSelect = [
        'id',
        'name',
    ];

    protected $bookRepository;

    public function __construct(BookRepository $bookRepository)
    {
        parent::__construct();
        $this->bookRepository = $bookRepository;
    }

    public function index()
    {
        $relations = [
            'image' => function ($q) {
                $q->select($this->imageSelect);
            }
        ];

        return $this->getData(function() use ($relations){
            $this->compacts['items'] = $this->bookRepository->getDataInHomepage($relations, $this->bookSelect);
        });
    }

    public function filter(HomeFilterRequest $request)
    {
        $filters = $request->get('filters') ?: [];
        $relations = [
            'image' => function ($q) {
                $q->select($this->imageSelect);
            },
            'category' => function ($q) {
                $q->select($this->categorySelect);
            },
            'office' => function ($q) {
                $q->select($this->officeSelect);
            }
        ];

        return $this->getData(function() use ($relations, $filters){
            $this->compacts['items'] = $this->bookRepository->getDataFilterInHomepage(
                $relations, $this->bookSelect, $filters
            );
        });
    }
}
