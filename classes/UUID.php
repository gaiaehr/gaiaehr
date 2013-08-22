<?php
/**
 * GaiaEHR (Electronic Health Records)
 * Copyright (C) 2013 Certun, inc.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

class UUID
{
    /**
     * Generates version 1: MAC address
     */
    public static function v1()
    {
        if (!function_exists('uuid_create'))
            return false;

        uuid_create(&$context);
        uuid_make($context, UUID_MAKE_V1);
        uuid_export($context, UUID_FMT_STR, &$uuid);
        return trim($uuid);
    }

    /**
     * Generates version 3 UUID: MD5 hash of URL
     */
    public static function v3($i_url)
    {
        if (!function_exists('uuid_create'))
            return false;

        if (!strlen($i_url))
            $i_url = self::v1();

        uuid_create(&$context);
        uuid_create(&$namespace);

        uuid_make($context, UUID_MAKE_V3, $namespace, $i_url);
        uuid_export($context, UUID_FMT_STR, &$uuid);
        return trim($uuid);
    }

    /**
     * Generates version 4 UUID: random
     */
    public static function v4()
    {
        if (!function_exists('uuid_create'))
            return false;

        uuid_create(&$context);

        uuid_make($context, UUID_MAKE_V4);
        uuid_export($context, UUID_FMT_STR, &$uuid);
        return trim($uuid);
    }

    /**
     * Generates version 5 UUID: SHA-1 hash of URL
     */
    public static function v5($i_url)
    {
        if (!function_exists('uuid_create'))
            return false;

        if (!strlen($i_url))
            $i_url = self::v1();

        uuid_create(&$context);
        uuid_create(&$namespace);

        uuid_make($context, UUID_MAKE_V5, $namespace, $i_url);
        uuid_export($context, UUID_FMT_STR, &$uuid);
        return trim($uuid);
    }
}