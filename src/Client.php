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
    
    public function getAuthHeader()
    {
        if (!empty($this->api_key)) {
            return array(
                'Authorization: Token ' . $this->api_key
            );
        }
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
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->getAuthHeader());
        
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

        curl_close($ch);

        $response_object = json_decode($response, true);
        $customer_objects = $response_object["data"];
        
        $customers = array();
        foreach ($customer_objects as $object) {
            $customers[] = new CustomerModel($object);
        }
        
        return array($httpStatus, $customers);
    }

}
