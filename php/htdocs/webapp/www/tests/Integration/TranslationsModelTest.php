<?php

namespace Tests\Integration;

use App\Models\TranslationsModel;
use Helpers\ProjectFile;
use PHPUnit\Framework\Attributes\DataProvider;
use ZipArchive;

class TranslationsModelTest extends DatabaseTestCase
{
    use Fixtures;

    private TranslationsModel $model;
    private int $translator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->model = new TranslationsModel();
        $this->language('en', 'English');
        $this->language('id', 'Indonesian');
        $this->book('gen', 'Genesis', 50, 1);
        $this->book('exo', 'Exodus', 40, 2);
        $this->translator = $this->member(['userName' => 'jsmith', 'firstName' => 'John', 'lastName' => 'Smith']);
    }

    /** A translator in a new event of $bookProject into Indonesian; returns the trID. */
    private function translating(string $bookProject = 'ulb', string $bookCode = 'gen', string $sourceBible = 'ulb'): int
    {
        $eventID = $this->event($this->project(['bookProject' => $bookProject, 'sourceBible' => $sourceBible, 'targetLang' => 'id']), ['bookCode' => $bookCode]);
        return $this->translator($this->translator, $eventID);
    }

    private function chunks(array $rows): array
    {
        return array_map(fn($r) => [$r->chapter, $r->chunk], $rows);
    }

    // --- Finished translations (the published work)

    public function testGetTranslationOnlyHasFinishedChunksInBookAndChapterOrder(): void
    {
        $exodus = $this->translating('ulb', 'exo');
        $this->translation($exodus, 1, 0, [], ['translateDone' => 1]);
        $genesis = $this->translating();
        $this->chapter($genesis, 2, ['chunks' => '[[1,2]]']);
        $this->translation($genesis, 2, 1, [], ['translateDone' => 1]);
        $this->translation($genesis, 2, 0, ['1' => 'In the beginning'], ['translateDone' => 1]);
        $this->translation($genesis, 1, 0, [], ['translateDone' => 1]);
        $this->translation($genesis, 3, 0);

        $rows = $this->model->getTranslation('id', 'ulb', 'ulb');

        $this->assertSame([[1, 0], [2, 0], [2, 1], [1, 0]], $this->chunks($rows), 'unfinished chunks are left out');
        $this->assertSame(['Genesis', 'Indonesian', 'translating', '[[1,2]]'], [$rows[1]->bookName, $rows[1]->langName, $rows[1]->state, $rows[1]->chunks]);
        $this->assertSame('Exodus', $rows[3]->bookName);
        $this->assertSame([[1, 0]], $this->chunks($this->model->getTranslation('id', 'ulb', 'ulb', 'exo')));
        $this->assertSame([], $this->model->getTranslation('id', 'ulb', 'odb'));
        $this->assertSame([], $this->model->getTranslation('en', 'ulb', 'ulb'));
    }

    public function testGetWordsTranslationHasTheGroupsWords(): void
    {
        $trID = $this->translating('tw', 'wkt');
        $groupID = $this->wordGroup(self::db()->table('translators')->where('trID', $trID)->first()->eventID, ['god', 'love']);
        $this->translation($trID, $groupID, 0, [], ['translateDone' => 1, 'bookCode' => 'wkt']);

        $this->assertSame('["god","love"]', $this->model->getTranslation('id', 'tw', 'ulb')[0]->words);
    }

    public function testTranslationLanguagesProjectsAndBooks(): void
    {
        $this->translating('ulb', 'gen');
        $this->translating('ulb', 'exo');
        $this->translating('tn', 'gen');
        $this->project(['targetLang' => 'en']);

        $this->assertSame(['en', 'id'], array_map(fn($l) => $l->targetLang, $this->model->getTranslationLanguages()));
        // grouped by the bookProject enum, so in its declared order (udb, ulb, tn, ...)
        $this->assertSame([['ulb', 'ulb'], ['tn', 'ulb']], array_map(fn($p) => [$p->bookProject, $p->sourceBible], $this->model->getTranslationProjects('id')));
        $this->assertSame(['Genesis', 'Exodus'], array_map(fn($b) => $b->bookName, $this->model->getTranslationBooks('id', 'ulb', 'ulb')));
    }

    // --- Work in progress of a translator or an event

    public function testEventTranslationOfTranslatorByChapterAndChunk(): void
    {
        $trID = $this->translating();
        $this->translation($trID, 1, 1);
        $this->translation($trID, 1, 0, [], ['firstvs' => 5]);
        $this->translation($trID, 2, 0, [], ['firstvs' => 1]);
        $this->translation($this->translator($this->member(), $this->event($this->project())), 1, 0);

        $this->assertSame([[2, 0], [1, 0], [1, 1]], $this->chunks($this->model->getEventTranslation($trID)), 'ordered by chunk, then first verse');
        $this->assertSame([[1, 0], [1, 1]], $this->chunks($this->model->getEventTranslation($trID, 1)));
        $this->assertSame([[1, 1]], $this->chunks($this->model->getEventTranslation($trID, 1, 1)));
        $this->assertCount(3, $this->model->getEventTranslation($trID, null, 1), 'a chunk without a chapter is ignored');
    }

    public function testEventTranslationOfWholeEvent(): void
    {
        $trID = $this->translating();
        $eventID = self::db()->table('translators')->where('trID', $trID)->first()->eventID;
        $this->translation($trID, 1, 0);
        $this->translation($this->translator($this->member(), $eventID), 2, 0);
        $this->translation($this->translating('ulb', 'exo'), 1, 0);

        $this->assertCount(2, $this->model->getEventTranslationByEventID($eventID));
        $this->assertSame([[2, 0]], $this->chunks($this->model->getEventTranslationByEventID($eventID, 2, 0)));
    }

    public function testCreateUpdateAndDeleteTranslation(): void
    {
        $tID = $this->translation($this->translating(), 1, 0, ['1' => 'draft']);

        $this->assertSame(1, $this->model->updateTranslation(['translateDone' => 1], ['tID' => $tID]));
        $this->assertSame(1, (int)$this->model->getEventTranslationByEventID(
            self::db()->table('translations')->where('tID', $tID)->first()->eventID)[0]->translateDone);
        $this->assertSame(1, $this->model->deleteTranslation(['tID' => $tID]));
        $this->assertSame(0, self::db()->table('translations')->count());
    }

    // --- Comments and keywords

    public function testCommentsNewestFirstWithAuthorAndFilters(): void
    {
        $eventID = self::db()->table('translators')->where('trID', $this->translating())->first()->eventID;
        $comment = fn($chapter, $chunk, $text, $row = []) => $this->model->createComment($row + [
            'memberID' => $this->translator, 'eventID' => $eventID, 'chapter' => $chapter, 'chunk' => $chunk, 'text' => $text]);

        $comment(1, 0, 'first');
        $comment(1, 1, 'second');
        $comment(2, 0, 'third', ['level' => 2, 'saved' => 1]);

        $texts = fn(...$args) => array_map(fn($c) => $c->text, $this->model->getCommentsByEvent($eventID, ...$args));
        $this->assertSame(['third', 'second', 'first'], $texts());
        $this->assertSame(['second', 'first'], $texts(1));
        $this->assertSame(['first'], $texts(1, 0));
        $this->assertSame('jsmith', $this->model->getCommentsByEvent($eventID)[0]->userName);

        $this->assertSame(['third'], array_map(fn($c) => $c->text, $this->model->getComment($eventID, 2, 0, $this->translator, 2, 1)));
        $this->assertSame([], $this->model->getComment($eventID, 2, 0, $this->translator, 1, 1));
    }

    public function testUpdateAndDeleteComment(): void
    {
        $eventID = self::db()->table('translators')->where('trID', $this->translating())->first()->eventID;
        $cID = $this->model->createComment(['memberID' => $this->translator, 'eventID' => $eventID, 'chapter' => 1, 'chunk' => 0, 'text' => 'old']);

        $this->assertSame(1, $this->model->updateComment(['text' => 'new'], ['cID' => $cID]));
        $this->assertSame('new', $this->model->getCommentsByEvent($eventID)[0]->text);
        $this->assertSame(1, $this->model->deleteComment(['cID' => $cID]));
        $this->assertSame([], $this->model->getCommentsByEvent($eventID));
    }

    public function testKeywordsInVerseOrder(): void
    {
        $eventID = self::db()->table('translators')->where('trID', $this->translating())->first()->eventID;
        $keyword = fn($chapter, $chunk, $verse, $text) => $this->model->createKeyword([
            'memberID' => $this->translator, 'eventID' => $eventID, 'chapter' => $chapter, 'chunk' => $chunk,
            'verse' => $verse, 'indexOrder' => 0, 'text' => $text]);

        $keyword(2, 0, '1', 'grace');
        $last = $keyword(1, 1, '3', 'love');
        $keyword(1, 0, '2', 'God');
        $keyword(1, 0, '1', 'Lord');

        $this->assertSame(['Lord', 'God', 'love', 'grace'], array_map(fn($k) => $k->text, $this->model->getKeywords(['eventID' => $eventID])));
        $this->assertSame(1, $this->model->deleteKeyword($last));
        $this->assertSame(['Lord', 'God'], array_map(fn($k) => $k->text, $this->model->getKeywords(['eventID' => $eventID, 'chapter' => 1])));
    }

    // --- Reference data

    public function testSourcesLanguageAndBookInfo(): void
    {
        $this->insert('sources', ['langID' => 'id', 'slug' => 'ayt', 'name' => 'Alkitab']);
        $this->insert('sources', ['langID' => 'en', 'slug' => 'ulb', 'name' => 'ULB']);
        $this->insert('sources', ['langID' => 'en', 'slug' => 'udb', 'name' => 'UDB']);

        $this->assertSame([['en', 'udb', 'English'], ['en', 'ulb', 'English'], ['id', 'ayt', 'Indonesian']],
            array_map(fn($s) => [$s->langID, $s->slug, $s->langName], $this->model->getSources()));
        $this->assertSame('Indonesian', $this->model->getLanguageInfo('id')[0]->langName);
        $this->assertSame(50, $this->model->getBookInfo('gen')[0]->chaptersNum);
    }

    // --- Export manifests

    private static function exportData(array $overrides = []): object
    {
        return (object)($overrides + [
            'bookProject' => 'ulb', 'sourceBible' => 'ulb', 'targetLang' => 'id', 'langName' => 'Indonesian',
            'direction' => 'ltr', 'sourceLangID' => 'en', 'resLangID' => 'en', 'bookCode' => 'gen', 'bookName' => 'Genesis',
        ]);
    }

    public static function manifests(): array
    {
        return [
            'scripture' => [[], 'text/usfm', 'bundle', 'Bible', 'ulb', ['id/tw', 'id/tq', 'id/tn'], ['ulb', 'en']],
            'notes' => [['bookProject' => 'tn'], 'text/markdown', 'help', 'tn', 'tn', ['id/ulb', 'id/udb'], ['tn', 'en']],
            'words' => [['bookProject' => 'tw'], 'text/markdown', 'dict', 'tw', 'tw', ['id/ulb', 'id/udb', 'id/obs', 'id/tn', 'id/tq'], ['tw', 'en']],
            'obs' => [['bookProject' => 'obs'], 'text/markdown', 'book', 'obs', 'obs', ['id/tw', 'id/tq', 'id/tn'], ['obs', 'en']],
            'bible commentary articles' => [['bookProject' => 'bca'], 'text/markdown', 'help', 'bca', 'bc', ['id/ulb', 'id/udb'], ['bc', 'en']],
            'our daily bread' => [['bookProject' => 'sun', 'sourceBible' => 'odb'], 'text/json', 'bundle', 'Our Daily Bread', 'sun', [], ['odb', 'en']],
            'radio' => [['bookProject' => 'rad', 'sourceBible' => 'rad'], 'text/json', 'bundle', 'Radio', 'rad', [], ['rad', 'en']],
        ];
    }

    #[DataProvider('manifests')]
    public function testManifest(array $overrides, string $format, string $type, string $subject, string $identifier, array $relation, array $source): void
    {
        $core = $this->model->generateManifest(self::exportData($overrides))->output()['dublin_core'];

        $this->assertSame(
            [$format, $type, $subject, $identifier, $relation, [['identifier' => $source[0], 'language' => $source[1], 'version' => '1']]],
            [$core['format'], $core['type'], $core['subject'], $core['identifier'], $core['relation'], $core['source']]
        );
        $this->assertSame(['direction' => 'ltr', 'identifier' => 'id', 'title' => 'Indonesian'], $core['language']);
        $this->assertSame(date('Y-m-d'), $core['issued']);
    }

    public function testTranslationStudioAndPackageManifests(): void
    {
        $tstudio = $this->model->generateTstudioManifest(self::exportData())->output();
        $package = $this->model->generatePackageManifest(self::exportData(['direction' => 'rtl']))->output();

        $this->assertSame(['id' => 'id', 'name' => 'Indonesian', 'direction' => 'ltr'], $tstudio['target_language']);
        $this->assertSame(['id' => 'gen', 'name' => 'Genesis'], $tstudio['project']);
        $this->assertSame([['language_id' => 'en', 'resource_id' => 'ulb', 'checking_level' => '3', 'date_modified' => '', 'version' => '']],
            $tstudio['source_translations']);
        $this->assertSame([['path' => 'id_gen_text_ulb', 'id' => 'id_gen_text_ulb', 'commit_hash' => ['stdout' => '', 'stderr' => '', 'error' => ''], 'direction' => 'rtl']],
            $package['target_translations']);
    }

    public function testZipOfContentFilesAndDirectories(): void
    {
        $onDisk = tempnam(sys_get_temp_dir(), 'vmast');
        file_put_contents($onDisk, 'from disk');

        ob_start();
        $this->model->generateZip('export.zip', [
            ProjectFile::withContent('id_gen/', null),
            ProjectFile::withContent('id_gen/manifest.json', '{}'),
            ProjectFile::withFile('id_gen/01.usfm', $onDisk),
        ]);
        $zipFile = tempnam(sys_get_temp_dir(), 'vmast');
        file_put_contents($zipFile, ob_get_clean());

        $zip = new ZipArchive();
        $this->assertTrue($zip->open($zipFile));
        $this->assertSame(['id_gen/', 'id_gen/manifest.json', 'id_gen/01.usfm'],
            array_map(fn($i) => $zip->getNameIndex($i), range(0, $zip->numFiles - 1)));
        $this->assertSame('from disk', $zip->getFromName('id_gen/01.usfm'));
        $zip->close();
        unlink($onDisk);
        unlink($zipFile);
    }
}
