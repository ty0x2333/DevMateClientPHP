<?php
/**
 * Created by PhpStorm.
 * User: luckytianyiyan
 * Date: 16/12/16
 * Time: 上午1:55
 */

namespace Tests\Ty\DevMate;

use Ty\DevMate\Client;
use Ty\DevMate\CustomerModel;


class ClientTest extends \PHPUnit_Framework_TestCase
{
    const API_KEY = "1e8b252e67adeecc9b94fe139ca80240294ab56209c6d89d1f7437c28dc37713";
    
    private $email;
    
    public function __construct($name = null, array $data = array(), $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->email = time() . "@gmail.com";
    }

    public function testCreateCustomer()
    {
        $client = new Client(ClientTest::API_KEY);
        list($status, $customer) = $client->create_customer($this->email);
        $this->assertEquals(201, $status);
        $this->assertEquals($customer->email, $this->email);
        $this->assertEquals(0, count($customer->licenses));
        return $customer;
    }

    /**
     * @param CustomerModel $customer created customer
     * @depends testCreateCustomer
     */
    public function testDuplicateCreateCustomer(CustomerModel $customer)
    {
        $this->setExpectedException(\Exception::class, '', 409);
        $client = new Client(ClientTest::API_KEY);
        $client->create_customer($customer->email);
    }

    /**
     * @depends testCreateCustomer
     */
    public function testFetchCustomers()
    {
        $client = new Client(ClientTest::API_KEY);
        list($status, $customers) = $client->fetch_customers();
        $this->assertEquals(200, $status);
        $this->assertGreaterThan(0, count($customers));
    }

    /**
     * @param CustomerModel $customer created customer
     * @depends testCreateCustomer
     */
    public function testFetchSpecificCustomer(CustomerModel $customer)
    {
        $client = new Client(ClientTest::API_KEY);
        list($status, $fetched_customer) = $client->fetch_customer($customer->custom_id);
        $this->assertEquals(200, $status);
        $this->assertEquals($customer->custom_id, $fetched_customer->custom_id);
    }
    
    public function testCreateLicense()
    {
        $client = new Client(ClientTest::API_KEY);
        $customer_id = 2;
        $license_type_id = 1;
        list($status, $license) = $client->create_license($customer_id, $license_type_id);
        // maybe 200 or 201
        $this->assertGreaterThanOrEqual(200, $status);
        $this->assertNotNull($license);
    }
}
