<?php


namespace App\Model;

/**
 * Class PaginatedResponseModel
 * @package App\Model
 */
class PaginatedResponseModel implements ResponseModelInterface
{
    /**
     * @var ViewInterface[]
     */
    private $data;

    /**
     * @var string
     */
    private $type;
    /**
     * @var int
     */
    private $totalPages;
    /**
     * @var int
     */
    private $page;

    /**
     * @var string
     */
    private $version;

    /**
     * @var int
     */
    private $pageSize;

    public function __construct($data, $type, $totalPages, $page, $pageSize, $version = '1.0.0')
    {

        $this->data = $data;
        $this->type = $type;
        $this->totalPages = $totalPages;
        $this->page = $page;
        $this->version = $version;
        $this->pageSize = $pageSize;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray(): array
    {
        return [
            'meta' => [
                'type' => $this->type,
                'paginated' => true,
                'total_pages' => $this->totalPages,
                'page' => $this->page,
                'version' => $this->version,
                'page_size' => $this->pageSize
            ],
            'data' => array_map(function (ViewInterface $view) {
                return $view->view();
            }, $this->data)
        ];
    }

}