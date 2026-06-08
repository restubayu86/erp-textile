/*! Bulma styling wrapper for ColumnControl
 * © SpryMedia Ltd - datatables.net/license
 */

import jQuery from 'jquery';
import DataTable from 'datatables.net-bm';
import ColumnControl from 'datatables.net-columncontrol';

// Allow reassignment of the $ variable
let $ = jQuery;


DataTable.ColumnControl.content.dropdown.classes.container = [
	'dtcc-dropdown',
	'dropdown',
	'dropdown-menu'
];

DataTable.ColumnControl.content.dropdown.classes.liner = [
	'dtcc-dropdown-liner',
	'dropdown-content'
];


export default DataTable;
