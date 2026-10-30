<?php

namespace App\Support;

/**
 * Fungsi bantu aturan ITIL V3 dari config/itil.php:
 * nama level, rating F/L/P/N, dan pembulatan nilai kematangan.
 */
class Itil
{
    /**
     * Nama maturity level, contoh: 3 -> "Defined".
     */
    public static function levelName(?int $level): string
    {
        return $level === null ? '-' : config("itil.levels.{$level}.name", '-');
    }

    /**
     * Label level dalam bahasa Indonesia, contoh: 3 -> "Terdefinisi".
     */
    public static function levelLabel(?int $level): string
    {
        return $level === null ? '-' : config("itil.levels.{$level}.label", '-');
    }

    /**
     * Konversi persentase ke rating (Tabel 2.2), contoh: 90 -> F, 50 -> P.
     */
    public static function rating(float $percent): string
    {
        foreach (config('itil.ratings') as $code => $rating) {
            $passes = $rating['inclusive'] ? $percent >= $rating['min'] : $percent > $rating['min'];

            if ($passes) {
                return $code;
            }
        }

        return 'N';
    }

    /**
     * Pembulatan nilai kematangan domain, contoh: 2.8333 -> 2.83.
     */
    public static function roundMaturity(float $index): float
    {
        return round($index, config('itil.maturity_decimals'));
    }

    /**
     * Nilai kematangan siap tampil, contoh: 2.8333 -> "2,83".
     */
    public static function formatMaturity(?float $index): string
    {
        return $index === null ? '-' : number_format(self::roundMaturity($index), config('itil.maturity_decimals'), ',', '.');
    }

    /**
     * Persentase siap tampil, contoh: 46.6667 -> "46,67%".
     */
    public static function formatPercent(?float $percent): string
    {
        return $percent === null ? '-' : number_format($percent, 2, ',', '.') . '%';
    }

    /**
     * Nilai kematangan domain ke maturity level (interval Tabel 2.1), contoh: 2.83 -> 3.
     */
    public static function maturityLevel(?float $index): ?int
    {
        if ($index === null) {
            return null;
        }

        $index = self::roundMaturity($index);

        foreach (config('itil.maturity_scale') as $range) {
            if ($index <= $range['max']) {
                return $range['level'];
            }
        }

        return config('itil.max_level');
    }
}
