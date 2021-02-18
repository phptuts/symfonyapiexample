<?php


namespace App\Model;


/**
 * This is used to produce json the class
 * Class ResponseModel
 * @package App\Model
 */
class ResponseModel implements ResponseModelInterface
{
    const USER_RESPONSE_TYPE = 'user';

    const FORM_ERROR = 'form_error';

    /**
     * @var ViewInterface|array
     */
    private $data;

    /**
     * @var string
     */
    private $type;
    /**
     * @var string
     */
    private $version;

    public function __construct($data, $type, $version = '1.0.0') {
        $this->data = $data;
        $this->type = $type;

        $this->version = $version;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray(): array {
        $data = is_array($this->data) ? $this->data : $this->data->view();
        return [
            'meta' => [
                // type is used so that the frontend can easily create parsers
                'type' => $this->type,
                'version' => $this->version,
                'paginated' => false
            ],
            'data' => $data
        ];
    }
}