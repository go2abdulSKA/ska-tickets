<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Import extends Model
{
    protected $fillable = [
        'name', 'filename_master', 'filename_lineitems',
        'status','total_master_rows','processed_master_rows',
        'total_line_rows','processed_line_rows','message','log_path','started_by'
    ];    
}
