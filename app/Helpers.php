<?php

if (! function_exists('format_stat')) {
    function format_stat($value)
    {
        // 1. Handle null, empty strings, or completely missing/invalid data
        if (is_null($value) || trim((string)$value) === '' || !is_numeric($value)) {
            return '0';
        }

        // 2. Cast to a float so we can safely check its value
        $number = (float) $value;

        // 3. Handle exactly zero (This prevents the "00" issue on your dashboard)
        if ($number == 0) {
            return '0';
        }

        // 4. Handle decimal numbers (e.g., 5.5). We usually don't pad decimals.
        if (floor($number) != $number) {
            return (string) $value;
        }

        // 5. Handle single digits (1-9 and -1 to -9)
        // Note: sprintf('%02d') is smart enough to turn -7 into -07 automatically
        if (abs($number) > 0 && abs($number) < 10) {
            return sprintf('%02d', $number);
        }

        // 6. Handle 10 and above (or -10 and below)
        return (string) $number;
    }
}