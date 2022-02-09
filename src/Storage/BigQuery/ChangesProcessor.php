<?php

namespace Softspring\DoctrineChangeLogBundle\Storage\BigQuery;

use Psr\Log\LoggerInterface;
use Softspring\DoctrineChangeLogBundle\Collector\ChangeEntry;

class ChangesProcessor
{
    /**
     * @var Schema
     */
    protected $schema;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * ChangesProcessor constructor.
     */
    public function __construct(Schema $schema, LoggerInterface $logger)
    {
        $this->schema = $schema;
        $this->logger = $logger;
    }

    public function getDataRow(ChangeEntry $changes): array
    {
        $data = [
            'id' => time(),
            'timestamp' => $changes->getTimestamp(),
            'entity_class' => $changes->getEntityClass(),
            'entity_id' => json_encode($changes->getEntityIdentifier()),
            'changes' => json_encode($changes->getChanges()),
        ];

        foreach ($changes->getAttributes()->keys() as $attributeKey) {
            if ($this->schema->fieldDefined($attributeKey)) {
                $data[$attributeKey] = $changes->getAttributes()->get($attributeKey);
            } else {
                $this->logger->warning(sprintf('Missing schema %s field for attribute mapping', $attributeKey));
                $missingSchemaField = $attributeKey;
            }
        }

        return ['data' => $data];
    }
}
