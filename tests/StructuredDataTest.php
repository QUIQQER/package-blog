<?php

namespace QUI\Blog;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class StructuredDataTest extends TestCase
{
    #[DataProvider('modificationDateProvider')]
    public function testModificationDateRequiresAValidLaterEditDate(
        mixed $creationDate,
        mixed $editDate,
        ?string $expected
    ): void {
        self::assertSame($expected, StructuredData::getDateModified($creationDate, $editDate));
    }

    public static function modificationDateProvider(): iterable
    {
        yield 'later edit date' => [
            '2026-08-12 10:00:00',
            '2026-08-12 11:00:00',
            '2026-08-12 11:00:00'
        ];
        yield 'same date' => [
            '2026-08-12 10:00:00',
            '2026-08-12 10:00:00',
            null
        ];
        yield 'earlier edit date' => [
            '2026-08-12 10:00:00',
            '2026-08-12 09:00:00',
            null
        ];
        yield 'missing edit date' => ['2026-08-12 10:00:00', null, null];
        yield 'invalid edit date' => ['2026-08-12 10:00:00', 'not-a-date', null];
        yield 'impossible edit date' => ['2026-08-12 10:00:00', '2026-02-30 10:00:00', null];
        yield 'missing creation date' => [null, '2026-08-12 11:00:00', null];
        yield 'ISO 8601 dates' => [
            '2026-08-12T10:00:00+02:00',
            '2026-08-12T09:00:01Z',
            '2026-08-12T09:00:01Z'
        ];
    }
}
