<?php


namespace App\Tests\End2End;

use App\Entity\User;
use App\Model\ResponseModel;
use App\Repository\UserRepository;

/**
 * Class UserControllerTest
 * @package App\Tests\End2End
 */
class UserControllerTest extends BaseCase
{

    /**
     * Tests that the not blank validation is working
     */
    public function testBlankValidation()
    {

        $response = $this->sendRequest('POST', '/api/users', []);
        $this->assertFormErrors($response);
        $data = json_decode($response->getContent(), true);
        $errors = $data['data']['children'];
        $this->assertNotEmpty($errors['name']);
        $this->assertNotEmpty($errors['email']);
    }

    /**
     * This tests the email validation is on
     */
    public function testEmail()
    {
        $response = $this->sendRequest('POST', '/api/users', ['email' => 'bad_format', 'name' => 'exasd']);
        $this->assertFormErrors($response);

        $data = json_decode($response->getContent(), true);
        $this->assertNotEmpty($data['data']['children']['email']);
    }

    /**
     * This tests the duplicate email constraint is configure right
     */
    public function testDuplicateEmail()
    {
        $response = $this->sendRequest('POST', '/api/users', ['email' => 'example@gmail.com']);
        $this->assertFormErrors($response);
        $data = json_decode($response->getContent(), true);
        $errors = $data['data']['children'];

        $this->assertNotEmpty($errors['email']);
    }

    /**
     * Tests that you can't but blanks in for setting properties
     */
    public function testSettingCanNotBeBlank()
    {
        $response = $this->sendRequest('POST', '/api/users',
            [
                'email' => 'sd@gmail.com',
                'name' => 'noah',
                'settings' => [
                    ['name' => '', 'value' => ''],
                ]
            ]);
        $this->assertFormErrors($response);
        $data = json_decode($response->getContent(), true);
        $errors = $data['data']['children']['settings']['children'][0]['children'];

        // asserting that these can't be empty
        $this->assertNotEmpty($errors['name']);
        $this->assertNotEmpty($errors['value']);

    }

    /**
     * Tests that user can be created
     * @return User
     */
    public function testCreateUser()
    {
        $response = $this->sendRequest('POST', '/api/users',
            [
                'email' => 'random23@gmail.com',
                'name' => 'noah',
                'settings' => [
                    ['name' => 'blue', 'value' => 'test'],
                ]
            ]);

        $this->assertEquals(201, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $this->assertEquals(ResponseModel::USER_RESPONSE_TYPE, $data['meta']['type']);
        $this->assertEquals('random23@gmail.com', $data['data']['email']);
        /** @var UserRepository $repo */
        $repo = $this->em->getRepository(User::class);
        /** @var User $user */
        $user = $repo->findOneBy(['email' => 'random23@gmail.com']);

        $this->assertNotEmpty($user);
        $this->assertEquals('random23@gmail.com', $user->getEmail());
        $this->assertEquals('blue', $user->getSettings()->toArray()[0]->getName());

        return $user;
    }

    /**
     * Tests that user can be updated
     * @throws \Doctrine\ORM\ORMException
     */
    public function testUpdateUser()
    {
        /** @var UserRepository $repo */
        $repo = $this->em->getRepository(User::class);
        $originalUser = $repo->findOneBy(['email' => 'example@gmail.com']);
        $this->assertNotEquals('random23423423@gmail.com', $originalUser->getEmail());
        $this->assertNotEquals('bill', $originalUser->getName());

        $response = $this->sendRequest('PUT', '/api/users/' . $originalUser->getId(),
            [
                'email' => 'random23423423@gmail.com',
                'name' => 'bill',
                'settings' => [
                    ['name' => 'blue', 'value' => 'test'],
                ]
            ]);
        $this->assertEquals(200, $response->getStatusCode());

        $this->em->refresh($originalUser);
        $this->assertEquals('random23423423@gmail.com', $originalUser->getEmail());
        $this->assertEquals('bill', $originalUser->getName());
    }

    /**
     * Tests that you can get user
     */
    public function testGetUser()
    {

        /** @var UserRepository $repo */
        $repo = $this->em->getRepository(User::class);
        $originalUser = $repo->findOneBy(['email' => 'example@gmail.com']);

        $response = $this->sendRequest('GET', '/api/users/' . $originalUser->getId());
        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $this->assertEquals(ResponseModel::USER_RESPONSE_TYPE, $data['meta']['type']);
        $this->assertEquals($originalUser->getEmail(), $data['data']['email']);
        $this->assertEquals($originalUser->getName(), $data['data']['name']);
    }


    /**
     * Tests that you can delete a user
     */
    public function testDeleteUser()
    {

        /** @var UserRepository $repo */
        $repo = $this->em->getRepository(User::class);
        $originalUser = $repo->findOneBy(['email' => 'example@gmail.com']);
        $this->assertInstanceOf(User::class, $originalUser);
        $response = $this->sendRequest('DELETE', '/api/users/' . $originalUser->getId());
        $this->assertEquals(204, $response->getStatusCode());

        $refreshedUser = $repo->find(1);
        $this->assertNull($refreshedUser);
    }


    /**
     * Tests that paginated responses work
     */
    public function testPagination()
    {
        $response = $this->sendRequest('GET', '/api/users?page=3');
        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);

        $this->assertCount(4, $data['data']);
        $this->assertEquals(3, $data['meta']['total_pages']);
        $this->assertTrue($data['meta']['paginated']);
    }


}