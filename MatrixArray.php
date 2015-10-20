<?php

/**
 * Contains multiple Matrix objects for a single page
 *
 */

class MatrixArray extends WireArray {

	protected $page;
	protected $fn;

	public function __construct(Page $page) {

		$this->page = $page;

	}

	public function isValidItem($item) {

		return $item instanceof Matrix;//item returned as instance of Matrix
	}

	public function add($item) {

		$item->page = $this->page; 
		return parent::add($item);//back to WireArray
	}

	public function __toString() {

		$out = '';
		foreach($this as $item) $out .= $item;
		return $out; 
	}

	/**
	* Determine data type of passed row/column.
	*
	* Use by in-memory selector in getRow()/getColumn().
	*
	* @access private
	* @param Page|string|int|path $sv The row/column whose values to return.
	* @param String $type Whether dealing with 'row' or 'column'.
	* @return String $rowSel Selector string to search for.
	*
	*/
	private function getValidgetRC($sv, $type) {

		$rowSel = '';
		$this->fn = FieldtypeMatrix::$name;//name of the field to query

		if(is_int($sv)) $rowSel = $type . "=" . $sv;
		//if we got a path
		elseif (preg_match('#^(\/)#', $sv)) {
				$p = wire('pages')->get($this->sanitizer->pagePathName($sv));
				if($p && $p->id > 0) $rowSel = $type . "=" . (int) $p->id;
		}		
		//if we got a string, we assume page title
		elseif(is_string($sv)) $rowSel = $type . "Label=" . $this->sanitizer->text($sv);//row||columnLabel
		//if we got a Page object
		elseif ($sv instanceof Page) $rowSel = $type . "Label=" . $this->sanitizer->text($sv->title);//row||columnLabel

		return $rowSel;

	}

	/**
	* Return all|limited values of the specified Row.
	*
	* @access public
	* @param Page|string|int|path $sv The row whose values to return.
	* @param int $limit Limit number of values to return.
	* @param string $sort How to sort the results on fetch.
	* @return Object $values Retrieved results.
	*
	*/
	public function getRow($sv, $limit = '', $sort ='') {

		//@@todo: update to work with $pages->get?

		$rowSel = $this->getValidgetRC($sv, 'row');
		$n = $this->fn;//name of the field to search through

		//limit
		if($limit) $limit = (int) $limit;

		//sort
		if($sort == 'asc') $sort = 'value';
		elseif($sort == 'desc') $sort = '-value';		

		$values = wire('page')->$n->find("$rowSel, limit=$limit, sort=$sort");

		return $values;

	}

	/**
	* Return all|limited values of the specified Column.
	* 
	* @access public
	* @param Page|string|int|path $sv The row whose values to return.
	* @param int $limit Limit number of values to return.
	* @param string $sort How to sort the results on fetch.
	* @return Object $values Retrieved results.
	*
	*/
	public function getColumn($sv, $limit = '', $sort ='') {

		//@@todo: update to work with $pages->get?

		$colSel = $this->getValidgetRC($sv, 'column');
		$n = $this->fn;//the name of the field to query

		//limit
		if($limit) $limit = (int) $limit;

		//sort
		if($sort == 'asc') $sort = 'value';
		elseif($sort == 'desc') $sort = '-value';		

		$values = wire('page')->$n->find("$colSel, limit=$limit, sort=$sort");

		return $values;

	}


}

