<?php

namespace App\Helpers;


class Helper
{

    /**
     * Helper to transform a bool to a label active / inactive.
     *
     * @param bool        $condition        This is the bool value to be mapped.
     * @param string|null $text             This text will be concatenate to label. Optional.
     * @param array|null  $mapStatusToLabel The map to transform bool to label. Optional.
     *
     * @return string
     */
    public static function addStatusLabel(bool $condition, string $text = null, array $mapStatusToLabel = null): string
    {
        if (is_null($mapStatusToLabel)) {
            $mapStatusToLabel = [
                '0' => '<span class="badge badge-secondary">' . __('general.disabled') . '</span>',
                '1' => '',
            ];
        }
        $concatenationSpace = empty($text) ? '' : ' ';
        return trim($text . $concatenationSpace . $mapStatusToLabel[$condition], ' ');
    }

}
