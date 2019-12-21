<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentDocuments extends Model
{
    protected $fillable = [
		'student_id','document_type','document_file'
	];

	public function Students() {
    	return $this->belongsTo('App\Models\Students', 'student_id', 'id');
    }
	
}