<?php

namespace Tests\Feature;

use App\Jobs\ProcessDataset;
use App\Models\Dataset;
use App\Models\Import;
use App\Models\ImportChunk;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DataValidateTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public function test_job_validation_valid_file(): void
    {

        Storage::fake('local');
        Storage::disk('local')->put('datasets/valid.csv', file_get_contents('/var/www/html/tests/Fixtures/valid.csv'));
        $file = UploadedFile::fake()->createWithContent(
            'valid.csv',
            file_get_contents(base_path('datasets/valid.csv'))
        );
        $dataset = Dataset::create([
            'owner_id' => 1,
            'users_name' => 'valid.csv',
            'path' => 'datasets/valid.csv',
        ]);
        $import = Import::create([
            'dataset_id' => $dataset->id,
            'source_system' => 'users',
            'schema_version' => '1',
            'filename' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
            'checksum' => hash_file('sha256', $file->getRealPath()),
            'immutable_source_key' => $file->getRealPath(),
            'state' => 'received',
        ]);

        ProcessDataset::dispatchSync($import->id);

        $chunk = ImportChunk::firstOrFail();
        $this->assertSame([
            'accepted' => 100,
            'rejected' => 0,
            'duplicated' => 0,
            'failed' => 0,
            ], $chunk->summary);






    }
}
