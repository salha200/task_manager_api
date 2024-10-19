<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'name', 'description',
    ];

    // علاقة مع المستخدمين الذين يملكون هذا الدور
    public function users()
    {
        return $this->belongsToMany(User::class, 'role_user');
    }

    
}
