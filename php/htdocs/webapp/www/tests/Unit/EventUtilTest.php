<?php

namespace Tests\Unit;

use App\Helpers\EventUtil;
use PHPUnit\Framework\TestCase;

class EventUtilTest extends TestCase
{
    private function member(): object
    {
        $e = fn($id) => (object)['eventID' => $id];

        return (object)[
            'translators' => [$e(1), $e(2)],
            'checkersL2' => [$e(2), $e(3)],
            'checkersL3' => [$e(4)],
        ];
    }

    public function testGetMemberEventsMergesRolesWithoutDuplicates(): void
    {
        $this->assertSame([1, 2, 3, 4], array_values(EventUtil::getMemberEvents($this->member())));
    }

    public function testGetMemberEventsEmptyForMemberWithoutRoles(): void
    {
        $member = (object)['translators' => [], 'checkersL2' => [], 'checkersL3' => []];

        $this->assertSame([], EventUtil::getMemberEvents($member));
    }

    public function testIsMemberInEvent(): void
    {
        $this->assertTrue(EventUtil::isMemberInEvent($this->member(), (object)['eventID' => 3]));
        $this->assertFalse(EventUtil::isMemberInEvent($this->member(), (object)['eventID' => 9]));
    }
}
