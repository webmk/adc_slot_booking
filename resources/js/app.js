import './bootstrap';

import $ from "jquery";
window.$ = window.jQuery = $;

// Load DataTables jQuery version
import DataTable from "datatables.net-dt";
window.DataTable = DataTable;

// Load Buttons extension
import jszip from "jszip";
window.JSZip = jszip;

import "datatables.net-buttons/js/dataTables.buttons.min.js";
import "datatables.net-buttons/js/buttons.html5.min.js";
import "datatables.net-buttons/js/buttons.print.min.js";

// Load CSS
import "datatables.net-dt/css/jquery.dataTables.css";
import "datatables.net-buttons-dt/css/buttons.dataTables.css";