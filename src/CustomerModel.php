<?php
/**
 * Created by PhpStorm.
 * User: luckytianyiyan
 * Date: 16/12/16
 * Time: 下午8:44
 */

namespace Ty\DevMate;


class CustomerModel
{
    public $custom_id;
    public $email;
    public $first_name;
    public $last_name;
    public $company;
    public $phone;
    public $address;
    public $note;
    public $date_added;
    public $licenses;
    
    public function __construct($json)
    {
        $this->custom_id = $json["id"];
        $this->email = $json["email"];
        $this->first_name = $json["first_name"];
        $this->last_name = $json["last_name"];
        $this->company = $json["company"];
        $this->phone = $json["phone"];
        $this->address = $json["address"];
        $this->note = $json["note"];
        $this->date_added = $json["date_added"];
        $this->licenses = array();
        if (isset($json["licenses"])) {
            $license_objects = $json["licenses"];
            foreach ($license_objects as $obj) {
                $license = new LicenseModel($obj);
                $this->licenses[] = $license;
            }
        }
    }
}
