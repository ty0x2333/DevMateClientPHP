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
    private $client;
    
    public function __construct($name = null, array $data = array(), $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->email = time() . "@gmail.com";
        $this->client = new Client(ClientTest::API_KEY);
    }

    public function testCreateCustomer()
    {
        list($status, $customer) = $this->client->create_customer($this->email);
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
        $this->client->create_customer($customer->email);
    }

    /**
     * @depends testCreateCustomer
     */
    public function testFetchCustomers()
    {
        list($status, $customers) = $this->client->fetch_customers();
        $this->assertEquals(200, $status);
        $this->assertGreaterThan(0, count($customers));
    }

    /**
     * @param CustomerModel $customer created customer
     * @depends testCreateCustomer
     */
    public function testFetchSpecificCustomer(CustomerModel $customer)
    {
        list($status, $fetched_customer) = $this->client->fetch_customer($customer->custom_id);
        $this->assertEquals(200, $status);
        $this->assertEquals($customer->custom_id, $fetched_customer->custom_id);
    }

    /**
     * @param CustomerModel $customer created customer
     * @depends testCreateCustomer
     */
    public function testCreateLicense(CustomerModel $customer)
    {
        $license_type_id = 1;
        list($status, $license) = $this->client->create_license($customer->custom_id, $license_type_id);
        $this->assertEquals(201, $status);
        $this->assertNotNull($license);
    }
}
