<?php
/**
 * (c) Ismael Shop <i.Shop@gmail.com>
 *
 * @link        http://www.ismaelShop.com
 * @copyright   Copyright (c) Ismael Shop. (http://www.ismaelShop.com)
 * @license     MIT License - http://en.wikipedia.org/wiki/MIT_License
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace tests\AppBundle;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApplicationAvailabilityFunctionalTest extends WebTestCase
{
    /**
     * @dataProvider roleAnnonymousUrlProvider
     */
    public function testRoleAnnonymousPages($url)
    {
        $client = self::createClient();
        $client->request('GET', $url);

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function roleAnnonymousUrlProvider()
    {
        return [
            ['/'],
        ];
    }

    /**
     * @dataProvider roleUserUrlProvider
     */
    public function testRoleUserPages($url)
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'iShop',
            'PHP_AUTH_PW'   => '1234',
        ));
        $client->request('GET', $url);

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function roleUserUrlProvider()
    {
        return [
            ['/'],
        ];
    }

    /**
     * @dataProvider roleAdminUrlProvider
     */
    public function testRoleAdminPages($url)
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => '1234',
        ));
        $client->request('GET', $url);

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function roleAdminUrlProvider()
    {
        return [
            ['/'],
        ];
    }
}