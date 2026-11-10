<?php

namespace Tests\Feature;

use App\Livewire\Admin\Clients as ClientsAdmin;
use App\Livewire\Admin\ManagePapers;
use App\Models\Category;
use App\Models\Client;
use App\Models\Paper;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Regression tests for admin Display Order handling (Client Showcase and
 * Resource Library). Historically, drag-reorder only renumbered the visible
 * page relative to its minimum and the Display Order field wrote raw values,
 * so duplicates ("two items with order 11") and gaps ("11 followed by 24")
 * accumulated. Every ordering write must now leave the whole list densely
 * numbered 1..n with the Display Order field acting as a position.
 */
class AdminSortingTest extends TestCase
{
    use RefreshDatabase;

    private function makeClient(string $name, int $order): Client
    {
        return Client::create([
            'name' => $name,
            'logo' => 'clients/'.$name.'.png',
            'sort_order' => $order,
            'status' => 'active',
        ]);
    }

    private function makePaper(string $slug, int $order): Paper
    {
        $category = Category::firstOrCreate(
            ['slug' => 'test-papers'],
            ['name' => 'Test Papers', 'type' => 'paper'],
        );

        return Paper::create([
            'title' => ucfirst(str_replace('-', ' ', $slug)),
            'slug' => $slug,
            'category_id' => $category->id,
            'description' => 'Summary for '.$slug,
            'resource_type' => Paper::TYPE_DOCUMENT,
            'is_active' => true,
            'sort_order' => $order,
        ]);
    }

    /** Assert a table's sort_order column is exactly 1..n with the given id order. */
    private function assertDenseOrder(string $model, array $expectedIdOrder): void
    {
        $rows = $model::orderBy('sort_order')->pluck('sort_order', 'id');

        $this->assertSame(
            range(1, count($expectedIdOrder)),
            array_values($rows->all()),
            'sort_order values must be dense 1..n with no duplicates or gaps'
        );

        $this->assertSame(
            $expectedIdOrder,
            $rows->keys()->map(fn ($i) => (int) $i)->all(),
            'items are in the expected sequence'
        );
    }

    public function test_client_drag_reorder_renumbers_all_rows_densely(): void
    {
        // Messy legacy state: duplicates (11, 11) and gaps (2 ... 24).
        $d = $this->makeClient('Delta', 2);
        $a = $this->makeClient('Alpha', 11);
        $b = $this->makeClient('Bravo', 11);
        $c = $this->makeClient('Charlie', 24);

        // Display order is (sort_order, name): Delta, Alpha, Bravo, Charlie.
        // Drag swaps Alpha and Bravo on their "page".
        Livewire::test(ClientsAdmin::class)
            ->call('updateSortOrder', [$b->id, $a->id]);

        $this->assertDenseOrder(Client::class, [$d->id, $b->id, $a->id, $c->id]);
    }

    public function test_client_display_order_field_acts_as_position(): void
    {
        $a = $this->makeClient('Alpha', 1);
        $b = $this->makeClient('Bravo', 2);
        $c = $this->makeClient('Charlie', 3);

        // Editing Charlie's Display Order to 1 moves it first; the rest shift.
        Livewire::test(ClientsAdmin::class)
            ->call('edit', $c->id)
            ->set('sort_order', 1)
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDenseOrder(Client::class, [$c->id, $a->id, $b->id]);
    }

    public function test_paper_drag_reorder_renumbers_all_rows_densely(): void
    {
        // Duplicates and a gap; display order tiebreaks by id: p1, p2, p3.
        $p1 = $this->makePaper('paper-one', 5);
        $p2 = $this->makePaper('paper-two', 5);
        $p3 = $this->makePaper('paper-three', 9);

        Livewire::test(ManagePapers::class)
            ->call('reorder', [$p2->id, $p1->id]);

        $this->assertDenseOrder(Paper::class, [$p2->id, $p1->id, $p3->id]);
    }

    public function test_paper_display_order_field_acts_as_position(): void
    {
        $p1 = $this->makePaper('paper-one', 1);
        $p2 = $this->makePaper('paper-two', 2);
        $p3 = $this->makePaper('paper-three', 3);

        Livewire::test(ManagePapers::class)
            ->call('edit', $p3->id)
            ->set('sort_order', 2)
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDenseOrder(Paper::class, [$p1->id, $p3->id, $p2->id]);
    }

    public function test_out_of_range_position_clamps_to_end(): void
    {
        $a = $this->makeClient('Alpha', 1);
        $b = $this->makeClient('Bravo', 2);

        Livewire::test(ClientsAdmin::class)
            ->call('edit', $a->id)
            ->set('sort_order', 99)
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDenseOrder(Client::class, [$b->id, $a->id]);
    }
}
