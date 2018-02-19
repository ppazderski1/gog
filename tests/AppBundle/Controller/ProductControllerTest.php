<?php

namespace Tests\AppBundle\Controller;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductControllerTest extends WebTestCase
{
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        self::bootKernel();

        self::loadFixtures();

    }

    public function testAddProductAction()
    {
        $client = static::createClient();

        /** @var \Symfony\Bundle\FrameworkBundle\Routing\Router $router */
        $router = $client->getContainer()->get('router');
        $url = $router->generate('addProduct', ['name' => 'TEST', 'stock_size' => 10]);

        $client->request('POST', $url, [], [], ['HTTP_AUTHORIZATION' => 'Bearer admin']);

        $response = $client->getResponse();

        $responseData = json_decode($response->getContent());

        $productId = $responseData->id;

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue(is_int($responseData->id));
        $this->assertEquals($responseData->name, 'TEST');
        $this->assertEquals($responseData->stock_size, 10);
        $this->assertEquals($responseData->is_protected, false);


        /*Test for non user request*/

        $client->request('POST', $url, [], [], ['HTTP_AUTHORIZATION' => '']);
        $response = $client->getResponse();
        $responseData = json_decode($response->getContent());
        $this->assertEquals(401, $response->getStatusCode());
        $this->assertEquals($responseData->error, 'access_denied');
        $this->assertEquals($responseData->error_description, 'OAuth2 authentication required');

        /*Test for not in ACL*/

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

        $this->deleteProductActionTest($productId);
    }


    public function testUpdateProductAction()
    {
        $client = static::createClient();

        /** @var \Symfony\Bundle\FrameworkBundle\Routing\Router $router */
        $router = $client->getContainer()->get('router');
        $url = $router->generate('updateProduct', ['productId' => 2, 'stock_size' => 10, 'name' => 'Name changed']);

        $client->request('PUT', $url, [], [], ['HTTP_AUTHORIZATION' => 'Bearer admin']);

        $response = $client->getResponse();

        $this->assertEquals(204, $response->getStatusCode());

        /*Test for non user request*/

        $client->request('PUT', $url, [], [], ['HTTP_AUTHORIZATION' => '']);
        $response = $client->getResponse();
        $responseData = json_decode($response->getContent());
        $this->assertEquals(401, $response->getStatusCode());
        $this->assertEquals($responseData->error, 'access_denied');
        $this->assertEquals($responseData->error_description, 'OAuth2 authentication required');

        /*Test for not ACL*/

        $client->request('PUT', $url, [], [], ['HTTP_AUTHORIZATION' => 'Bearer client1']);
        $response = $client->getResponse();
        $responseData = json_decode($response->getContent());
        $this->assertEquals(403, $response->getStatusCode());
        $this->assertEquals($responseData->type, 'AccessDeniedHttpException');
        $this->assertEquals($responseData->message, 'Access Denied.');

        /*Test for unique name*/

        $url = $router->generate('updateProduct', ['productId' => 2, 'stock_size' => 10, 'name' => 'Icewind Dale']);
        $client->request('PUT', $url, [], [], ['HTTP_AUTHORIZATION' => 'Bearer admin']);
        $response = $client->getResponse();
        $this->assertEquals(400, $response->getStatusCode());
    }

    protected function deleteProductActionTest($productId)
    {

        $client = static::createClient();

        /** @var \Symfony\Bundle\FrameworkBundle\Routing\Router $router */
        $router = $client->getContainer()->get('router');

        /*Test deletion*/

        $url = $router->generate('deleteProduct', ['productId' => $productId]);

        $client->request('DELETE', $url, [], [], ['HTTP_AUTHORIZATION' => 'Bearer admin']);

        $response = $client->getResponse();

        $this->assertEquals(204, $response->getStatusCode());

        /*Test deletion of protected*/

        $url = $router->generate('deleteProduct', ['productId' => 1]);

        $client->request('DELETE', $url, [], [], ['HTTP_AUTHORIZATION' => 'Bearer admin']);

        $response = $client->getResponse();

        $this->assertEquals(400, $response->getStatusCode());

        /*Test for non user request*/

        $client->request('DELETE', $url, [], [], ['HTTP_AUTHORIZATION' => '']);
        $response = $client->getResponse();
        $responseData = json_decode($response->getContent());
        $this->assertEquals(401, $response->getStatusCode());
        $this->assertEquals($responseData->error, 'access_denied');
        $this->assertEquals($responseData->error_description, 'OAuth2 authentication required');

        /*Test for not ACL*/

        $client->request('DELETE', $url, [], [], ['HTTP_AUTHORIZATION' => 'Bearer client1']);
        $response = $client->getResponse();
        $responseData = json_decode($response->getContent());
        $this->assertEquals(403, $response->getStatusCode());
        $this->assertEquals($responseData->type, 'AccessDeniedHttpException');
        $this->assertEquals($responseData->message, 'Access Denied.');

    }

    public function testGetProductsAction()
    {
        $client = static::createClient();

        /** @var \Symfony\Bundle\FrameworkBundle\Routing\Router $router */
        $router = $client->getContainer()->get('router');
        $url = $router->generate('getProducts', ['page' => 1]);

        $client->request('GET', $url, [], [], ['HTTP_AUTHORIZATION' => 'Bearer client1']);

        $response = $client->getResponse();

        $responseData = json_decode($response->getContent());

        $responseData = $responseData[0];

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue(is_int($responseData->id));
        $this->assertEquals($responseData->id, 1);
        $this->assertEquals($responseData->name, 'Fallout');
        $this->assertEquals($responseData->is_protected, true);
        $this->assertTrue(is_int($responseData->price->amount));

        /*Test for non user request*/

        $client->request('GET', $url, [], [], ['HTTP_AUTHORIZATION' => '']);
        $response = $client->getResponse();
        $responseData = json_decode($response->getContent());
        $this->assertEquals(401, $response->getStatusCode());
        $this->assertEquals($responseData->error, 'access_denied');
        $this->assertEquals($responseData->error_description, 'OAuth2 authentication required');
    }

    public function testAddProductPrice()
    {
        $client = static::createClient();

        /** @var \Symfony\Bundle\FrameworkBundle\Routing\Router $router */
        $router = $client->getContainer()->get('router');
        $url = $router->generate('addProductPrice', ['productId' => '1', 'zoneId' => 1, 'value' => 100]);

        $client->request('POST', $url, [], [], ['HTTP_AUTHORIZATION' => 'Bearer admin']);

        $response = $client->getResponse();

        $responseData = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue(is_int($responseData->id));

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
