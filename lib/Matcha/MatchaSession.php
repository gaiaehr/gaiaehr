<?php
/**
 * Matcha::connect
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

class MatchaSession extends Matcha
{

    private $__lifetime = null;
    private $__name = null;
    private $__httponly = true;


    public function initializeSession()
    {
        try
        {
            // Securing the Session
            $lifetime = 1800;
            session_cache_limiter('private');
            session_set_cookie_params(
                time()+$this->__lifetime,
                $this->__name,
                "localhost",
                false,
                $this->__httponly
            );
            session_cache_expire($this->__lifetime);
            session_name($this->__name);
            session_start();
        }
        catch(PDOException $e)
        {
            MatchaErrorHandler::__errorProcess($e);
            return false;
        }
    }

    public function setValue($Paramer){
        try
        {
        }
        catch(PDOException $e)
        {
            MatchaErrorHandler::__errorProcess($e);
            return false;
        }
    }

    public function getValue($Paramer){
        try
        {
        }
        catch(PDOException $e)
        {
            MatchaErrorHandler::__errorProcess($e);
            return false;
        }
    }

    public function getAllValue($Paramer){
        try
        {
        }
        catch(PDOException $e)
        {
            MatchaErrorHandler::__errorProcess($e);
            return false;
        }
    }

    public function setLifeTime($Minutes){
        $this->__lifetime = $Minutes;
    }

    public function setName($Name){
        $this->__name = $Name;
    }

    public function setHttpOnly($HttpOnly = true){
        $this->__httponly = $HttpOnly;
    }

}
