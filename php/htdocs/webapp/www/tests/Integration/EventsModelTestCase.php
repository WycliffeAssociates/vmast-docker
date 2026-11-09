<?php

namespace Tests\Integration;

use App\Models\EventsModel;
use App\Models\ORM\Event;
use App\Repositories\Event\EventRepository;

/** EventsModel with two books and two languages in place. */
abstract class EventsModelTestCase extends DatabaseTestCase
{
    use Fixtures;

    protected EventsModel $model;

    protected function setUp(): void
    {
        parent::setUp();
        $this->model = new EventsModel(new EventRepository(new Event()));

        $this->language('en', 'English');
        $this->language('id', 'Indonesian');
        $this->book('gen', 'Genesis', 50, 1);
        $this->book('exo', 'Exodus', 40, 2);
    }

    /** IDs of the admins attached to each listed event, keyed by book name. */
    protected function adminsByBook(array $events, string $nameField = 'name'): array
    {
        $admins = [];
        foreach ($events as $event) {
            $admins[$event->$nameField] = $event->admins->map(fn($m) => $m->memberID)->toArray();
        }
        return $admins;
    }
}
