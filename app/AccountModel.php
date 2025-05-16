<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountModel extends Model
{
    // Define table, primary key and fillable fields
    protected $table = 'account';

    protected $primaryKey = 'accountid';

    protected $fillable = ['name', 'balance', 'description'];

    // Add timestamps if the table has created_at and updated_at columns
    public $timestamps = true;

    // Relationships can be added here if needed
}
