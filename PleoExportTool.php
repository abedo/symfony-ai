<?php

/**
 * This file is part of Boozt Platform
 * and belongs to Boozt Fashion AB.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Boozt\Tools;

use Symfony\AI\Agent\Toolbox\Attribute\AsTool;
use Symfony\AI\Agent\Toolbox\Source\HasSourcesInterface;
use Symfony\AI\Agent\Toolbox\Source\HasSourcesTrait;
use Symfony\AI\Agent\Toolbox\Source\Source;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsTool('pleo_create_job_event', description: 'Creates a new export job event.', method: 'createJobEvent')]
#[AsTool('pleo_list_jobs', description: 'Retrieves a list of all export jobs.', method: 'listJobs')]
#[AsTool('pleo_create_job', description: 'Creates a new export job.', method: 'createJob')]
#[AsTool('pleo_get_job', description: 'Retrieves a single export job by its ID.', method: 'getJob')]
#[AsTool('pleo_get_job_items', description: 'Retrieves all items associated with a specific export job.', method: 'getJobItems')]
#[AsTool('pleo_update_job_items', description: 'Updates items within a specific export job.', method: 'updateJobItems')]
#[AsTool('pleo_get_export_items', description: 'Retrieves a list of individual export items across jobs.', method: 'getExportItems')]
final class PleoExportTool implements HasSourcesInterface
{
    use HasSourcesTrait;

    public function __construct(
        private readonly HttpClientInterface $pleoClient,
        private readonly string $pleoApiKey,
    ) {}

    public function createJobEvent(array $payload): array
    {
        return $this->request('POST', 'export-job-events', 'Create Export Job Event', [
            'json' => $payload
        ]);
    }

    public function listJobs(): array
    {
        return $this->request('GET', 'export-jobs', 'List Export Jobs');
    }

    public function createJob(array $payload): array
    {
        return $this->request('POST', 'export-jobs', 'Create Export Job', [
            'json' => $payload
        ]);
    }

    public function getJob(string $jobId): array
    {
        return $this->request('GET', "export-jobs/$jobId", "Get Export Job $jobId");
    }

    public function getJobItems(string $jobId): array
    {
        return $this->request('GET', "export-jobs/$jobId/items", "Get Items for Job $jobId");
    }

    public function updateJobItems(string $jobId, array $items): array
    {
        return $this->request('PUT', "export-jobs/$jobId/items", "Update Items for Job $jobId", [
            'json' => ['items' => $items]
        ]);
    }

    public function getExportItems(array $query = []): array
    {
        return $this->request('GET', 'export-items', 'Get Export Items', [
            'query' => $query
        ]);
    }

    private function request(string $method, string $path, string $sourceName, array $options = []): array
    {
        $url = "https://external.staging.pleo.io/v3/$path";

        $response = $this->pleoClient->request($method, $url, array_replace_recursive([
            'headers' => [
                'Authorization' => 'Basic ' . $this->pleoApiKey,
                'Accept' => 'application/json',
            ],
        ], $options));

        $data = $response->toArray(false);

        return $data;
    }
}
