<?php

namespace Tests\AppBundle\Controller;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CurrencyZoneControllerTest extends WebTestCase
{
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        self::bootKernel();

        self::loadFixtures();

    }

    public function testAddCurrencyZoneAction()
    {
        $client = static::createClient();

        /** @var \Symfony\Bundle\FrameworkBundle\Routing\Router $router */
        $router = $client->getContainer()->get('router');
        $url = $router->generate('addCurrencyZone', ['name' => 'TEST', 'currency' => 'AOA', 'locale' => 'en_US']);

        $client->request('POST', $url, [], [], ['HTTP_AUTHORIZATION' => 'Bearer admin']);

        $response = $client->getResponse();

        $responseData = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue(is_int($responseData->id));
        $this->assertEquals($responseData->name, 'TEST');
        $this->assertEquals($responseData->currency, 'AOA');
        $this->assertEquals($responseData->locale, 'en_US');

        /*Test for non user request*/

        $client->request('POST', $url, [], [], ['HTTP_AUTHORIZATION' => '']);
        $response = $client->getResponse();
        $responseData = json_decode($response->getContent());
        $this->assertEquals(401, $response->getStatusCode());
        $this->assertEquals($responseData->error, 'access_denied');
        $this->assertEquals($responseData->error_description, 'OAuth2 authentication required');

        /*Test for not ACL*/

        $client->request('POST', $url, [], [], ['HTTP_AUTHORIZATION' => 'Bearer client1']);
        $response = $client->getResponse();
        $responseData = json_decode($response->getContent());
        $this->assertEquals(403, $response->getStatusCode());
        $this->assertEquals($responseData->type, 'AccessDeniedHttpException');
        $this->assertEquals($responseData->message, 'Access Denied.');

        /*Test for duplicate entry*/

        $client->request('POST', $url, [], [], ['HTTP_AUTHORIZATION' => 'Bearer admin']);
        $response = $client->getResponse();
        $this->assertEquals(400, $response->getStatusCode());
    }

    protected static function loadFixtures(): void
    {
        $em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $loader = new Loader();
        $loader->addFixture( new \Application\DataFixtures\ORM\CurrencyZone());
        $loader->addFixture(new \Application\DataFixtures\ORM\User());
        $loader->addFixture(new \Application\DataFixtures\ORM\OAuth());
        $loader->addFixture(new \Application\DataFixtures\ORM\Product());
        $loader->addFixture(new \Application\DataFixtures\ORM\Price());

        $purger = new ORMPurger($em);
        $executor = new ORMExecutor($em, $purger);
        $executor->execute($loader->getFixtures());
    }
}
