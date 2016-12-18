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
    
    public function fetch_customers(CustomerFiltration $filtration = NULL, $limit = NULL, $offset = NULL, $licenses = NULL)
    {
        $data = array(
            'limit' => $limit,
            'offset' => $offset,
            'with' => $licenses,
        );

        if ($filtration) {
            $data = array_merge($data, $filtration->body());
        }
        
        $query_string = http_build_query($data);
        $url = rtrim(trim(DevMateConstants::BASE_URL), '/') . "/v2/customers";
        if (!empty($query_string)) {
            $url = $url . '?' . $query_string;
        }
        
        list($httpStatus, $response) = $this->_request($url);

        $response_object = json_decode($response, true);
        $customer_objects = $response_object["data"];
        
        $customers = array();
        foreach ($customer_objects as $object) {
            $customers[] = new CustomerModel($object);
        }
        
        return array($httpStatus, $customers);
    }

    public function fetch_customer($customer_id)
    {
        $url = rtrim(trim(DevMateConstants::BASE_URL), '/') . "/v2/customers/" . $customer_id;

        list($httpStatus, $response) = $this->_request($url);

        $response_object = json_decode($response, true);
        $customer_object = $response_object["data"];
        $customer = new CustomerModel($customer_object);

        return array($httpStatus, $customer);
    }
    
    public function create_customer($email, $first_name = "", $last_name = "", $company = "", 
                                    $phone = "", $address = "", $note = "")
    {
        $data = array(
            'data' => array(
                'email' => $email,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'company' => $company,
                'phone' => $phone,
                'address' => $address,
                'note' => $note
            )
        );

        $url = rtrim(trim(DevMateConstants::BASE_URL), '/') . "/v2/customers";
        list($httpStatus, $response) = $this->_request($url, json_encode($data), "POST");

        $response_object = json_decode($response, true);

        if ($httpStatus == 409) {
            $error_objects = $response_object["errors"][0];
            $title = $error_objects["title"];
            $detail = $error_objects["detail"];
            $ex = new \Exception($title . $detail, $httpStatus);
            throw $ex;
        }
        
        $customer_object = $response_object["data"];
        $customer = new CustomerModel($customer_object);
        
        return array($httpStatus, $customer);
    }
    
    public function create_license($custom_id, $license_type_id)
    {
        $data = array(
            "data" => array(
                "license_type_id" => $license_type_id
            )
        );
        
        $url = rtrim(trim(DevMateConstants::BASE_URL), '/') . "/v2/customers/" . $custom_id . "/licenses";

        list($httpStatus, $response) = $this->_request($url, json_encode($data), "POST");

        $response_object = json_decode($response, true);
        $license_object = $response_object["data"];
        $license = new LicenseModel($license_object);
        
        return array($httpStatus, $license);
    }
    
    private function _request($url, $data = "", $method = "GET")
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->getAuthHeader());
        if ($method == "POST") {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        if (!empty($method)) {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        }
        
        $response = curl_exec($ch);
        $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        // Get Request and Response Headers
//        $requestHeaders = curl_getinfo($ch, CURLINFO_HEADER_OUT);
        // Using alternative solution to CURLINFO_HEADER_SIZE as it throws invalid number when called using PROXY.
        if (function_exists('mb_strlen')) {
            $responseHeaderSize = mb_strlen($response, '8bit') - curl_getinfo($ch, CURLINFO_SIZE_DOWNLOAD);
//            $responseHeaders = mb_substr($response, 0, $responseHeaderSize, '8bit');
            $response = mb_substr($response, $responseHeaderSize, mb_strlen($response), '8bit');
        } else {
            $responseHeaderSize = strlen($response) - curl_getinfo($ch, CURLINFO_SIZE_DOWNLOAD);
//            $responseHeaders = substr($response, 0, $responseHeaderSize);
            $response = substr($response, $responseHeaderSize);
        }

        if (curl_errno($ch)) {
            // TODO
        }

        curl_close($ch);

        return array($httpStatus, $response);
    }

    private function getAuthHeader()
    {
        if (!empty($this->api_key)) {
            return array(
                "Authorization: Token " . $this->api_key,
                "Content-Type: application/json"
            );
        }
    }

}
