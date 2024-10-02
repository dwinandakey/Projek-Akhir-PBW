function showTime() {
    const date = new Date();
    const hours = date.getHours();
    const minutes = date.getMinutes();
    const seconds = date.getSeconds();
    const jam = formatTime(hours) + ":" + formatTime(minutes) + ":" + formatTime(seconds);
    const tanggal = formatDate(date);
    document.getElementById("tanggal").textContent = tanggal;
    document.getElementById("jam").textContent = jam;
    setTimeout(showTime, 1000);
}

function formatTime(time) {
    return time < 10 ? "0" + time : time;
}

function formatDate(date) {
    const day = date.getDate();
    const month = date.getMonth() + 1;
    const year = date.getFullYear();
    return formatTime(day) + "/" + formatTime(month) + "/" + year;
}