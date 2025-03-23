<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TransactionControllerTest extends ResourceControllerTestCase
{
    use RefreshDatabase;

    public function model(): Model
    {
        return new Transaction();
    }

    public function getRequiredFieldName(): string
    {
        return 'amount';
    }
}
