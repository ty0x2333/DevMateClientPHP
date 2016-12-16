<?php
/**
 * Created by PhpStorm.
 * User: luckytianyiyan
 * Date: 16/12/16
 * Time: 上午1:55
 */

namespace Tests\Ty\DevMate;

use Ty\DevMate\Client;


class ClientTest extends \PHPUnit_Framework_TestCase
{
    const API_KEY = "1e8b252e67adeecc9b94fe139ca80240294ab56209c6d89d1f7437c28dc37713";
    
    public function testFetchCustomers()
    {
        $client = new Client(ClientTest::API_KEY);
        list($status, $customers) = $client->fetch_customers();
        $this->assertEquals(200, $status);
        $this->assertGreaterThan(0, count($customers));
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
