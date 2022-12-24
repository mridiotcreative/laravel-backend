<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactUs extends Model
{
    protected $fillable = ['name', 'email', 'message'];
    // Insert Data into contact_us table
    public function addContactUs($request)
    {
        $contactUs = new ContactUs;
        $contactUs->fill($request->all());
        return $contactUs->save();
    }
}
