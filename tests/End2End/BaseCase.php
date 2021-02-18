<?php


namespace App\Tests\End2End;


use App\Model\ResponseModel;
use App\Service\FormErrorNormalizerOverride;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;

class BaseCase extends WebTestCase
{
    /**
     * @var KernelBrowser
     */
    protected $client;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * Boots the kernel and load the fixtures
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();

        self::bootKernel();

        // returns the real and unchanged service container
        $container = self::$kernel->getContainer();
        $this->em = $container
            ->get('doctrine')
            ->getManager();

        $loader = $container->get('fidry_alice_data_fixtures.loader.doctrine');         // For Doctrine ORM
        $files = [__DIR__ . '/../../src/DataFixtures/users_test.yaml'];
        $loader->load($files);
    }

    /**
     * Asserts values in form error response
     * @param Response $response
     */
    protected function assertFormErrors(Response $response)
    {
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $this->assertEquals(ResponseModel::FORM_ERROR, $data['meta']['type']);
        $this->assertNotEmpty($data['data']['children']);
    }

    /**
     * This Sends a request
     *
     * @param string $method
     * @param string $url
     * @param array|null $data
     * @return Response
     */
    protected function sendRequest(string $method, string $url, array $data = null)
    {
        $this->client->request(
            $method,
            $url,
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
            ],
            $data ? json_encode($data) : null
        );

        return $this->client->getResponse();
    }
}