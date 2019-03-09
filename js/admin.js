(function () {
    'use strict';

    /**
    * Admin - Student - Status filter
    */
    var showFilter = document.getElementById("showFilter");
    if (showFilter != null) {
        showFilter.addEventListener("change", function () {
            var value = showFilter.options[showFilter.selectedIndex].value;
            // Redirect with parameter
            var url = window.location.href;
            url = url.replace("search=", "");   // Remove search param
            var index = url.indexOf("&filter");
            if (index > 0) {
                window.location.href = url.substr(0, index) + "&filter=" + value;
            } else {
                window.location.href = url + ("&filter=") + value;
            }
        });
    }

    /**
    * Admin - Planning - Time spinner
    */
    var timeSpinner = document.getElementById("timeSpinner");
    if (timeSpinner != null) {
        timeSpinner.addEventListener("change", function () {
            var time = timeSpinner.options[timeSpinner.selectedIndex].value;
            for (var i = 0; i < 28 ; i++) {
                var elem = document.getElementById("h" + i);
                if (elem.classList.contains("selected")) {
                    var hour = elem.value;
                    break;
                }
            }

            // Redirect with parameter
            var label = document.getElementById("headerLabel").innerHTML;
            // Convert label in spa format to eng
            var dateSpa = label.split('/')[0].trim();
            var spa = dateSpa.split('-');
            var date = spa[2] + "-" + spa[1] + "-" + spa[0];

            var url = window.location.href;
            var index = url.indexOf("admin_calendar_1");
            if (index > 0) {
                window.location.href = url.substr(0, index) + "admin_calendar_2&selDate=" + date + "&hourLabel=" + hour + "&spinTime=" + time;
            } else {
                window.location.href = url + "admin_calendar_2&selDate=" + date + "&hourLabel=" + hour + "&spinTime=" + time;
            }
        });
    }

    /**
    * Admin - Planning - Students spinner
    */
    var studentSpinner = document.getElementById("studentSpinner");
    if (studentSpinner != null) {
        studentSpinner.addEventListener("change", function () {
            var student = studentSpinner.options[studentSpinner.selectedIndex].value;
            for (var i = 0; i < 28 ; i++) {
                var elem = document.getElementById("h" + i);
                if (elem.classList.contains("selected")) {
                    var hour = elem.value;
                    break;
                }
            }

            // Redirect with parameter
            var label = document.getElementById("headerLabel").innerHTML;
            // Convert label from spa [d-m-y] to eng [y-m-d]
            var dateSpa = label.split('/')[0].trim();
            var spa = dateSpa.split('-');
            var date = spa[2] + "-" + spa[1] + "-" + spa[0];

            var url = window.location.href;
            var index = url.indexOf("admin_calendar_1");
            if (index > 0) {
                window.location.href = url.substr(0, index) + "admin_calendar_2&selDate=" + date + "&hourLabel=" + hour + "&spinStudent=" + student;
            } else {
                window.location.href = url + "admin_calendar_2&selDate=" + date + "&hourLabel=" + hour + "&spinStudent=" + student;
            }
        });
    }

    /**
    * Admin - Calendar 2 - TextArea
    */
    var textArea = document.getElementById("text-area");
    if (textArea != null) {
        textArea.addEventListener("input", function() {
            if (!textArea.classList.contains("error-message")) {
                textArea.className += " error-message";
                console.log("add");
            }
        });
    }

})();
