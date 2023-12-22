function handleSorting(sortColumn) {
    const url = window.location.href.split('?')[0];
    const params = new URLSearchParams(window.location.search);

    let currentSortDirection = params.get('sort_direction');

    if (currentSortDirection === 'asc') {
        params.set('sort_direction', 'desc');
    } else {
        params.set('sort_direction', 'asc');
    }

    params.set('sort_column', sortColumn);

    window.location.href = `${url}?${params.toString()}`;
}