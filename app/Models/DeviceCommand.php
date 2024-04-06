<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceCommand extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'command',
        'operation',
        'description',
        'result',
        'format',
        'device_id'
    ];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
    
    public function deviceCommandParams()
    {
        return $this->hasMany(DeviceCommandParam::class);
    }
}
