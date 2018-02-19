<?php

namespace Tests\AppBundle\Controller;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CartControllerTest extends WebTestCase
{
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        self::bootKernel();

        self::loadFixtures();

    }

    public function testCartOperationsTest()
    {
        $client = static::createClient();

        /** @var \Symfony\Bundle\FrameworkBundle\Routing\Router $router */
        $router = $client->getContainer()->get('router');
        $url = $router->generate('addCart', []);

        $client->request('POST', $url, [], [], ['HTTP_AUTHORIZATION' => 'Bearer client1']);

        $response = $client->getResponse();

        $responseData = json_decode($response->getContent());
        $cartId = $responseData->id;

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue(is_int($responseData->id));
        $this->assertTrue(is_array($responseData->products));
        $this->assertTrue(is_string($responseData->created_at));
        $this->assertTrue(is_string($responseData->updated_at));

        /*Test for existing cart*/

        $client->request('POST', $url, [], [], ['HTTP_AUTHORIZATION' => 'Bearer client1']);

        $response = $client->getResponse();

        $this->assertEquals(400, $response->getStatusCode());

        /*Test for non user request*/

        $client->request('POST', $url, [], [], ['HTTP_AUTHORIZATION' => '']);
        $response = $client->getResponse();
        $responseData = json_decode($response->getContent());
        $this->assertEquals(401, $response->getStatusCode());
        $this->assertEquals($responseData->error, 'access_denied');
        $this->assertEquals($responseData->error_description, 'OAuth2 authentication required');

        $this->getCartActionTest($cartId);
    }

    protected function getCartActionTest($cartId)
    {
        $client = static::createClient();
        /** @var \Symfony\Bundle\FrameworkBundle\Routing\Router $router */
        $router = $client->getContainer()->get('router');

        $url = $router->generate('getCart', ['cartId' => $cartId]);

        $client->request('GET', $url, [], [], ['HTTP_AUTHORIZATION' => 'Bearer client1']);

        $response = $client->getResponse();

        $responseData = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue(is_int($responseData->id));
        $this->assertTrue(is_array($responseData->products));
        $this->assertTrue(is_string($responseData->created_at));
        $this->assertTrue(is_string($responseData->updated_at));


        /*Non existing cart*/

        $url = $router->generate('getCart', ['cartId' => 999999]);

        $client->request('GET', $url, [], [], ['HTTP_AUTHORIZATION' => 'Bearer client1']);

        $response = $client->getResponse();

        $this->assertEquals(404, $response->getStatusCode());

        /*Test for non user request*/

        $client->request('GET', $url, [], [], ['HTTP_AUTHORIZATION' => '']);
        $response = $client->getResponse();
        $responseData = json_decode($response->getContent());
        $this->assertEquals(401, $response->getStatusCode());
        $this->assertEquals($responseData->error, 'access_denied');
        $this->assertEquals($responseData->error_description, 'OAuth2 authentication required');

        $this->addCartProductActionTest($cartId);
    }

    protected function addCartProductActionTest($cartId)
    {
        $client = static::createClient();

        /** @var \Symfony\Bundle\FrameworkBundle\Routing\Router $router */
        $router = $client->getContainer()->get('router');

        $url = $router->generate('addCartProduct', ['cartId' => $cartId, 'productId' => 3]);

        $client->request('POST', $url, [], [], ['HTTP_AUTHORIZATION' => 'Bearer client1']);

        $response = $client->getResponse();

        $this->assertEquals(204, $response->getStatusCode());

        /*Test for non user request*/

        $client->request('POST', $url, [], [], ['HTTP_AUTHORIZATION' => '']);
        $response = $client->getResponse();
        $responseData = json_decode($response->getContent());
        $this->assertEquals(401, $response->getStatusCode());
        $this->assertEquals($responseData->error, 'access_denied');
        $this->assertEquals($responseData->error_description, 'OAuth2 authentication required');

        $this->deleteCartProductActionTest($cartId);
    }

    protected function deleteCartProductActionTest($cartId)
    {
        $client = static::createClient();
        /** @var \Symfony\Bundle\FrameworkBundle\Routing\Router $router */
        $router = $client->getContainer()->get('router');

        $url = $router->generate('removeCartProduct', ['cartId' => $cartId, 'productId' => 3]);

        $client->request('DELETE', $url, [], [], ['HTTP_AUTHORIZATION' => 'Bearer client1']);

        $response = $client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());

        /*Test for non user request*/

        $client->request('DELETE', $url, [], [], ['HTTP_AUTHORIZATION' => '']);
        $response = $client->getResponse();
        $responseData = json_decode($response->getContent());
        $this->assertEquals(401, $response->getStatusCode());
        $this->assertEquals($responseData->error, 'access_denied');
        $this->assertEquals($responseData->error_description, 'OAuth2 authentication required');

        $this->updateCartActionTest($cartId);

    }

    public function testCartDelete()
    {
        $client = static::createClient();

        /** @var \Symfony\Bundle\FrameworkBundle\Routing\Router $router */
        $router = $client->getContainer()->get('router');
        $url = $router->generate('addCart', []);

        $client->request('POST', $url, [], [], ['HTTP_AUTHORIZATION' => 'Bearer client1']);

        $response = $client->getResponse();

        $responseData = json_decode($response->getContent());
        $cartId = $responseData->id;

        $this->deleteCartActionTest($cartId);
    }

    protected function deleteCartActionTest($cartId)
    {
        $client = static::createClient();

        /** @var \Symfony\Bundle\FrameworkBundle\Routing\Router $router */
        $router = $client->getContainer()->get('router');

        $url = $router->generate('deleteCart', ['cartId' => $cartId]);

        $client->request('DELETE', $url, [], [], ['HTTP_AUTHORIZATION' => 'Bearer client1']);

        $response = $client->getResponse();

        $this->assertEquals(204, $response->getStatusCode());

        /*Test for non user request*/

        $client->request('DELETE', $url, [], [], ['HTTP_AUTHORIZATION' => '']);
        $response = $client->getResponse();
        $responseData = json_decode($response->getContent());
        $this->assertEquals(401, $response->getStatusCode());
        $this->assertEquals($responseData->error, 'access_denied');
        $this->assertEquals($responseData->error_description, 'OAuth2 authentication required');
    }

    protected function updateCartActionTest($cartId)
    {
        $client = static::createClient();

        /** @var \Symfony\Bundle\FrameworkBundle\Routing\Router $router */
        $router = $client->getContainer()->get('router');

        $url = $router->generate('updateCart', ['cartId' => $cartId, 'submitted'=> true]);

        $client->request('PUT', $url, [], [], ['HTTP_AUTHORIZATION' => 'Bearer client1']);

        $response = $client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());

        /*Test for non user request*/

        $client->request('PUT', $url, [], [], ['HTTP_AUTHORIZATION' => '']);
        $response = $client->getResponse();
        $responseData = json_decode($response->getContent());
        $this->assertEquals(401, $response->getStatusCode());
        $this->assertEquals($responseData->error, 'access_denied');
        $this->assertEquals($responseData->error_description, 'OAuth2 authentication required');

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
