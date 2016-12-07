checkbox = document.getElementById('client_createNewGroup');
if (checkbox != null) {
    if (checkbox.checked == true) {
        document.getElementById('client_mainFeedGroup').disabled = true;
    } else {
        document.getElementById('client_mainFeedGroup').disabled = false;
    }

    checkbox.addEventListener('click', function(event) {
        if (checkbox.checked == true) {
            document.getElementById('client_mainFeedGroup').disabled = true;
        } else {
            document.getElementById('client_mainFeedGroup').disabled = false;
        }
    });
}
