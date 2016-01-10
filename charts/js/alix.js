function load_link(select_link) {
 	var link_to_load = "chart_loader.php?chart="+select_link;
 	$("#chart").load(link_to_load);
 }

var interval = null;

var setSelect = function (selectedValue) {
    select = selectedValue;
    var link_to_load = "chart_loader.php?chart="+select;
    $("#chart").load(link_to_load);
    if (interval) {
        clearInterval(interval);
    }
    interval = runInterval(select);
};

var runInterval = function () {
    return setInterval(function() {
        var link_to_load = "chart_loader.php?chart="+select;
        $("#chart").load(link_to_load);
    }, 60000);
}