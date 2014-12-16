# FieldtypeMatrix

This modules serves as an example of creating an editable table of 
data as a Fieldtype and Inputfield in ProcessWire. In this case, we
create a simple table of matrix each with date, location and notes.
This pattern can be adapted to nearly any table of information. 

Note that this module is intended as a proof-of-concept. If you 
find it useful for the example scenario (matrix) then great, but 
keep in mind it is not intended as a comprehensive matrix solution,
where using ProcessWire pages may be a better fit. 


## Install

1. Copy the files for this module to /site/modules/FieldtypeMatrix/ 
2. In admin: Modules > Check for new modules. Install Fieldtype > Matrix.
3. Create a new field of type Matrix, and name it whatever you would 
   like. In our examples we named it simply "matrix". 
4. Add the field to a template and edit a page using that template. 

## Output 

A typical output case for this module would work like this:

``````
foreach($page->matrix as $m) {
  echo "
    <p>
    Date: $m->matrix_row<br />
    Location: $m->matrix_column<br />
    Notes: $m->matrix_value
    </p>
    ";
}
``````

OR TABLE HERE?

``````


foreach($page->matrix as $m) {
  echo "
    <p>
    Date: $m->matrix_row<br />
    Location: $m->matrix_column<br />
    Notes: $m->matrix_value
    </p>
    ";
}
``````

This module provides a default rendering capability as well, so that
you can also do this (below) and get about the same result as above:

``````
echo $page->matrix; 
``````

...or this: 

``````
foreach($page->matrix as $event) {
  echo $event; 
}
``````

## Finding matrix

This fieldtype includes an indexed date field so that you can locate
matrix by date or within a date range. 

`````
// find all pages that have expired matrix
$results = $pages->find("matrix.date<" . time()); 

// find all pages with matrix in January, 2014
$results = $pages->find("matrix.date>=2014-01-01, matrix.date<2014-02-01"); 
`````


