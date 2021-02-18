<?php


namespace App\Tests\Functional\Entity;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Tests\Functional\BaseCase;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class UserTest
 * @package App\Tests\Functional\Entity
 */
class UserTest extends BaseCase
{

    /**
     * Tests that the preperist lifecycle event is trigger
     */
    public function testUserCreatedAtFieldIsSet()
    {
        $user = new User();
        $user->setEmail('ex@gmail.com')
            ->setIsActive(true)
            ->setName('bill');
        /** @var EntityManagerInterface $em */
        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');
        $em->persist($user);
        $em->flush();

        /** @var UserRepository $userRepo */
        $userRepo = $this->getContainer()->get(UserRepository::class);

        $userRefreshed = $userRepo->find($user->getId());

        $this->assertInstanceOf(DateTime::class, $userRefreshed->getCreatedAt());
        $this->assertInstanceOf(DateTime::class, $userRefreshed->getUpdatedAt());

    }

    /**
     * Tests that preupdate lifecycle event is trigger
     */
    public function testUserUpdatedAtFieldIsSet()
    {
        /** @var UserRepository $userRepo */
        $userRepo = $this->getContainer()->get(UserRepository::class);

        /** @var EntityManagerInterface $em */
        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');

        $user = $userRepo->findOneBy(['email' => 'example@gmail.com']);
        $this->assertNotEquals('Fred23', $user->getName());
        $oldDate = $user->getUpdatedAt();
        $user->setName('Fred23');

        // you don't have to call persist in later version of symfony when updating
        $em->flush();

        $userRefreshed = $userRepo->findOneBy(['email' => 'example@gmail.com']);

        // Test that the old date is being set
        $this->assertGreaterThan($oldDate, $userRefreshed->getUpdatedAt());
        // Asserting that the data changed
        $this->assertEquals('Fred23', $userRefreshed->getName());
    }
}