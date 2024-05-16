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
        if ($document == null) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        if ($document['permission'] == '1') {
            $session = session();
            $user = $session->get('user');

            if ($user === null) {
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            }
        }
        
        $data = [
            'document' => $document
        ];

        return view('preview_view', $data);
    }
}