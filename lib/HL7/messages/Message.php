<?php
/**
 * GaiaEHR (Electronic Health Records)
 * Copyright (C) 2013 Certun, LLC.
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

class Message {

	/**
	 * @var HL7
	 */
	public $hl7;

	/**
	 * @var array
	 */
	public $evt = array();

	/**
	 * @var array
	 */
	public $data = array();

	/**
	 * @var bool|array
	 */
	public $errors = false;

	/**
	 * @var int
	 */
	public $segmentIndex = 0;

	/**
	 * @param $hl7
	 */
	function __construct($hl7){
		$this->hl7 = $hl7;
	}

	function __destruct(){
		$this->segmentIndex = 0;
		$this->errors = false;
		$this->data = array();
//		print 'Destroying class "'. get_class($this). '" (Message)'.PHP_EOL;
	}

	/**
	 * @param $event
	 * @return $this
	 */
	public function readMessage($event){
		$this->groupWorker($this->Events($event));
		unset($this->hl7, $this->evt, $this->segmentIndex);
		return $this;
	}

	/**
	 * @param $array
	 * @return mixed
	 */
	private function groupWorker($array){
		$items = isset($array['items']) ? $array['items'] : $array;
		foreach($items AS $key => $val){
			// if is segment
			if($this->isSegment($key)){
				$items[$key] = $this->getNextSegment($key, !$this->isRepeatable($val));
				continue;
			}
			// is group
			if($this->isRepeatable($val)){
				$items[$key] = array();
				while($a = $this->groupWorker($val)){

					if($this->isRequired($val)){
						$items[$key][] = $a;
						break;
					};

					if(is_string($a) || !current($a)) break;
					$curr = current($a);
					if(is_string($curr) || !current($curr)) break;
					$curr = current($a);
					if(is_string($curr) || !current($curr)) break;
					if(empty($a)) break;
					$items[$key][] = $a;

				}
			}else{
				$a = $this->groupWorker($val);
				if(is_string($a) || !current($a)) {
					unset($items[$key]);
					continue;
				}
				$items[$key] = $a;

			}
		};

		return $this->data = $items;
	}

	/**
	 * @param $seg
	 * @param $onlyOne
	 * @return array|bool
	 */
	private function getNextSegment($seg, $onlyOne){
		$len = count($this->hl7->segments);
		$foo = array();

		if($onlyOne) {
			$i = $this->segmentIndex;
			if(isset($this->hl7->segments[$i]) && get_class($this->hl7->segments[$i]) == $seg){
				$this->segmentIndex++;
				return $this->hl7->segments[$i]->data;
			}
		}else{
			for($i = $this->segmentIndex; $i < $len; $i++){
				if(get_class($this->hl7->segments[$i]) == $seg){
					$foo[] = $this->hl7->segments[$i]->data;
					$this->segmentIndex++;
					continue;
				}
				break;
			}

			if(count($foo) >= 1) return $foo;
		}

		return false;
	}

	/**
	 * @param $foo
	 * @return bool
	 */
	private function isRequired($foo){
		return isset($foo['required']) && $foo['required'];
	}

	/**
	 * @param $foo
	 * @return bool
	 */
	private function isRepeatable($foo){
		return isset($foo['repeatable']) && $foo['repeatable'];
	}

	/**
	 * @param $foo
	 * @return bool
	 */
	private function getItems($foo){
		if(!isset($foo['items'])) return false;
		return $foo['items'];
	}

	/**
	 * @param $foo
	 * @return bool
	 */
	private function isSegment($foo){
		return strlen($foo) == 3;
	}

	/**
	 * @param $event
	 * @return mixed
	 */
	protected function Events($event){
		return $event;
	}
}