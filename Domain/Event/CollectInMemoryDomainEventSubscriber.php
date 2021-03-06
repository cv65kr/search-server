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

namespace Apisearch\Server\Domain\Event;

/**
 * Class CollectInMemoryDomainEventSubscriber.
 */
class CollectInMemoryDomainEventSubscriber implements EventSubscriber
{
    /**
     * @var DomainEvent[]
     *
     * Events
     */
    private $domainEventWithRepositoryReference = [];

    /**
     * Subscriber should handle event.
     *
     * @param DomainEventWithRepositoryReference $domainEventWithRepositoryReference
     *
     * @return bool
     */
    public function shouldHandleEvent(DomainEventWithRepositoryReference $domainEventWithRepositoryReference): bool
    {
        return true;
    }

    /**
     * Handle event.
     *
     * @param DomainEventWithRepositoryReference $domainEventWithRepositoryReference
     */
    public function handle(DomainEventWithRepositoryReference $domainEventWithRepositoryReference)
    {
        $this->domainEventWithRepositoryReference[] = $domainEventWithRepositoryReference;
    }

    /**
     * Get Events.
     *
     * @return DomainEventWithRepositoryReference[]
     */
    public function getEvents(): array
    {
        return $this->domainEventWithRepositoryReference;
    }

    /**
     * Flush events.
     */
    public function flushEvents()
    {
        $this->domainEventWithRepositoryReference = [];
    }
}
