<?php
/**
 * This file is part of php-endpoint-bootstrap project
 * 
 * @package App\Iora
 * @author Yan Naing <yannaing@hexcores.com>
 * Date: 6/29/15
 * Time: 1:06 PM
 */

namespace App\Iora\Contract;

use League\Csv\Reader;

abstract class AbstractReader implements IoraInterface {

    protected function render()
    {
        $fileObject = new \SplFileObject($this->getPath());

        $reader = Reader::createFromFileObject($fileObject);
        $reader->setFlags(\SplFileObject::READ_AHEAD|\SplFileObject::SKIP_EMPTY);

        return $reader;
    }

    abstract public function getPath();

}