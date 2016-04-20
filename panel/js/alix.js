function load_link(select_link) {
 	var link_to_load = "loader.php?"+select;
 	$("#container").load(link_to_load);
 }

var interval = null;

var setSelect = function (selectedValue) {
    select = selectedValue;
    var link_to_load = "loader.php?"+select;
    $("#container").load(link_to_load);
    if (interval) {
        clearInterval(interval);
    }
    interval = runInterval(select);
};

var runInterval = function () {
    return setInterval(function() {
        var link_to_load = "loader.php?"+select;
        $("#container").load(link_to_load);
    }, 60000);
}