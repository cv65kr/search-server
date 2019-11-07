<?php

/*
 * This file is part of the Apisearch Server
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 */

declare(strict_types=1);

namespace Apisearch\Plugin\Elastica\Domain;

use Apisearch\Repository\RepositoryReference;
use Apisearch\Server\Exception\ResponseException;
use Elastica\Index;
use Elastica\Request;
use Elasticsearch\Endpoints\AbstractEndpoint;
use React\Promise\PromiseInterface;

/**
 * Class ElasticaWithAppIdWrapper.
 */
abstract class WithElasticaWrapper implements AsyncRequestAccessor
{
    /**
     * @var ElasticaWrapper
     *
     * Elastica wrapper
     */
    protected $elasticaWrapper;

    /**
     * @var bool
     *
     * Refresh on write
     */
    protected $refreshOnWrite;

    /**
     * ElasticaSearchRepository constructor.
     *
     * @param ElasticaWrapper $elasticaWrapper
     * @param bool            $refreshOnWrite
     */
    public function __construct(
        ElasticaWrapper $elasticaWrapper,
        bool $refreshOnWrite
    ) {
        $this->elasticaWrapper = $elasticaWrapper;
        $this->refreshOnWrite = $refreshOnWrite;
    }

    /**
     * Normalize Repository Reference for cross index.
     *
     * @param RepositoryReference $repositoryReference
     *
     * @return RepositoryReference
     */
    protected function normalizeRepositoryReferenceCrossIndices(RepositoryReference $repositoryReference)
    {
        if (is_null($repositoryReference->getIndexUUID())) {
            return $repositoryReference;
        }

        $indices = $repositoryReference
            ->getIndexUUID()
            ->composeUUID();

        $appUUIDComposed = $repositoryReference
            ->getAppUUID()
            ->composeUUID();

        if ('*' === $indices) {
            return RepositoryReference::create(
                $appUUIDComposed,
                'all'
            );
        }

        $splittedIndices = explode(',', $indices);
        if (count($splittedIndices) > 1) {
            sort($splittedIndices);

            return RepositoryReference::create(
                $appUUIDComposed,
                implode('_', $splittedIndices)
            );
        }

        return $repositoryReference;
    }

    /**
     * Makes calls to the elasticsearch server based on this index.
     *
     * It's possible to make any REST query directly over this method
     *
     * @param string       $path        Path to call
     * @param string       $method      Rest method to use (GET, POST, DELETE, PUT)
     * @param array|string $data        OPTIONAL Arguments as array or pre-encoded string
     * @param array        $query       OPTIONAL Query params
     * @param string       $contentType Content-Type sent with this request
     *
     * @throws ResponseException
     *
     * @return PromiseInterface
     */
    public function requestAsync(
        string $path,
        string $method = Request::GET,
        $data = [],
        array $query = [],
        $contentType = Request::DEFAULT_CONTENT_TYPE
    ): PromiseInterface {
        return $this
            ->elasticaWrapper
            ->requestAsync(
                $path,
                $method,
                $data,
                $query,
                $contentType
            );
    }

    /**
     * Makes calls to the elasticsearch server with usage official client Endpoint based on this index.
     *
     * @param AbstractEndpoint $endpoint
     * @param Index            $index
     *
     * @return PromiseInterface
     *
     * @throws ResponseException
     */
    public function requestAsyncEndpoint(
        AbstractEndpoint $endpoint,
        Index $index
    ): PromiseInterface {
        return $this
            ->elasticaWrapper
            ->requestAsyncEndpoint(
                $endpoint,
                $index
            );
    }
}