# FieldtypeMatrix

##Note: Alpha version!

This module is useful if you wish to save data in a **2D-matrix(grid) table**.
The matrix table is made up of row and column headers of pages whose individual intersections form the 'matrix values'

Pages for building the rows/columns can be selected in one of two ways. **These methods are set up in the Field's 'Details Tab':**

**1. Use a valid ProcessWire selector.**
Here you enter valid ProcessWire selectors for finding pages to build your matrix rows and columns respectively in the relevant input fields found in your matrix field's 'Details Tab'.
If you use this method, it means all instances of the field across different templates and pages will have identical rows and columns.
This also means that if you wanted to create matrices with different rows and columns, you would have to create a new field for each.
**2. Specify a Multiplepage field for row/column parent pages selections**
If used, this method overrides method #1 above.
The method allows you to reuse the same matrix field to create matrix tables made up of different rows and columns on a page by page basis.
To use the method, you first specify the name (e.g. **product_select**) of a valid **Multiplepage field** that will hold the parent pages of **both your rows and columns pages**.
Make sure to first add that Multiplepage field (**product_select**) to the template(s) of the page(s) where you will be building your matrices.
Go and edit your matrix page. No matrix will be shown until you first select **2 pages** in your **page_select** and save the page.
The first page selected will be assumed to be your matrix rows pages parent and the second one your columns pages parent. Any additional selected pages will be ignored. 
If either of the parent pages you selected does not have children, you will get an error.
**If available, the children of the selected row/column pages respectively will be used to build your matrix table.**

**Please note that** if you changed or reordered the pages in your **product_select** page field and saved the page, a new matrix will be built using the children of the newly specified row and column pages and **ALL your old values for the specific page you are editing will be deleted in the database.**

By using this method, if you've properly configured your **product_select**, you can build a large variety of matrix tables on a page by page basis as long as those pages are using a template with the **product_select** page field.

FieldtypeMatrix stores row and column pages data as their respective page->id. Matrix-values store any data (varchar(255)).

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

##Changelog
###Version 0.0.2
Added feature to select matrix row and column parent pages via a named Multiplepage Field select present on the page containing a matrix table.

###Version 0.0.1
Initial Alpha Version.

##Resources
 [Support Forum](https://processwire.com/talk/topic/8581-module-matrix-fieldtype-inputfield/)

##License
GPL(2)
