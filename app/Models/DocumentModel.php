<?php 
namespace App\Models;
use CodeIgniter\Model;
class DocumentModel extends Model
{
    protected $table = 'document';
    protected $primaryKey = 'id';
    protected $allowedFields = ['judul', 'keterangan', 'path', 'category_id', 'created_at', 'size', 'permission'];
}
