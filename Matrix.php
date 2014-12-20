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
		$this->set('matrix_row', '');
		$this->set('matrix_column', '');
		$this->set('matrix_value', '');

	}

	/**
	 * Set a value to the matrix: matrix_row, matrix_column, matrix_value
	 *
	 */
	public function set($key, $value) {		
		
		if($key == 'page') {
			$this->page = $value; 
			return $this;
		} 

		//sanitize values as integers
		elseif($key == 'matrix_row' || $key == 'matrix_column') {				
				
				$value = (int) $value; 
		}

		//regular text sanitizer
		elseif($key == 'matrix_value') {			
				
				$value = $this->sanitizer->text($value); 
		}

		return parent::set($key, $value);//back to WireData
	}

	/**
	 * Retrieve a value from the matrix: matrix_row, matrix_column, vamatrix_valuelue
	 *
	 */
	public function get($key) {

		$value = parent::get($key);//retrieve from WireData method get()

		//if the page's output formatting is on, then we'll return sanitized values
		if($this->page && $this->page->of()) {
				
				//sanitize our page IDs as integers
				if($key == 'matrix_row' || $key == 'matrix_column') $value = (int) $value; 
				
				//regular text sanitizer for our values
				if($key == 'matrix_value') $value = $this->sanitizer->text($value);

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

		$out = "<p>" . 	$pages->get($this->matrix_row)->title . "<br>" . 
						$pages->get($this->matrix_column)->title . "<br>
						<em>$this->matrix_value</em><br>					
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

