// resources/js/session-timeout-popup.js

// Function to display the session timeout popup
function displaySessionTimeoutPopup() {
    // Show your popup here
    alert('Your session is about to expire. Click OK to continue.'); // Example: Replace this with your custom popup code
}

// Function to check for session timeout
function checkSessionTimeout() {
    // Get the session lifetime in minutes from the HTML meta tag
    var sessionLifetime = parseInt(document.querySelector('meta[name="session-lifetime"]').getAttribute('content'));
    
    // Calculate the timeout time in milliseconds
    var timeoutMilliseconds = sessionLifetime * 60 * 1000;
    
    // Set a timer to display the popup before the session expires
    setTimeout(displaySessionTimeoutPopup, timeoutMilliseconds);
}

// Call the checkSessionTimeout function when the page loads
window.onload = checkSessionTimeout;
