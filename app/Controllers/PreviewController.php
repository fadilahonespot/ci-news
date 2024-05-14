<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\DocumentModel;

class PreviewController extends Controller
{
    public function index($id)
    {
        $documentModel = new DocumentModel();
        $document = $documentModel->where('id', $id)->first();
        $data = [
            'document' => $document
        ];

        return view('preview_view', $data);
    }
}