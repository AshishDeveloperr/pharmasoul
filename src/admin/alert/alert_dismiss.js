
function dismissAlert(alertId) {
    const alertElement = document.getElementById(alertId);

    if (alertElement) {
        alertElement.classList.add('dismiss-transition');

        alertElement.style.opacity = 0;

        setTimeout(() => {
            alertElement.style.display = "none";
        }, 300); 
    }
}
setTimeout(function () {
    const alertElement = document.getElementById("toast-alert");

    alertElement.classList.add('dismiss-transition');

    alertElement.style.opacity = 0;

    setTimeout(() => {
        alertElement.style.display = "none";
    }, 300); 
}, 5000);


 // Prevent form for resubmission on page reload
 if ( window.history.replaceState ) {
    window.history.replaceState( null, null, window.location.href );
}