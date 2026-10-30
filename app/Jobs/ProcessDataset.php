<?php

namespace App\Jobs;

use App\Models\Import;
use App\Models\ImportChunk;
use App\Models\ImportRun;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use SplFileObject;

class ProcessDataset implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public int $import_id)
    {

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $import = Import::find($this->import_id);
        $file = Storage::disk('local')->path($import->immutable_source_key);
        $import_run = ImportRun::create([
            'import_id' => $this->import_id,
            'dataset_id' => $import->dataset_id,
            'run_no' => 1,
            'state' => 'processing',
        ]);
        $import_chunk = ImportChunk::create([
            'run_id' => $import_run->id,
            'phase' => 'validate',
            'chunk_index' => 1,
            'state' => 'processing',
            'attempts' => 1,
            'summary' => null
        ]);
        $csv = new SplFileObject($file, 'r');

        $csv->setFlags(
            SplFileObject::READ_CSV
            | SplFileObject::SKIP_EMPTY
            | SplFileObject::DROP_NEW_LINE
            );

        $csv->setCsvControl(',' , '"', '//');

        $isHeader = true;
        $rowNumber = 0;
        $rows = [];
        $errors = [];
        $failed = 0;
        $duplicates = 0;
        foreach ($csv as $row) {
            $rowNumber++;
            if ($isHeader) {
                $isHeader = false;
                continue;
            }

            if ($row === null || $row === false) {
                $failed++;
                continue;
            }

            $record = [
                'external_id' => trim($row[0] ?? ''),
                'email' => mb_strtolower(trim($row[1] ?? '')),
                'full_name' => preg_replace(
                    '/\s+/u' ,
                    ' ' ,
                    trim($row[2] ?? '')),
                'country_code' => strtoupper(trim($row[3] ?? '')),
                'company_id' => trim($row[4] ?? ''),
            ];
            if ($record ['company_id'] ===''){
                $record['company_id'] = null;
            }
            $validator = validator($record, [
                'external_id' => ['string', 'required', 'min:1', 'max:64'],
                'email' => ['string', 'required', 'max:254', 'email'],
                'full_name' => ['string', 'required', 'max:200'],
                'country_code' => ['string', 'required', 'regex:/^[A-Z]{2}$/'],
                'company_id' => ['nullable', 'string', 'min:1', 'max:64'],
            ]);

            if($validator->fails()) {
                $failed++;
                continue;
            }
            $isValid = $validator->passes();
            $now = now();

            $rows[] = [
                'import_id' => $import->id,
                'row_number' => $rowNumber,
                'chunk' => $import_run->chunk_index,
                'normalized_payload' => $isValid
                    ? json_encode($validator->validated(), JSON_UNESCAPED_UNICODE)
                    : null,
                'business_key' => $isValid ? $record['external_id'] : null,
                'source_hash' => $isValid
                    ? hash('sha256', json_encode($validator->validated()))
                    : null,
                'candidate_row_id' => null,
                'outcome' => $isValid ? 'pending' : 'invalid',
                'created_at' => $now,
                'updated_at' => $now,
            ];
            if(!$isValid){
                foreach ($validator->errors()->toArray() as $field => $messages) {
                    foreach ($messages as $message) {
                        $errors[] = [
                            'row_id' => null,
                            'import_id' => $import->id,
                            'field' => $field,
                            'code' => 'validation_failed',
                            'message' => $message,
                            'excerpt' => null,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                    }
                }

            }

        }
        $duplicates = count($rows) - count(array_unique($rows));
        $rows = array_unique($rows);
        DB::transaction(function () use ($errors, $rows){
           DB::table('import_rows')->upsert($rows, ['import_id', 'row_number'],
           [
               'chunk',
               'normalized_payload',
               'business_key',
               'source_hash',
               'candidate_row_id',
               'outcome',
               'updated_at'
           ]);
           if ($errors !== []){
               DB::table('import_rows')->insert($errors);
           }
        });
        $import_chunk->summary = [
            'accepted' => count($rows),
            'rejected' => count($errors),
            'duplicated' => $duplicates,
            'failed' => $failed,
            ];
        $import_chunk->save();



        $errors = [];
        $rows = [];
    }
}
