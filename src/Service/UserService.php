<?php


namespace App\Service;


use App\Entity\User;
use App\Model\PaginatedResponseModel;
use App\Model\ResponseModel;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class UserService
 * @package App\Service
 */
class UserService
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var UserRepository
     */
    private $repository;

    public function __construct(EntityManagerInterface  $em, UserRepository  $repository)
    {
        $this->em = $em;
        $this->repository = $repository;
    }

    /**
     * Save a user
     *
     * @param User $user
     */
    public function save(User $user) {
        $this->em->persist($user);
        $this->em->flush();
    }

    /**
     * Finds a user by id
     *
     * @param int $id
     * @return User|null
     */
    public function findUserById(int $id) {
        return $this->repository->find($id);
    }

    /**
     * Deletes a user
     * @param User $user
     */
    public function delete(User $user) {
        $this->em->remove($user);
        $this->em->flush();
    }

    /**
     * Returns a paginated response model
     *
     * @param int $page
     * @return PaginatedResponseModel
     */
    public function getPaginatedResponse($page = 1) {
       $count = $this->repository->count([]);
       $page = $page >= 1 ? $page - 1 : 0;
       $users = $this->repository->getUsers($page);
       $totalPages = ceil($count / UserRepository::PAGE_SIZE);
       return new PaginatedResponseModel($users, ResponseModel::USER_RESPONSE_TYPE, $totalPages, $page + 1, UserRepository::PAGE_SIZE);
    }
}