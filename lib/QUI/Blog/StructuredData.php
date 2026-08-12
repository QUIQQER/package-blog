<?php

namespace QUI\Blog;

/**
 * Helpers for blog structured data.
 */
final class StructuredData
{
    /**
     * Return the edit date only when it represents a real modification.
     */
    public static function getDateModified(mixed $creationDate, mixed $editDate): ?string
    {
        if (!is_string($creationDate) || !is_string($editDate)) {
            return null;
        }

        $CreationDate = self::parseDate($creationDate);
        $EditDate = self::parseDate($editDate);

        if ($CreationDate === null || $EditDate === null || $EditDate <= $CreationDate) {
            return null;
        }

        return $editDate;
    }

    private static function parseDate(string $date): ?\DateTimeImmutable
    {
        if ($date === '') {
            return null;
        }

        try {
            $Date = new \DateTimeImmutable($date);
        } catch (\Exception) {
            return null;
        }

        $errors = \DateTimeImmutable::getLastErrors();

        if ($errors !== false && ($errors['warning_count'] > 0 || $errors['error_count'] > 0)) {
            return null;
        }

        return $Date;
    }
}
