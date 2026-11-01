<?php

namespace Tests\Feature;

use App\Jobs\ProcessDataset;
use App\Models\Dataset;
use App\Models\Import;
use App\Models\ImportChunk;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class DataValidateTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public static function importCases(): array
    {
        return [
            'valid file' => [
                'valid.csv',
                100, //accepted
                0,  //rejected
                0,  //duplicated
                0, //failed
                '0', //errors
            ],
            'mixed file' => [
                'mixed.csv',
                90,
                10,
                0,
                0,
                '0', //errors
            ],
            'bad header' => [
                'bad-header.csv',
                0,
                0,
                0,
                0,
                'bad header',
            ]
        ];
    }

    #[DataProvider('importCases')]
    public function test_job_validation_files(
        string $filename,
        int $accepted,
        int $rejected,
        int $duplicated,
        int $failed,
        string $errors
    ): void
    {
        $owner = User::factory()->create();
        Storage::fake('local');
        $file = UploadedFile::fake()->createWithContent(
            $filename,
            file_get_contents(base_path("tests/Fixtures/{$filename}")),
        );
        $key = Storage::disk('local')->putFile('datasets', $file);
        //Storage::disk('local')->put('datasets/valid.csv', file_get_contents('/var/www/html/tests/Fixtures/valid.csv'));

        $dataset = Dataset::create([
            'owner_id' => $owner->id,
            'users_name' => 'valid.csv',
            'path' => 'datasets/valid.csv',
        ]);
        $import = Import::create([
            'dataset_id' => $dataset->id,
            'source_system' => 'users',
            'schema_version' => '1',
            'filename' => 'valid.csv',
            'size' => Storage::disk('local')->size($key),
            'checksum' => hash_file('sha256', Storage::disk('local')->path($key)),
            'immutable_source_key' => $key,
            'state' => 'received',
        ]);

        ProcessDataset::dispatchSync($import->id);

        $chunk = ImportChunk::firstOrFail();
        $this->assertSame([
            'accepted' => $accepted,
            'rejected' => $rejected,
            'duplicated' => $duplicated,
            'failed' => $failed,
            'errors' => $errors,
            ], $chunk->summary);
    }



}
