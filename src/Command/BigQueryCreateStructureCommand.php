<?php

namespace Softspring\DoctrineChangeLogBundle\Command;

use Google\Cloud\BigQuery\BigQueryClient;
use Google\Cloud\BigQuery\Dataset;
use Google\Cloud\BigQuery\Table;
use Google\Cloud\Core\Exception\ConflictException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class BigQueryCreateStructureCommand // extends Command implements ContainerAwareInterface
{
    //    /**
    //     * @var array
    //     */
    //    protected $storageOptions;
    //
    //    /**
    //     * @var ContainerInterface
    //     */
    //    protected $container;
    //
    //    /**
    //     * @var BigQueryClient
    //     */
    //    protected $bigQuery;
    //
    //    /**
    //     * @var array
    //     */
    //    protected $bigQuerySchema;
    //
    //    /**
    //     * @inheritDoc
    //     */
    //    public function setContainer(ContainerInterface $container = null)
    //    {
    //        $this->container = $container;
    //        $this->storageOptions = $this->container->getParameter('sfs_doctrine_changelog.storage.options');
    //        $this->bigQuerySchema = $this->container->getParameter('sfs_doctrine_changelog.storage.big_query.schema');
    //        $this->bigQuery = $this->container->get('sfs_doctrine_changelog.storage.big_query_client');
    //    }
    //
    //    /**
    //     * @inheritDoc
    //     */
    //    protected function configure()
    //    {
    //        $this->setName('sfs:doctrine-changelog:create-bigquery-structure');
    //    }
    //
    //    /**
    //     * @inheritDoc
    //     */
    //    protected function execute(InputInterface $input, OutputInterface $output)
    //    {
    //        $this->checkDriver();
    //        $dataset = $this->getDataset($output);
    //        $this->syncTable($dataset, $output, false);
    //    }
    //
    //    protected function checkDriver()
    //    {
    //        $driver = $this->container->getParameter('sfs_doctrine_changelog.storage.driver');
    //
    //        if ($driver !== 'big-query') {
    //            throw new InvalidArgumentException(sprintf('SfsDoctrineChangeLogBundle is configured to use %s driver, not big-query', $driver));
    //        }
    //    }
    //
    //    protected function getDataset(OutputInterface $output): Dataset
    //    {
    //        try {
    //            $dataset = $this->bigQuery->createDataset($this->storageOptions['dataset']);
    //            $output->writeln(sprintf('Created dataset %s', $this->storageOptions['dataset']));
    //            return $dataset;
    //        } catch (ConflictException $e) {
    //            $output->writeln(sprintf('Dataset %s already exists', $this->storageOptions['dataset']));
    //            return $this->bigQuery->dataset($this->storageOptions['dataset']);
    //        }
    //    }
    //
    //    protected function syncTable(Dataset $dataset, OutputInterface $output, bool $dryRun): ?Table
    //    {
    //        $table = $dataset->table($this->storageOptions['table']);
    //
    //        if ($table->exists()) {
    //            $output->writeln(sprintf('Table %s already exists', $this->storageOptions['table']));
    //
    //            $schema = $table->info()['schema'];
    //
    //            $diff = $this->tableSchemaFieldDiff($schema, $this->bigQuerySchema);
    //
    //            if (!$this->diffIsValid($diff, $output)) {
    //                $output->writeln('Changes are not going to be applied, review your configuration');
    //                return null;
    //            }
    //
    //            if ($diff['changes']) {
    //                // TODO SYNC TABLE
    //                $output->writeln(sprintf('<info>BigQuery table updating is not yet implemented, please do it manually at https://console.cloud.google.com/bigquery?project=%s&p=%s&d=%s&t=%s&page=table</info>',
    //                    $this->storageOptions['project'],
    //                    $this->storageOptions['project'],
    //                    $this->storageOptions['dataset'],
    //                    $this->storageOptions['table']
    //                ));
    //            } else {
    //                $output->writeln('BigQuery table is synced with your definition');
    //            }
    //
    //            return $table;
    //        } else {
    //            $output->writeln(sprintf('Table %s does not exist, creating...', $this->storageOptions['table']));
    //
    //            if (!$dryRun) {
    //                return $dataset->createTable($this->storageOptions['table'], ['schema' => $this->bigQuerySchema]);
    //            }
    //        }
    //
    //        return null;
    //    }
    //
    //    /**
    //     * @see https://cloud.google.com/bigquery/docs/managing-table-schemas
    //     *
    //     * @param array $diff
    //     * @param OutputInterface $output
    //     * @return bool
    //     */
    //    protected function diffIsValid(array $diff, OutputInterface $output): bool
    //    {
    //        $valid = true;
    //
    //        // CHECK REMOVALS (NOT SUPPORTED)
    //        foreach ($diff['remove'] as $removedField) {
    //            $output->writeln(sprintf('<error>- REMOVE: Big query does not support field removal (requested field removal: %s)</error>', $removedField['name']));
    //            $valid = false;
    //        }
    //
    //        // CHECK UPDATES (ONLY SUPPORTED)
    //        foreach ($diff['update'] as list('old'=>$oldField, 'new'=>$newField)) {
    //            if (strtolower($oldField['type']) != strtolower($newField['type'])) {
    //                $output->writeln(sprintf('<error>- UPDATE: Big query does not support field type change (field %s is %s, and change to %s is requested)</error>', $oldField['name'], $oldField['type'], $newField['type']));
    //                $valid = false;
    //                continue;
    //            }
    //
    //            if (!in_array(strtolower($oldField['mode']??''), ['', 'required']) && !in_array(strtolower($newField['mode']??''), ['', 'required', 'nullable'])) {
    //                $output->writeln(sprintf('<error>- UPDATE: Big query does not support field mode change different that from REQUIRED to NULLABLE (field %s is %s, and change to %s is requested)</error>', $oldField['name'], $oldField['mode']??'', $newField['mode']??''));
    //                $valid = false;
    //                continue;
    //            }
    //
    //            $output->writeln(sprintf('- UPDATE: field %s %s %s', $newField['name'], $newField['type'], $newField['mode']??''));
    //        }
    //
    //        // ALL CREATES ARE VALID
    //        foreach ($diff['create'] as $newField) {
    //            $output->writeln(sprintf('- CREATE: field %s %s %s', $newField['name'], $newField['type'], $newField['mode']??''));
    //        }
    //
    //        return $valid;
    //    }
    //
    //    protected function tableSchemaFieldDiff(array $bigQuerySchema, array $configuredSchema): array
    //    {
    //        $createFields = [];
    //        $updatedFields = [];
    //        $removedFields = [];
    //
    //        $resultingSchema = [];
    //
    //        $changes = false;
    //
    //        foreach ($bigQuerySchema['fields'] as $field) {
    //            if (null === $indexOfField = $this->indexOfSchemaField($field['name'], $configuredSchema)) {
    //                $removedFields[] = $field;
    //                $changes = true;
    //                continue;
    //            }
    //
    //            $configuredField = $configuredSchema['fields'][$indexOfField];
    //
    //            if (strtolower($field['type']) !== strtolower($configuredField['type']) || strtolower($field['mode']??'') !== strtolower($configuredField['mode']??'')) {
    //                $updatedFields[] = [
    //                    'old' => $field,
    //                    'new' => $configuredField,
    //                ];
    //
    //                $resultingSchema[$indexOfField] = $configuredField;
    //                $changes = true;
    //            }
    //        }
    //
    //        foreach ($configuredSchema['fields'] as $field) {
    //            if (null === $indexOfField = $this->indexOfSchemaField($field['name'], $bigQuerySchema)) {
    //                $createFields[] = $field;
    //                $resultingSchema[] = $field;
    //                $changes = true;
    //                continue;
    //            }
    //        }
    //
    //        return [
    //            'changes' => $changes,
    //            'create' => $createFields,
    //            'update' => $updatedFields,
    //            'remove' => $removedFields,
    //            'originalSchema' => $bigQuerySchema,
    //            'resultingSchema' => $resultingSchema,
    //        ];
    //    }
    //
    //    private function indexOfSchemaField(string $fieldName, array $schema): ?int
    //    {
    //        foreach ($schema['fields'] as $i => $field) {
    //            if ($field['name'] == $fieldName) {
    //                return $i;
    //            }
    //        }
    //
    //        return null;
    //    }
}
