<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\TransactionType;
use App\User;
use Illuminate\Http\Response;

class UserTransactionController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @param $userId
     * @return Response
     */
    public function index($userId)
    {
        // Return all the user transactions
        $user = User::find($userId);

        if (!$user) {
            // 404 user not found
            return $this->sendError('userNotFount', ['error' => 'User not found']);
        }

        return $this->sendResponse($user->transactions()->get(), 'transactions retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param $userId
     * @return Response
     */
    public function store(Request $request, $userId)
    {
        // Check request data
        if (!$request->input('amount') || !$request->input('currency') || !$request->input('type')) {
            return $this->sendError('dataMissing', ['error' => 'Data is missing'], 422);
        }

        // Validate currency
        if ($request->has("currency")) {
            if ($request->get("currency") != "" && !validateCurrency($request->get("currency")))
                return $this->sendError('currencyError.', ['error' => 'Currency not found']);
        }

        //Validate transaction type
        $transactionType = TransactionType::where('type', $request->input('type'))->first();
        if ($transactionType)
            $transactionId = $transactionType->id;
        else
            return $this->sendError('typeError', ['error' => 'Type is not valid'], 422);

        // Validate user
        $user = User::find($userId);

        if (!$user) {
            //404 user not found
            return $this->sendError('userNotFount', ['error' => 'User not found']);
        }

        //Make the currency conversion if is necessary
        $amount = $request->get('amount');
        if($user->defaultCurrency != "" && $request->has('currency')) {
            $amount = convertCurrency($user->currency, $request->get('currency'), $amount);
        }

        $transaction = $request->all();
        $transaction['transaction_type_id'] = $transactionId;
        $transaction['amount'] = $amount;

        $user->transactions()->create($transaction);

        $user->balance += $amount;
        $user->save();

        return $this->sendResponse($user, 'transactions retrieved successfully.');
    }
}
