<?php

namespace App\Controllers;

use App\Models\CategoryModel;
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

        if ($document['permission'] == '1' || $document['permission'] == '2') {
            $session = session();
            $user = $session->get('user');
    
            if ($user === null) {
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            }

            if ($document['permission'] == '1') {
                $categoryModel = new CategoryModel();
                $category = $categoryModel->where('id', $document['category_id'])->first();

                if ($category === null || $user['id'] !== $category['user_id']) {
                    throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
                }
            }
        }
        
        $data = [
            'document' => $document
        ];

        return view('preview_view', $data);
    }
}