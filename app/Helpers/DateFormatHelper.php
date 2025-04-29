<?php

namespace App\Helpers;

class DateFormatHelper
{
    /**
     * Format a date string into d F Y format.
     *
     * @param  string|null  $date
     * @return string|null
     */
    public static function dateFormat(?string $date): ?string
    {
        if (empty($date)) {
            return null;
        }

        $bulan = [
            'January' => 'Januari',
            'February' => 'Februari',
            'March' => 'Maret',
            'April' => 'April',
            'May' => 'Mei',
            'June' => 'Juni',
            'July' => 'Juli',
            'August' => 'Agustus',
            'September' => 'September',
            'October' => 'Oktober',
            'November' => 'November',
            'December' => 'Desember',
        ];

        $timestamp = strtotime($date);
        return date('d', $timestamp) . ' ' . ($bulan[date('F', $timestamp)] ?? date('F', $timestamp)) . ' ' . date('Y', $timestamp);
    }

    /**
     * Format a date string into F format.
     *
     * @param  string|null  $date
     * @return string|null
     */
    public static function monthFormat(?string $date): ?string
    {
        if (empty($date)) {
            return null;
        }

        $bulan = [
            'January' => 'Januari',
            'February' => 'Februari',
            'March' => 'Maret',
            'April' => 'April',
            'May' => 'Mei',
            'June' => 'Juni',
            'July' => 'Juli',
            'August' => 'Agustus',
            'September' => 'September',
            'October' => 'Oktober',
            'November' => 'November',
            'December' => 'Desember',
        ];

        $timestamp = strtotime($date);
        return ($bulan[date('F', $timestamp)] ?? date('F', $timestamp));
    }

    /**
     * Format a date string into Y/Y+1 format.
     *
     * @param  string|null  $date
     * @return string|null
     */
    public static function academicYearFormat(?string $date): ?string
    {
        if (empty($date)) {
            return null;
        }

        return date("Y", strtotime($date)). '/' .(date("Y", strtotime($date))+1);
    }
}
