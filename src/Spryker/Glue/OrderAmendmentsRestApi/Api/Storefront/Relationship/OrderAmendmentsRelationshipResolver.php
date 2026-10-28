<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Glue\OrderAmendmentsRestApi\Api\Storefront\Relationship;

use Generated\Api\Storefront\OrderAmendmentsStorefrontResource;
use Spryker\ApiPlatform\Relationship\AbstractRelationshipResolver;
use Spryker\ApiPlatform\Relationship\PerItemRelationshipResolverInterface;
use Spryker\Service\Serializer\SerializerServiceInterface;

class OrderAmendmentsRelationshipResolver extends AbstractRelationshipResolver implements PerItemRelationshipResolverInterface
{
    protected const string KEY_SALES_ORDER_AMENDMENT = 'salesOrderAmendment';

    public function __construct(protected SerializerServiceInterface $serializer)
    {
    }

    /**
     * @param array<object> $parentResources
     * @param array<string, mixed> $context
     *
     * @return array<string, array<\Generated\Api\Storefront\OrderAmendmentsStorefrontResource>>
     */
    public function resolvePerItem(array $parentResources, array $context): array
    {
        $orderAmendmentResourcesIndexedByOrderReference = [];

        foreach ($parentResources as $orderResource) {
            $orderReference = $orderResource->orderReference ?? null;

            if ($orderReference === null) {
                continue;
            }

            $contextData = $orderResource->context ?? null;
            $salesOrderAmendmentData = is_array($contextData) ? ($contextData[static::KEY_SALES_ORDER_AMENDMENT] ?? null) : null;

            $orderAmendmentResourcesIndexedByOrderReference[$orderReference] = is_array($salesOrderAmendmentData) && $salesOrderAmendmentData !== []
                ? [$this->serializer->denormalize($salesOrderAmendmentData, OrderAmendmentsStorefrontResource::class)]
                : [];
        }

        return $orderAmendmentResourcesIndexedByOrderReference;
    }

    /**
     * @return array<\Generated\Api\Storefront\OrderAmendmentsStorefrontResource>
     */
    protected function resolveRelationship(): array
    {
        return array_merge([], ...array_values($this->resolvePerItem($this->getParentResources(), $this->context)));
    }
}
