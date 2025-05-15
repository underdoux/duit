<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransactionModel extends Model
{
    // Define table, primary key and fillable fields
    protected $table = 'transaction';
    protected $primaryKey = 'transactionid';
    protected $fillable = ['categoryid', 'accountid', 'name', 'transactiondate', 'reference', 'type', 'description', 'file'];

    // Add timestamps if the table has created_at and updated_at columns
    public $timestamps = true;

    // Relationships can be added here if needed
}
