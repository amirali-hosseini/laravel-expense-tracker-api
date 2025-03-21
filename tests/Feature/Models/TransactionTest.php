<?php

namespace Tests\Feature\Models;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Models\Traits\InsertDataTestTrait;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    use RefreshDatabase, InsertDataTestTrait;

    public function model(): Model
    {
        return new Transaction();
    }
}
