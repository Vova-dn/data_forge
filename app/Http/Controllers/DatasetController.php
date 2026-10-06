<?php

namespace App\Http\Controllers;

use App\Models\Dataset;
use Illuminate\Http\Request;

class DatasetController extends Controller
{


    public function getDatasets(int $id){

        $dataset = Dataset::findOrFail($id);

        $this->authorize('view', $dataset);

        return view('datasets', compact('dataset'));

    }
}
