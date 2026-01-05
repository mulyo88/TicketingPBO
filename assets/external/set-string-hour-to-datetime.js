function setTimeNow(idx_hour, idx_minute) {
    const dateNow = new Date();
    dateNow.setHours(idx_hour, idx_minute);
    dateNow.setSeconds(0);
    dateNow.setMilliseconds(0);
    
    return dateNow;
}

function setHour24(idx_time) {
    const [hourMinute, ampm] = idx_time.split(" ");
    let [idx_hour, idx_minute] = hourMinute.split(":");

    idx_hour = parseInt(idx_hour);

    if (ampm.toUpperCase() === "PM" && idx_hour !== 12) {
        idx_hour += 12;
    } else if (ampm.toUpperCase() === "AM" && idx_hour === 12) {
        idx_hour = 0;
    }

    const idx_hourFormated = idx_hour.toString().padStart(2, "0");
    const idx_minuteFormated = idx_minute.padStart(2, "0");

    return [idx_hourFormated, idx_minuteFormated];
}

function setTimeWithDate(time) {
    const time_result = setHour24(time);
    const idx_hourTarget = time_result[0];
    const idx_minuteTarget = time_result[1];

    return setTimeNow(idx_hourTarget, idx_minuteTarget);
}