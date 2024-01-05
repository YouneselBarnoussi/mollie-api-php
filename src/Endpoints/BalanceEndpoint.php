<?php

namespace Mollie\Api\Endpoints;

use Mollie\Api\Exceptions\ApiException;
use Mollie\Api\Resources\Balance;
use Mollie\Api\Resources\BalanceCollection;
use Mollie\Api\Resources\LazyCollection;

class BalanceEndpoint extends CollectionRestEndpoint
{
    const string RESOURCE_ID_PREFIX = 'bal_';

    protected string $resourcePath = "balances";

    /**
     * @inheritDoc
     */
    protected function getResourceCollectionObject(int $count, object $_links): BalanceCollection
    {
        return new BalanceCollection($this->client, $count, $_links);
    }

    /**
     * @inheritDoc
     */
    protected function getResourceObject(): Balance
    {
        return new Balance($this->client);
    }

    /**
     * Retrieve a single balance from Mollie.
     *
     * Will throw an ApiException if the balance id is invalid or the resource cannot be found.
     *
     * @param string $balanceId
     * @param array $parameters
     * @return \Mollie\Api\Resources\Balance|\Mollie\Api\Resources\BaseResource
     * @throws ApiException
     */
    public function get(string $balanceId, array $parameters = []): Balance
    {
        if (empty($balanceId) || strpos($balanceId, self::RESOURCE_ID_PREFIX) !== 0) {
            throw new ApiException("Invalid balance ID: '{$balanceId}'. A balance ID should start with '" . self::RESOURCE_ID_PREFIX . "'.");
        }

        return parent::rest_read($balanceId, $parameters);
    }

    /**
     * Retrieve the primary balance from Mollie.
     *
     * Will throw an ApiException if the balance id is invalid or the resource cannot be found.
     *
     * @param array $parameters
     * @return \Mollie\Api\Resources\Balance
     * @throws ApiException
     */
    public function primary(array $parameters = []): Balance
    {
        return parent::rest_read("primary", $parameters);
    }

    /**
     * Retrieves a collection of Balances from Mollie.
     *
     * @param string|null $from The first Balance ID you want to include in your list.
     * @param int|null $limit
     * @param array $parameters
     *
     * @return BalanceCollection
     * @throws \Mollie\Api\Exceptions\ApiException
     */
    public function page(?string $from = null, ?int $limit = null, array $parameters = []): BalanceCollection
    {
        return $this->rest_list($from, $limit, $parameters);
    }

    /**
     * Create an iterator for iterating over balances retrieved from Mollie.
     *
     * @param string $from The first Balance ID you want to include in your list.
     * @param int $limit
     * @param array $parameters
     * @param bool $iterateBackwards Set to true for reverse order iteration (default is false).
     *
     * @return LazyCollection
     */
    public function iterator(?string $from = null, ?int $limit = null, array $parameters = [], bool $iterateBackwards = false): LazyCollection
    {
        return $this->rest_iterator($from, $limit, $parameters, $iterateBackwards);
    }
}
