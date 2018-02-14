<?php
/**
 * Created by PhpStorm.
 * User: mamazu
 * Date: 14/02/18
 * Time: 23:15
 */

namespace App\Services;


interface PHPStreamsInterface
{
    /**
     * Returns the content of php://input
     *
     * @return string
     */
    public function getInput(): string;
}