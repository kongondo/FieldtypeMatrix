<?php

/**
 * Contains multiple Matrix objects for a single page
 *
 */

class MatrixArray extends WireArray {

	protected $page;

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
	* Return all|limited values of the specified Row.
	*
	* If Row specified as int|Page object, use in-memory selector
	* If Row specified as title, first get the 
	* 
	* @access public
	* @param Page|string|int|path $r The row whose values to return.
	* @param int $limit Limit number of values to return.
	* @param string $sort How to sort the results on fetch.
	* @return Object $values Retrieved results.
	*/
	public function getRow($r, $limit = '', $sort='') {

		//@@todo: path
		//@@todo: if method called for $pages->get	

		$n = FieldtypeMatrix::$name;

		if(!ctype_digit("$r") && strlen($r)) {
			//first get the page by name, then grab their ID
			$r = wire('pages')->get('name=' . $this->sanitizer->pageName($r));
			if($r and $r->id > 0) $r = $r->id;
		}

		#$r = (int) $r;

		if($limit) $limit = (int) $limit;
		if($sort == 'asc') $sort = 'value';
		elseif($sort == 'desc') $sort = '-value';

		


		$values = wire('page')->$n->find("row=$r, limit=$limit, sort=$sort");

		return $values;
	}

}

