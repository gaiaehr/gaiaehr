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

class Message {


	public $hl7;

	public $evt = array();

	public $data = array();

	public $errors = array();

	public $segmentIndex = 0;

	function __construct($hl7){
		$this->hl7 = $hl7;
	}

	public function readMessage($event){
		$this->evt = $this->Events($event);
		$this->groupWorker($this->evt);

		unset($this->hl7, $this->evt, $this->segmentIndex);

		print_r($this->data);
		return $this;
	}

	private function groupWorker($array){

		foreach($array AS $key => $val){
			// if is segment
			if($this->isSegment($key)){
				$array[$key] = $this->getNextSegment($key, !$this->isRepeatable($val));
				continue;
			}
			// is group
			if($this->isRepeatable($val)){
				$array[$key] = array();
				while($a = $this->groupWorker($val['items'])){
					if(is_string($a) || !current($a)) break;
					$curr = current($a);
					if(is_string($curr) || !current($curr)) break;
					$array[$key][] = $a;
				}
			}else{
				$array[$key] = $this->groupWorker($val['items']);
			}
		};
		return $this->data = $array;
	}


	private function getNextSegment($seg, $onlyOne){
		$len = count($this->hl7->segments);
		$segs = array();
		for($i = $this->segmentIndex; $i < $len; $i++, $this->segmentIndex++){
			if($onlyOne && isset($segs[0])) return $segs[0];
			if(get_class($this->hl7->segments[$this->segmentIndex]) == $seg){
				$segs[] = $this->hl7->segments[$this->segmentIndex]->data;
			}
			if(get_class($this->hl7->segments[$this->segmentIndex]) != $seg) break;
		}

		if(count($segs) > 1) return $segs;
		return false;
	}

	private function isRequired($foo){
		return isset($foo['required']) && $foo['required'];
	}

	private function isRepeatable($foo){
		return isset($foo['repeatable']) && $foo['repeatable'];
	}

	private function getItems($foo){
		if(!isset($foo['items'])) return false;
		return $foo['items'];
	}

	private function isSegment($foo){
		return strlen($foo) == 3;
	}

	protected function Events($event){
		return $event;
	}
}