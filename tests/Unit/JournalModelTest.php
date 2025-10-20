<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Journal;
use App\Models\JournalDetail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class JournalModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_journal_has_details_relationship()
    {
        $journal = Journal::factory()->create();

        $this->assertInstanceOf(
            'Illuminate\Database\Eloquent\Relations\HasMany',
            $journal->details()
        );
    }

    /** @test */
    public function test_journal_belongs_to_user()
    {
        $user = User::factory()->create();
        $journal = Journal::factory()->create(['created_by' => $user->id]);

        $this->assertInstanceOf(User::class, $journal->creator);
        $this->assertEquals($user->id, $journal->creator->id);
    }

    /** @test */
    public function test_journal_number_format_is_correct()
    {
        $journalNo = Journal::generateJournalNo();

        $this->assertMatchesRegularExpression(
            '/^JRN\/\d{4}\/\d{2}\/\d{4}$/',
            $journalNo
        );
    }

    /** @test */
    public function test_journal_number_increments()
    {
        $firstNo = Journal::generateJournalNo();
        
        Journal::factory()->create(['journal_no' => $firstNo]);
        
        $secondNo = Journal::generateJournalNo();

        $this->assertNotEquals($firstNo, $secondNo);
    }
}
