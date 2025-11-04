<?php

namespace App\Http\Controllers;

use App\Models\contact;
use Illuminate\Http\Request;

class ContactController extends ApiController
{
    public function index()
    {
        $item=Contact::latest()->get();
        return $this->successResponse($item,'Success',200);
    }
    public function show(Contact $contact){
        return $this->successResponse($contact,'Success',200);
    }
    public function destroy(Contact $contact){
        $item=$contact->delete();
        return $this->successResponse($item,'Success',200);
    }
}
