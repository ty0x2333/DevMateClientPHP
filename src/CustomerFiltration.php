<?php
/**
 * Created by PhpStorm.
 * User: luckytianyiyan
 * Date: 16/12/16
 * Time: 上午1:34
 */

namespace Ty\DevMate;


class CustomerFiltration
{
    public $email;
    public $first_name;
    public $last_name;
    public $company;
    public $phone;
    public $address;
    public $key;
    public $identifier;
    public $invoice;
    public $order_id;
    public $activation_id;
    public $limit;
    public $offset;
    public $licenses;
    
    public function body()
    {
        return array(
            'filter[email]' => $this->email,
            'filter[first_name]' => $this->first_name,
            'filter[last_name]' => $this->last_name,
            'filter[company]' => $this->company,
            'filter[phone]' => $this->phone,
            'filter[address]' => $this->address,
            'filter[key]' => $this->key,
            'filter[identifier]' => $this->identifier,
            'filter[invoice]' => $this->invoice,
            'filter[order_id]' => $this->order_id,
            'filter[activation_id]' => $this->activation_id
        );
    }
}
