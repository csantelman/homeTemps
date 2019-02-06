var myVar;

function timedRefreshDateTime() {
  refreshDateTime();
  myVar = setInterval(refreshDateTime, 1000);
}

function refreshDateTime() {
  var dateStr = new Date().toDateString();
  var timeStr = new Date().toLocaleTimeString();
  var newDateTimeStr = String(dateStr + " - " + timeStr);
  document.getElementById('dateTime').innerHTML = newDateTimeStr;
}
