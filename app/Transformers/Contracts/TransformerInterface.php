<?php
/**
 * This file is part of php-endpoint-bootstrap project
 * 
 * @package App\Transformers\Contracts
 * @author Yan Naing <yannaing@hexcores.com>
 * Date: 7/2/15
 * Time: 12:36 PM
 */

namespace App\Transformers\Contracts;


interface TransformerInterface {

    public function transform($data);

}