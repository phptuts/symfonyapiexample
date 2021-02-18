<?php


namespace App\Tests\Functional;


use App\Kernel;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class BaseCase
 * @package App\Tests\Functional
 */
class BaseCase extends TestCase
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Boots the kernel
     */
    protected function setUp(): void
    {
        parent::setUp();
        $kernel = new Kernel("test", true);
        $kernel->boot();
        $this->container = $kernel->getContainer();


        $loader = $this->getContainer()->get('fidry_alice_data_fixtures.loader.doctrine');         // For Doctrine ORM
        $files = [__DIR__ . '/../../src/DataFixtures/users_test.yaml'];
        $loader->load($files);
    }

    /**
     * @return ContainerInterface
     */
    protected function getContainer(): ContainerInterface
    {
        return $this->container;
    }
}