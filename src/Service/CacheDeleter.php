<?php

namespace App\Service;

use App\Repository\CustomerRepository;
use Symfony\Contracts\Cache\CacheInterface;

class CacheDeleter
{
    private $repo;

    public function __construct(CustomerRepository $customerRepository)
    {
        $this->repo = $customerRepository;
    }

    /**
     * @param CacheInterface $cache
     * @param null $id
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function deleteCache(CacheInterface $cache, $id = null)
    {
        if ($id) {
            $cache->delete('customer'.$id);
        }

        $customerCount = count($this->repo->findAll());

        $itemsPerPage = 10;
        $pageCount = (int) ceil($customerCount/$itemsPerPage);

        for ($i=1; $i < $pageCount; $i++) {
            $cache->delete('customers_list'.$i);
        }
    }
}
