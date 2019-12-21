<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Students extends Model
{
    //
    protected $fillable = [
		'first_name','last_name','parent_name','mobile_number','standard','course','email','active'
	];

}
