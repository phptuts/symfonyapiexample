<?php


namespace App\Tests\Unit\Model;


use App\Entity\User;
use App\Model\PaginatedResponseModel;
use App\Tests\HelperTrait;
use PHPUnit\Framework\TestCase;

/**
 * Class PaginatedResponseModelTest
 * @package App\Tests\Unit\Model
 */
class PaginatedResponseModelTest extends TestCase
{
    use HelperTrait;

    /**
     * Tests the structure of a paginated response
     */
    public function testPaginatedModel()
    {
        $user1 = new User();
        $user2 = new User();
        $this->setId($user1, 1);
        $this->setId($user2, 2);
        $user1->setName('blue1')->setEmail('blue1')->onCreated();
        $user2->setName('blue2')->setEmail('blue2')->onCreated();

        $responseModel = new PaginatedResponseModel([$user1, $user2], 'user', 1, 1, 10);

        $this->assertEquals([
            'meta' => [
                'type' => 'user',
                'paginated' => true,
                'total_pages' => 1,
                'page' => 1,
                'version' => '1.0.0',
                'page_size' => 10
            ],
            'data' => [
                $user1->view(),
                $user2->view()
            ]
        ], $responseModel->toArray());
    }
}