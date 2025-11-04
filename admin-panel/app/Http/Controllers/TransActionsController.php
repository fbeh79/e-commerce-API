<?php

namespace App\Http\Controllers;

use App\Http\Resources\TransActionResource;
use App\Models\transactions;
use Illuminate\Http\Request;

class TransActionsController extends ApiController
{
    public function index()
    {
        $transactions = transactions::latest()->paginate(4);
        return $this->SuccessResponse([
            'transactions' => TransActionResource::collection($transactions),
            'links' => transActionResource::collection($transactions->response()->getData()->links),
            'meta' => transActionResource::collection($transactions->response()->getData()->meta),
        ]);


    }

    public function chart()
    {
        $transactions = transactions::getData(month: 12, status: 1)->get();
      return $this->chartdata($transactions);
    }
    public function chartdata($transactions)
    {
        $monthName=$transactions->map(function ($transaction) {
            return verta($transaction->created_at)->format('Y-m-d');
        });
        $amount=$transactions->map(function ($transaction) {
            return ($transaction->amount);
        });
        dd($amount,$monthName);
    }
}
