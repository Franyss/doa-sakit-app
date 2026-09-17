<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoaSakit extends Model
{
    use HasFactory;
    protected $table = 'doa_sakit';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    protected $fillable = [
        'nama',
        'gender',
        'jenis_penyakit',
        'catatan',
        'tanggal_doa',
        'jam_doa',
        'alamat',
        'no_hp',
        'status',
    ];

    protected $casts = [
        'tanggal_doa' => 'date:Y-m-d',
        'created_at' => 'datetime',
    ];
}
