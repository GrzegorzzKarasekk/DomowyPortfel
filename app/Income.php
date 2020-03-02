<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'category_user_id', 'amount', 'transaction_date', 'description',
    ];
    
    
    
    // protected $id;
 
    // protected $fillable = ['user_id'];//protected $;

    // protected $category_user_id;
 
    // protected $transaction_date;
 
    // protected $description;
 
}
