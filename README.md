# FieldtypeMatrix

This module is useful if you wish to save data in a **2D-matrix(grid)table**.
The matrix table is made up of row and column headers of pages whose individual intersections form the 'matrix values'

In this **alpha version**, pages for building the rows/columns are only selectable at the Field setup level (Details Tab) (via a normal ProcessWire selector).
Rows and column pages data are stored as their respective page->id. Matrix-values store any data (varchar(255)).
This means that currently, to create different matrices, you would have to create a new field for each.
This may change in the future to allow reusability of the same field across different pages (similar to ProcessWire Page Fields).
This would allow users to select the pages they want to build their matrix's rows and columns right within the page they are editing.

## Example Usage
1. A matrix table of clothes' colours (rows) vs their sizes (columns) and the price (value) for each combination.
2. Car types (rows) vs engine size (columns) and their warranty (value).
3. Gender (rows) vs Age (columns) and their favourite movie for each combination.

The module allows the creation of matrix tables of any sizes (rows x columns).
The rows and columns dynamically grow/shrink depending on the addition of row/column pages that match the selector set up in the Field's 'Details Tab'.
Currently, if such pages are deleted/trashed/hidden/unpublished, their data (and presence) in the matrix are also deleted.

## Install

1. Copy the files for this module to /site/modules/FieldtypeMatrix/ 
2. In admin: Modules > Check for new modules. Install Fieldtype > Matrix.
3. Create a new field of type Matrix, and name it whatever you would 
   like. In our examples we named it simply "matrix". 
4. Add the field to a template and edit a page using that template. 

## API + Output 

A typical output case for this module would work like this:

```php
foreach($page->matrix as $m) {
  echo "
    <p>
    Colour: $m->matrix_row<br />
    Size: $m->matrix_column<br />
    Price: $m->matrix_value
    </p>
    ";
}
```

Of if you want to output a table

```php
//create array to build matrix
$products = array();

foreach($page->matrix as $m) $products[$m->matrix_row][$m->matrix_column] = $m->matrix_value;

$tbody ='';//matrix rows
$thcols = '';//matrix table column headers

$i = 0;//set counter not to output extraneous column label headers
$c = true;//set odd/even rows class
foreach ($products as $row => $cols) {

          //matrix table row headers (first column)
          $rowHeader = $pages->get($row)->title;
      
          $tbody .= "<tr" . (($c = !$c) ? " class='even' " : '') . "><td class='MatrixRowHeader'>" . $rowHeader . "</td>";

          $count = count($cols);//help to stop output of extra/duplicate column headers

          foreach ($cols as $col => $value) {

                    //matrix table column headers
                    $columnHeader = $pages->get($col)->title;

                    //avoid outputting extra duplicate columns
                    if ($i < $count) $thcols .= "<th class='MatrixColumnHeader'>" . $columnHeader . "</th>";
                      
                    //output matrix_values
                    $currency = $value > 0 ? '£' : '';
                    $tbody .= "<td>" . $currency . $value . "</td>";
                    
                    $i++;

          }

          $tbody .= "</tr>";
     
}

//final matrix table for output
 $tableOut =   "<table class='Matrix'>
                <thead>
                  <tr class=''>
                    <th></th>
                    $thcols
                   </tr>
                </thead>
                <tbody>
                  $tbody
                </tbody>
            </table>";


echo $tableOut;

```

The module provides a default rendering capability as well, so that
you can also do this (below) and get  a similar result as the first example above (without the captions).

```php
echo $page->matrix; 
```

Or this

```php
foreach($page->matrix as $m) {
         echo $m; 
}
```

## Finding matrix items

This fieldtype includes indexed row, column and value fields.
This enables you to find matrix items by either row types (e.g. colours) or columns (e.g. sizes) or their values (e.g. price) or a combination of some/all of these. For instance:

```php
//find all pages that have a matrix value of less than 1000
$results = $pages->find("matrix.value<1000"); 
 
```

Other more complex queries are possible, e.g. find all products that are either red or purple in colour, come in x-large size and are priced less than $50.