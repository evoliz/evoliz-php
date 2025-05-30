<?php

namespace Evoliz\Client\Model\Sales;

use Evoliz\Client\Model\BaseModel;

class Retention extends BaseModel
{
    /**
     * @var float Retention percent, this field is accepted only when retention option is enabled,
     * Required if retention date is given
     */
    public $percent;

    /**
     * @var string Retention date, this field is accepted only when retention option is enabled,
     * Required if retention percent is given
     */
    public $date;

    /**
     * @param array $data Array to build the object
     */
    public function __construct(array $data)
    {
        $this->percent = $data['percent'] ?? null;
        $this->date = $data['date'] ?? null;
    }
}
