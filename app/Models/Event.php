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
        $final_date = Carbon::parse($this->final_date);
        $grace_period = 1; 
        $allowed_submission_date = $final_date->addDays($grace_period);

        if (now()->greaterThan($allowed_submission_date)) {
            $this->status = 'expired';
            $this->save(); 
            return true;
        }
        return false;
    }
}