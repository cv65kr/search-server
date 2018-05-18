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
 * @author PuntMig Technologies
 */

declare(strict_types=1);

namespace Apisearch\Server\Domain\Repository\Repository;

use Apisearch\Config\ImmutableConfig;
use Apisearch\Model\Item;

/**
 * Interface IndexRepository.
 */
interface IndexRepository
{
    /**
     * Create the index.
     *
     * @param ImmutableConfig $config
     */
    public function createIndex(ImmutableConfig $config);

    /**
     * Delete the index.
     */
    public function deleteIndex();

    /**
     * Reset the index.
     */
    public function resetIndex();

    /**
     * Generate items documents.
     *
     * @param Item[] $items
     */
    public function addItems(array $items);
}
