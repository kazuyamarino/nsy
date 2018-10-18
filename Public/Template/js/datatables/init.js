// This is just example for making table with some records.
$(document).ready(function() {
	$("#example").DataTable({
		"sPaginationType": "full_numbers",
		"iDisplayLength": 5,
		"aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
		"ajax": {
			"url": base_url_js + "Public/Data/data.json" // base_url_js can be managed in main.js located in the Template/js/ folder
		},
		"oLanguage": {
			"oPaginate": {
				"sFirst": "<i class='fas fa-angle-double-left'></i>",
				"sLast": "<i class='fas fa-angle-double-right'></i>",
				"sPrevious": "<i class='fas fa-angle-left'></i>",
				"sNext": "<i class='fas fa-angle-right'></i>"
			}
		}
	});
});
