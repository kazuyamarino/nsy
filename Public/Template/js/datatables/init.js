// This is just example for making table with some records.
$(document).ready(function() {
	$("#example").DataTable({
		"sPaginationType": "full_numbers",
		"iDisplayLength": 5,
		"aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
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
