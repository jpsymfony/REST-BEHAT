<?php

namespace App\ApiAuthenticationBundle\Tests\Controller;

use App\ApiAuthenticationBundle\Tests\WebTestCase;
use Symfony\Component\HttpKernel\Client;

/**
 * AuthenticationControllerTest
 *
 * @author Nicolas Cabot <n.cabot@lexik.fr>
 */
class AuthenticationControllerTest extends WebTestCase
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->client = static::createClient();
    }

    /**
     * test login
     */
    public function testLoginFailure()
    {
        $data = array(
            'username' => 'user',
            'password' => 'userwrongpass',
        );

        $this->client->request('POST', $this->getUrl('login_check'), $data);
        $this->assertJsonResponse($this->client->getResponse(), 401);
    }

    /**
     * test login
     */
    public function testLoginSuccess()
    {
        $data = array(
            'username' => 'user',
            'password' => 'password',
        );

        $this->client->request('POST', $this->getUrl('login_check'), $data);
        $this->assertJsonResponse($this->client->getResponse(), 200);

        $response = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('token', $response);
        $this->assertArrayHasKey('data', $response);
    }
}
