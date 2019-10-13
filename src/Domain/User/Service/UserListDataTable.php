<?php

namespace App\Domain\User\Service;

use App\Domain\Service\ServiceInterface;
use App\Domain\User\Repository\UserListDataTableRepository;

/**
 * Service.
 */
final class UserListDataTable implements ServiceInterface
{
    /**
     * @var UserListDataTableRepository
     */
    private $repository;

    /**
     * Constructor.
     *
     * @param UserListDataTableRepository $repository The repository
     */
    public function __construct(UserListDataTableRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * List all users.
     *
     * @param array $params The parameters
     *
     * @return array The result
     */
    public function listAllUsers(array $params): array
    {
        return $this->repository->getTableData($params);
    }
}
