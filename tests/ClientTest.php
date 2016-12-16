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
    public function testFetchCustomers()
    {
        $client = new Client('1e8b252e67adeecc9b94fe139ca80240294ab56209c6d89d1f7437c28dc37713');
        list($status, $customers) = $client->fetch_customers();
        $this->assertEquals(200, $status);
        $this->assertGreaterThan(0, count($customers));
    }
}
