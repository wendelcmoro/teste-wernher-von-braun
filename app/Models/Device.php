<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'identifier',
        'description',
        'manufacturer',
        'access_url',
    ];

    public function deviceCommands()
    {
        return $this->hasMany(DeviceCommand::class);
    }
}
