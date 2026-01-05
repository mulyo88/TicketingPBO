function formatDate(date, options, separator) {
    function format(option) {
        let formatter = new Intl.DateTimeFormat('en', option);
        if (formatter.format(date).length <= 2) {
            formatter = String(formatter.format(date)).padStart(2, '0')
        } else {
            formatter = formatter.format(date)
        }

        return formatter;
    }
    return options.map(format).join(separator);
} 