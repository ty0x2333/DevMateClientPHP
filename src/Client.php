<?php
/**
 * Created by PhpStorm.
 * User: luckytianyiyan
 * Date: 16/12/16
 * Time: 上午12:43
 */

namespace Ty\DevMate;


class Client
{
    public $api_key;

    public function __construct($api_key)
    {
        $this->api_key = $api_key;
    }
}