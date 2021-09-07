(function() {
    var nav = document.getElementById('nav'),
        anchor = nav.getElementsByTagName('a'),
        list = nav.getElementsByTagName('li'),
        current = window.location.href;

        for (var i = 0; i < list.length; i++) {
            if(list[i].id == current) {
              list[i].className = "nav-item active";
            }
            console.log("Current Page: " + current);
        }
})();