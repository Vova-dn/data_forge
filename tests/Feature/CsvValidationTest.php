<?php

namespace Tests\Feature;

use App\Http\Controllers\DatasetController;
use App\Models\Dataset;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CsvValidationTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;
    public function test_csv_received_successful(): void
    {
        $owner = User::factory()->create();
        $file = UploadedFile::fake()->createWithContent('test.csv', 'x');
        $response = $this->actingAs($owner)->post('/datasets', ['file' => $file]);
        $response->assertStatus(202)
            ->assertJson(['message' => 'file uploaded'])
            ->assertJsonStructure(['import_id']);

    }
    public function test_txt_received_successful(): void
    {
        $owner = User::factory()->create();
        $file = UploadedFile::fake()->createWithContent('test.txt', 'x');
        $response = $this->actingAs($owner)->post('/datasets', ['file' => $file]);
        $response->assertStatus(202)
            ->assertJson(['message' => 'file uploaded'])
            ->assertJsonStructure(['import_id']);
    }
    public function test_anotherFormat_received_failure(): void
    {
        $owner = User::factory()->create();
        $file = UploadedFile::fake()->createWithContent('test.doc', 'x');
        $response = $this->actingAs($owner)->post('/datasets', ['file' => $file]);
        $response->assertSessionHasErrors('file');
    }
    public function test_save_file_correctly(): void
    {
        Storage::fake('local');
        $owner = User::factory()->create();
        $file = UploadedFile::fake()->createWithContent('test.txt', 'x');
        $response = $this->actingAs($owner)->post('/datasets', ['file' => $file]);
        $response->assertStatus(202);

        $dataset = Dataset::firstOrFail();

        Storage::disk('local')->assertExists($dataset->path);
        $this->assertStringStartsWith('datasets/', $dataset->path);
    }
    public function test_transaction_fail_mechanism(): void
    {
        $owner = User::factory()->create();
        Storage::fake('local');

        Dataset::creating(function () {
            throw new \RuntimeException('Database write failed');
        } );

        $this->withoutExceptionHandling();

        try {
            $this->actingAs($owner)->post('/datasets', [
                'file' => UploadedFile::fake()
                    ->create('test.csv', 1, 'text/csv'),
            ]);

            $this->fail('Ожидалось исключение');
        } catch (\RuntimeException $e) {
            $this->assertSame('Database write failed', $e->getMessage());
        }

        $this->assertSame([], Storage::disk('local')->allFiles('datasets'));
    }

}
