function limit_string(data, limit = 10) {
    return data.slice(0, limit) + (data.length > limit ? "..." : "");
}