<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExternalCustomerCategory extends Model
{
    protected $table = 'categories_external_customers';
    protected $fillable = [
        'external_customer_id',
        'category_id'
    ];

    public function externalCustomers(){
        return $this->hasMany(ExternalCustomer::class);
    }

    public function categories(){
        return $this->hasMany(Category::class);
    }
}
