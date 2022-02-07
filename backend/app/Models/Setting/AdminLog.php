<?php

namespace App\Models\Setting;

use Illuminate\Database\Eloquent\Model;

class AdminLog extends Model
{
  protected $table = 'admin_logs';
  protected $guarded = ['id'];
}