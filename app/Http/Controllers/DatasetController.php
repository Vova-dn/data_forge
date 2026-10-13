<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessDataset;
use App\Models\Dataset;
use App\Models\Import;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DatasetController extends Controller
{


    public function getDatasets(int $id){

        $dataset = Dataset::findOrFail($id);

        $this->authorize('view', $dataset);

        return view('datasets', compact('dataset'));

    }

    public function postDatasets(Request $request){
        $request->validate(['file' => ['required', 'file', 'mimes:csv,txt', 'max:524288']]);
        $file = $request->file('file');
        $path = $file->store('datasets', 'local');
        if ($path === false) {
            return response()->json(['error' => 'File could not be uploaded.'], 302);
        }

        try {
            $dataset = DB::transaction(function () use ($file, $path, $request) {
                return Dataset::create([
                    'owner_id' => $request->user()->id,
                    'users_name' => $file->getClientOriginalName(),
                    'path' => $path,
                ]);
            });
        } catch (\Throwable $e) {
            Storage::disk('local')->delete($path);
            throw $e;
        }

        $import = new Import();
        $import->dataset_id = $dataset->id;
        $import->source_system = 'users';
        $import->schema_version = '1';
        $import->filename = $file->getClientOriginalName();
        $import->size = $file->getSize();
        $import->checksum = hash_file('sha256', $file->getRealPath());
        $import->immutable_source_key = $path;
        $import->state = 'received';
        $import->save();

        ProcessDataset::dispatch($import->id)->afterCommit();


        return response()->json([
            'message' => 'file uploaded',
            'import_id' => $import->id,
        ], 202);

    }
}
