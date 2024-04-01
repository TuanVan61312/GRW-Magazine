<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Faculty;
use Carbon\Carbon;

class Event extends Model
{
    protected $guarded = [];

    public function faculty(){
        return $this->hasOne(Faculty::class, 'id', 'faculty_id');
    }

    public function hasExpired()
    {
        // Lấy ngày kết thúc của sự kiện
        $final_date = Carbon::parse($this->final_date);

        // So sánh ngày kết thúc với ngày hiện tại
        return $final_date->isPast();
    }
}