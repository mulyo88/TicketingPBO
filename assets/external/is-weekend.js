function isWeekEnd (date) {
    if (date.getDay() == 6) {
        return "Saturday";
    }

    if (date.getDay() == 0) {
        return "Sunday";
    }
}