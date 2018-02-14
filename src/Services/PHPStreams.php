<?php
/**
 * Created by PhpStorm.
 * User: mamazu
 * Date: 14/02/18
 * Time: 23:14
 */

namespace App\Services;


class PHPStreams implements PHPStreamsInterface
{
    public function getInput(): string
    {
        $content = file_get_contents('php://input');
        return is_bool($content) ? '' : $content;
    }
}