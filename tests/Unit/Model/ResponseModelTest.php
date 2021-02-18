<?php


namespace App\Tests\Unit\Model;


use App\Entity\User;
use App\Model\ResponseModel;
use App\Tests\HelperTrait;
use PHPUnit\Framework\TestCase;

/**
 * Class ResponseModelTest
 * @package App\Tests\Unit\Model
 */
class ResponseModelTest extends TestCase
{

    use HelperTrait;

    /**
     * Tests the structure response when use array.  This happens with form errors
     */
    public function testArrayData()
    {
        $responseModel = new ResponseModel(['lol' => true], 'example');

        $this->assertEquals([
            'meta' => [
                'type' => 'example',
                'version' => '1.0.0',
                'paginated' => false
            ],
            'data' => [
                'lol' => true
            ]
        ], $responseModel->toArray());
    }

    /**
     * Tests a response when passing a View interface to it
     */
    public function testViewData()
    {
        $user = new User();
        $user->setEmail('e@gmailc.com')->setName('blue')->onCreated();
        $this->setId($user, 3);
        $responseModel = new ResponseModel($user, 'user');

        $this->assertEquals([
            'meta' => [
                'type' => 'user',
                'version' => '1.0.0',
                'paginated' => false
            ],
            'data' => $user->view()
        ], $responseModel->toArray());

    }
}