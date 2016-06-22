$( document ).ready(function() {
	$("#exportxls").click(function(){
		$(".table").table2excel({
		    // exclude CSS class
		    exclude: ".noExl",
		    name: "Worksheet",
		    filename: "Exported Records" //do not include extension
		  });
		console.log("This is visitation");
	});
});
