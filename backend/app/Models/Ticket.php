<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use SoftDeletes,HasFactory;
    

    protected $fillable = [
        'user_id',
        'assigned_to',
        'subject',
        'status',
        'priority',
    ];

    public function user(){

        return $this->belongsTo(User::class,'user_id');
    }

    public function agent(){
        return $this->belongsTo(User::class,'assigned_to');
    }

    public function messages(){
        return $this->hasMany(TicketMessage::class);
    }

    public function activities()
    {
        return $this->hasMany(TicketActivity::class);
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) Ticket::where('status', 'open')->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }

    
}
