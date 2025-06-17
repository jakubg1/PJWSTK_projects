FS_PREFIX = "/git/PJWSTK_projects/wpr_project";

// Redirects to provided URL.
function redirect(url) {
    window.location.replace(FS_PREFIX + url);
}

// Returns the GET parameter from the URL.
function getURLParam(name) {
    let match = document.URL.match(name + "=(\\d+)");
    return match ? match[1] : null;
}

// Sets the status of page by accessing <div id="status">.
function status(message, isSuccess = false) {
    let status = $("#status");
    status.show();
    status.removeClass("success failure");
    status.addClass(isSuccess ? "success" : "failure");
    status.text(message);
}

// Returns the error message for the provided response (its code).
function xhrError(response, messages) {
    if (messages[response.status]) {
        return messages[response.status];
    } else if (response.status == 400) {
        return "Formularz nie został wypełniony poprawnie! Sprawdź go i spróbuj ponownie.";
    } else if (response.status == 500) {
        return "Po naszej stronie wystąpił nieoczekiwany błąd. Spróbuj ponownie później.";
    }
    return "Wystąpił BŁĄD NIESPODZIANKA! To się nigdy nie powinno zdarzyć! (" + response.status + ")";
}

// Sends a POST request to the provided endpoint.
function ajax(url, data, onSuccess, onError) {
    $.ajax({
        type: "POST",
        url: FS_PREFIX + url,
        data: data,
        success: onSuccess,
        error: onError
    });
}

// Binds a form and sends a POST request to the provided endpoint.
function registerForm(id, onValidate, onSuccess, onError) {
    // https://stackoverflow.com/questions/61259511/php-submit-form-without-exit-of-page
    let form = $("#" + id);
    form.submit(function(e) {
        e.preventDefault();
        e.stopPropagation();
        let formData = new FormData(form[0]);
        let result = onValidate(formData);
        if (result) {
            $.ajax({
                type: form.attr("method"),
                url: FS_PREFIX + form.attr("action"),
                data: formData,
                contentType: false,
                processData: false,
                success: onSuccess,
                error: function(response) {
                    onError(response);
                    // Print error info to console if something has been returned
                    // (so that when an error shows up, I can just place var_dumps for quick debugging)
                    if (response.responseText.length > 0) {
                        console.log("Error info:");
                        console.log(response.responseText);
                    }
                }
            });
        }
    });
}