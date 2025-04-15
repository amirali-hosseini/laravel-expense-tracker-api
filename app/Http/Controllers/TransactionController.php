<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Models\Category;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $user = auth()->user();

        $startDate = request()->filled('start')
            ? Carbon::parse(request('start'))->startOfDay()
            : now()->startOfMonth();

        $endDate = request()->filled('end')
            ? Carbon::parse(request('end'))->endOfDay()
            : now()->endOfMonth();

        $dateRange = [$startDate, $endDate];
        $type = request('type');

        $transactionsQuery = $user->transactions()
            ->whereBetween('date', $dateRange);

        if (in_array($type, ['income', 'expense'])) {
            $transactionsQuery->where('type', $type);
        }

        $transactions = $transactionsQuery
            ->latest()
            ->paginate(10);

        // Get totals
        $income = $user->transactions()
            ->whereBetween('date', $dateRange)
            ->where('type', 'income')
            ->sum('amount');

        $expense = $user->transactions()
            ->whereBetween('date', $dateRange)
            ->where('type', 'expense')
            ->sum('amount');

        $additionalData = [
            'monthly_summary' => [
                'total_income' => (int) $income,
                'total_expense' => (int) $expense,
            ],
        ];

        return $this->paginatedResponse($transactions, TransactionResource::class, $additionalData);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransactionRequest $request): JsonResponse
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
    public function show(Transaction $transaction): JsonResponse
    {
        if ($this->checkUser($transaction)) {

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
    public function update(UpdateTransactionRequest $request, Transaction $transaction): JsonResponse
    {
        if ($this->checkUser($transaction)) {

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
    public function destroy(Transaction $transaction): JsonResponse
    {
        if ($this->checkUser($transaction)) {

            $transaction->delete();

            return $this->jsonResponse(
                ['message' => 'The transaction has been deleted.']
            );
        } else {

            return $this->unauthenticatedResponse();
        }
    }
}
