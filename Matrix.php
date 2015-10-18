<?php

/**
 * An individual matrix item to be part of an MatrixArray for a Page
 *
 */
class Matrix extends WireData {


	/**
	 * We keep a copy of the $page that owns this matrix so that we can follow
	 * its outputFormatting state and change our output per that state
	 *
	 */
	protected $page; 

	/**
	 * Construct a new Matrix
	 *
	 */
	public function __construct() {

		//define the fields that represent our matrix's items
		$this->set('row', '');
		$this->set('column', '');
		$this->set('value', '');

	}

	/**
	 * Set a value to the matrix: row, column, value
	 *
	 */
	public function set($key, $value) {		
		
		if($key == 'page') {
			$this->page = $value; 
			return $this;
		}
		//sanitize values as integers
		elseif($key == 'row' || $key == 'column') $value = (int) $value; 
		//regular text sanitizer
		elseif($key == 'value') $value = $this->sanitizer->text($value); 

		return parent::set($key, $value);//back to WireData
	}

	/**
	 * Retrieve a value from the matrix: row, column, value
	 *
	 */
	public function get($key) {

		$value = parent::get($key);//retrieve from WireData method get()

		//if the page's output formatting is on, then we'll return sanitized values
		if($this->page && $this->page->of()) {

			//for rows and columns show user friendly output (titles rather than IDs)
			if($key == 'row' || $key == 'column') {					
				$p = wire('pages')->get((int)$value);
				if($p && $p->id > 0) $value = $p->title;
			}			
			//regular text sanitizer for our values
			elseif($key == 'value') $value = $this->sanitizer->text($value);

		}

		return $value; 
	}


	/**
	 * Provide a default rendering for an matrix
	 *
	 */
	public function renderMatrix() {

		$pages = wire('pages');

		//remember page's output formatting state
		$of = $this->page->of();
		//turn on output formatting for our rendering (if it's not already on)
		if(!$of) $this->page->of(true);

		$out = "<p>" . 	$this->row . "<br>" .
						$this->column . "<br>
						<em>$this->value</em><br>					
		</p>";	
		
		if(!$of) $this->page->of(false);

		return $out;
	}

	/**
	 * Return a string representing this matrix
	 *
	 */
	public function __toString() {

		return $this->renderMatrix();
	}

}
