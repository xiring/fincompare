<?php
namespace Src\Catalog\Application\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Src\Catalog\Application\Actions\CreateProductAction;

/**
 * ImportProductsJob job.
 *
 * @package Src\Catalog\Application\Jobs
 */
class ImportProductsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 1200; // 20 minutes

    public function __construct(
        public string $filePath,
        public string $delimiter = ',',
        public bool $hasHeader = true
    ) {
        $this->onQueue('imports');
    }

    public function handle(CreateProductAction $create): void
    {
        if (!is_file($this->filePath)) {
            Log::warning('ImportProductsJob: file not found', ['path' => $this->filePath]);
            return;
        }

        $handle = fopen($this->filePath, 'r');
        if ($handle === false) {
            Log::warning('ImportProductsJob: unable to open file', ['path' => $this->filePath]);
            return;
        }

        $headers = [];
        $lineNumber = 0;
        $imported = 0;
        $failed = 0;

        try {
            if ($this->hasHeader) {
                $headers = fgetcsv($handle, 0, $this->delimiter);
                $headers = is_array($headers) ? array_map(fn($h) => trim((string)$h), $headers) : [];
            }

            while (($row = fgetcsv($handle, 0, $this->delimiter)) !== false) {
                $lineNumber++;

                $data = $this->mapRowToData($row, $headers);
                if ($data === null) { $failed++; continue; }

                try {
                    $attributesInput = $data['_attributes'] ?? [];
                    unset($data['_attributes']);
                    $create->execute($data, $attributesInput);
                    $imported++;
                } catch (\Throwable $e) {
                    $failed++;
                    Log::error('ImportProductsJob row failed', [
                        'line' => $lineNumber,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        } finally {
            fclose($handle);
        }

        Log::info('ImportProductsJob finished', [
            'path' => $this->filePath,
            'imported' => $imported,
            'failed' => $failed,
        ]);
    }

    private function mapRowToData(array $row, array $headers): ?array
    {
        $indexed = [];
        if (!empty($headers)) {
            foreach ($row as $i => $value) {
                $key = isset($headers[$i]) ? strtolower($headers[$i]) : (string)$i;
                $indexed[$key] = $value;
            }
        } else {
            // fallback by index
            $indexed = [
                'name' => $row[0] ?? null,
                'partner_id' => $row[1] ?? null,
                'product_category_id' => $row[2] ?? null,
                'slug' => $row[3] ?? null,
                'description' => $row[4] ?? null,
                'is_featured' => $row[5] ?? null,
                'status' => $row[6] ?? null,
                'attributes' => $row[7] ?? null,
            ];
        }

        $name = trim((string)($indexed['name'] ?? ''));
        $partnerId = (int)($indexed['partner_id'] ?? 0);
        $categoryId = (int)($indexed['product_category_id'] ?? 0);
        if ($name === '' || $partnerId <= 0 || $categoryId <= 0) {
            return null;
        }

        $attributes = [];
        $rawAttrs = $indexed['attributes'] ?? null;
        if ($rawAttrs) {
            if (is_string($rawAttrs)) {
                $decoded = json_decode($rawAttrs, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    $attributes = $decoded;
                }
            } elseif (is_array($rawAttrs)) {
                $attributes = $rawAttrs;
            }
        }

        return [
            'partner_id' => $partnerId,
            'product_category_id' => $categoryId,
            'name' => $name,
            'slug' => isset($indexed['slug']) ? (string)$indexed['slug'] : null,
            'description' => isset($indexed['description']) ? (string)$indexed['description'] : null,
            'is_featured' => filter_var($indexed['is_featured'] ?? false, FILTER_VALIDATE_BOOLEAN),
            'status' => (string)($indexed['status'] ?? 'active'),
            '_attributes' => $attributes,
        ];
    }
}


