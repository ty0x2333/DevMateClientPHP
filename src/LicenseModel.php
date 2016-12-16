<?php
/**
 * Created by PhpStorm.
 * User: luckytianyiyan
 * Date: 16/12/17
 * Time: 上午1:26
 */

namespace Ty\DevMate;


class LicenseModel
{
    public $license_id;
    public $license_type_name;
    public $license_type_id;
    public $invoice;
    public $campaign;
    public $date_created;
    public $activations_used;
    public $expiration_date;
    public $products;
    public $is_subscription;
    public $status;
    public $activations_total;
    public $activation_key;
    public $history;
    public function __construct($json)
    {
        $this->license_id = $json["id"];
        $this->license_type_name = $json["license_type_name"];
        $this->license_type_id = $json["license_type_id"];
        $this->invoice = $json["invoice"];
        $this->campaign = $json["campaign"];
        $this->date_created = $json["date_created"];
        $this->activations_used = $json["activations_used"];
        $this->expiration_date = $json["expiration_date"];
        $this->products = $json["products"];
        $this->is_subscription = $json["is_subscription"];
        $this->status = $json["status"];
        $this->activations_total = $json["activations_total"];
        $this->activation_key = $json["activation_key"];
        $this->history = $json["history"];
    }
}
