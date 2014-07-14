<?php
/**
 * Manager
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License, that is bundled with this
 * package in the file LICENSE.
 * It is also available through the world-wide-web at this URL:
 *
 * http://opensource.org/licenses/MIT
 *
 * If you did not receive a copy of the license and are unable to obtain it
 * through the world-wide-web, please send an email
 * to <magetest@sessiondigital.com> so we can send you a copy immediately.
 *
 * @copyright  Copyright (c) 2014 MageTest team and contributors.
 */
namespace MageTest\Manager;

use Behat\Mink\Driver\GoutteDriver;
use Behat\Mink\Mink;
use Behat\Mink\Session;
use Behat\Mink\WebAssert;

class CustomerTest extends WebTestCase
{
    private $fixtures;
    private $customer;

    protected function setUp()
    {
        parent::setUp();
        $this->fixtures = new Customer();
    }

    protected function tearDown()
    {
        if ($this->customer) {
            \Mage::app()->setCurrentStore(\Mage_Core_Model_App::ADMIN_STORE_ID);
            $this->fixtures->delete();
            \Mage::app()->setCurrentStore(\Mage_Core_Model_App::DISTRO_STORE_ID);
        }
        parent::tearDown();
    }

    public function testCreatesCustomerWithEmailAndFirstName()
    {
        $email = 'test@example.com';
        $pass = 'qwerty123';

        $this->customer = $this->fixtures->create($this->getCustomerAttributes($email, $pass));

        $this->customerLogin($email, $pass);

        $this->assertSession()->addressEquals('/customer/account/');
    }

    public function testDeletesCustomer()
    {
        $email = 'test@example.com';
        $pass = 'qwerty123';

        $this->customer = $this->fixtures->create($this->getCustomerAttributes($email, $pass));
        $this->fixtures->delete();

        $this->customerLogin($email, $pass);

        $this->assertSession()->addressEquals('/customer/account/login/');
    }
}