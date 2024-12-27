<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    use HasFactory;

    public $fillable = [
        'name',
        "free_time",
        "max_hours",
        "insurance",
        "prices",
        "app_description_ar",
        "app_description_detailed_ar",
        "app_description_en",
        "app_description_detailed_en",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at"
    ]; 
}
