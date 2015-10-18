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
	* @param Page|string|int|path $rc The row/column whose values to return.
	* @return String $title of row/column to search for.
	*
	*/
	private function getValidgetRC($rc) {

		//@@todo: if method called from a $pages->get?? => how to get the referenced page?

		$this->fn = FieldtypeMatrix::$name;//name of the field to query

		if(is_int($rc)) {
			$p = wire('pages')->get($rc);
			if($p && $p->id > 0) $rc = $p->title;
		}

		//if we got a path
		elseif (preg_match('#^(\/)#', $rc)) {
				$p = wire('pages')->get($this->sanitizer->pagePathName($rc));
				if($p && $p->id > 0) $rc = $p->title;
		}
		
		//if we got a string, we assume page title
		elseif(is_string($rc)) $rc = $this->sanitizer->text($rc);

		//if we got a Page object
		elseif ($rc instanceof Page) $rc = $rc->title;	

		return $rc;


	}

	/**
	* Return all|limited values of the specified Row.
	*
	* @access public
	* @param Page|string|int|path $r The row whose values to return.
	* @param int $limit Limit number of values to return.
	* @param string $sort How to sort the results on fetch.
	* @return Object $values Retrieved results.
	*
	*/
	public function getRow($r, $limit = '', $sort ='') {

		$r = $this->getValidgetRC($r);
		$n = $this->fn;//name of the field to search through
		
		//limit
		if($limit) $limit = (int) $limit;

		//sort
		if($sort == 'asc') $sort = 'value';
		elseif($sort == 'desc') $sort = '-value';		

		$values = wire('page')->$n->find("row=$r, limit=$limit, sort=$sort");

		return $values;

	}

	/**
	* Return all|limited values of the specified Column.
	* 
	* @access public
	* @param Page|string|int|path $r The row whose values to return.
	* @param int $limit Limit number of values to return.
	* @param string $sort How to sort the results on fetch.
	* @return Object $values Retrieved results.
	*
	*/
	public function getColumn($c, $limit = '', $sort ='') {

		$c = $this->getValidgetRC($c);
		$n = $this->fn;//the name of the field to query

		//limit
		if($limit) $limit = (int) $limit;

		//sort
		if($sort == 'asc') $sort = 'value';
		elseif($sort == 'desc') $sort = '-value';		

		$values = wire('page')->$n->find("column=$c, limit=$limit, sort=$sort");

		return $values;

	}


}

