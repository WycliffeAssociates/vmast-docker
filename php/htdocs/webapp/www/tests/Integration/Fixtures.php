<?php

namespace Tests\Integration;

/**
 * Builders for test rows. Each fills the columns that have no default in production (db/schema.sql),
 * so a test only states what it cares about. Returns the new row's ID where the table has one.
 */
trait Fixtures
{
    protected function member(array $row = []): int
    {
        static $n = 0;
        $n++;
        return $this->insert('members', $row + [
            'userName' => "user$n",
            'firstName' => "First$n",
            'lastName' => "Last$n",
            'email' => "user$n@example.org",
            'tempEmail' => '',
            'active' => 1,
            'verified' => 1,
        ]);
    }

    protected function language(string $langID, ?string $langName = null): void
    {
        $this->insert('languages', [
            'langID' => $langID,
            'langName' => $langName ?? strtoupper($langID),
            'angName' => $langName ?? strtoupper($langID),
        ]);
    }

    protected function book(string $code, string $name, int $chaptersNum, int $sort): void
    {
        $this->insert('book_info', [
            'sort' => $sort,
            'code' => $code,
            'name' => $name,
            'chaptersNum' => $chaptersNum,
            'difficulty' => '1',
        ]);
    }

    protected function project(array $row = []): int
    {
        return $this->insert('projects', $row + [
            'glID' => $row['glID'] ?? $this->insert('gateway_languages', ['gwLang' => 'en']),
            'gwLang' => 'en',
            'targetLang' => 'en',
            'sourceLangID' => 'en',
            'bookProject' => 'ulb',
            'sourceBible' => 'ulb',
        ]);
    }

    protected function event(int $projectID, array $row = []): int
    {
        return $this->insert('events', $row + [
            'projectID' => $projectID,
            'bookCode' => 'gen',
            'state' => 'translating',
        ]);
    }

    protected function eventAdmin(int $memberID, int $eventID): void
    {
        self::db()->table('member_event')->insert(['memberID' => $memberID, 'eventID' => $eventID]);
    }

    /** Check columns (peerCheck, kwCheck, ...) may be given as arrays and are stored as JSON. */
    protected function translator(int $memberID, int $eventID, array $row = []): int
    {
        foreach (['verbCheck', 'peerCheck', 'kwCheck', 'crCheck', 'otherCheck'] as $check) {
            if (isset($row[$check]) && is_array($row[$check])) {
                $row[$check] = json_encode($row[$check]);
            }
        }

        return $this->insert('translators', $row + [
            'memberID' => $memberID,
            'eventID' => $eventID,
            'step' => 'pray',
            'currentChapter' => 0,
            'currentChunk' => 0,
            'verbCheck' => '',
            'peerCheck' => '',
            'kwCheck' => '',
            'crCheck' => '',
            'otherCheck' => '',
        ]);
    }

    /** A chapter assigned to the translator $trID, in that translator's event. */
    protected function chapter(int $trID, int $chapter, array $row = []): int
    {
        $translator = self::db()->table('translators')->where('trID', $trID)->first();

        return $this->insert('chapters', $row + [
            'eventID' => $translator->eventID,
            'memberID' => $translator->memberID,
            'trID' => $trID,
            'chapter' => $chapter,
            'l2memberID' => 0,
            'l3memberID' => 0,
            'l2chID' => 0,
            'l3chID' => 0,
            'chunks' => '',
        ]);
    }

    protected function wordGroup(int $eventID, array $words): int
    {
        return $this->insert('word_groups', ['eventID' => $eventID, 'words' => json_encode($words)]);
    }
}
