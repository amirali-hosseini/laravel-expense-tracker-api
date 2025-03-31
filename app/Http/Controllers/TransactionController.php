<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Models\Category;
use App\Models\Transaction;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transactions = auth()->user()->transactions()->latest()->paginate(10);

        return $this->paginatedResponse($transactions, TransactionResource::class);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransactionRequest $request)
    {
        $validated = $request->validated();

        $validated['user_id'] = auth()->id();

        $category = Category::query()->find($validated['category_id']);

        if ($category->user()->is(auth()->user())) {

            Transaction::query()->create($validated);

            return $this->jsonResponse(
                ['message' => 'Transaction created successfully.'],
                true,
                201
            );
        } else {

            return $this->unauthenticatedResponse();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        if ($transaction->user()->is(auth()->user())) {

            return $this->jsonResponse(
                ['data' => new TransactionResource($transaction)]
            );
        } else {

            return $this->unauthenticatedResponse();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTransactionRequest $request, Transaction $transaction)
    {
        if ($transaction->user()->is(auth()->user())) {

            $transaction->update($request->validated());

            return $this->jsonResponse(
                ['message' => 'The transaction has been updated.']
            );
        } else {

            return $this->unauthenticatedResponse();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        if ($transaction->user()->is(auth()->user())) {

            $transaction->delete();

            return $this->jsonResponse(
                ['message' => 'The transaction has been deleted.']
            );
        } else {

            return $this->unauthenticatedResponse();
        }
    }
}
