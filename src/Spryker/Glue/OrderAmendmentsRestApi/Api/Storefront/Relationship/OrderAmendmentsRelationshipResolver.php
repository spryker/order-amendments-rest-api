<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Glue\OrderAmendmentsRestApi\Api\Storefront\Relationship;

use Generated\Api\Storefront\OrderAmendmentsStorefrontResource;
use Spryker\ApiPlatform\Relationship\AbstractRelationshipResolver;
use Spryker\Service\Serializer\SerializerServiceInterface;

class OrderAmendmentsRelationshipResolver extends AbstractRelationshipResolver
{
    public function __construct(protected SerializerServiceInterface $serializer)
    {
    }

    /**
     * @return array<\Generated\Api\Storefront\OrderAmendmentsStorefrontResource>
     */
    protected function resolveRelationship(): array
    {
        $resources = [];

        foreach ($this->getParentResources() as $orderResource) {
            $contextData = $orderResource->context ?? null;
            $salesOrderAmendmentData = is_array($contextData) && isset($contextData['salesOrderAmendment'])
                ? $contextData['salesOrderAmendment']
                : null;

            if (!is_array($salesOrderAmendmentData) || $salesOrderAmendmentData === []) {
                continue;
            }

            $resources[] = $this->serializer->denormalize($salesOrderAmendmentData, OrderAmendmentsStorefrontResource::class);
        }

        return $resources;
    }
}
