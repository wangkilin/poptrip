var __refreshSessionInteval = 1000 * 60 * 5; // refresh session every 5 minutes

function __refreshSession() {
    var $=jQuery;
    $.get('refreshSession.php', {}, function() {
    });
    setTimeout('__refreshSession()', __refreshSessionInteval);
}
setTimeout('__refreshSession()', __refreshSessionInteval);