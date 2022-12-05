let admintable = new DataTable('.datatable', {
    responsive: {
        details: {
            type: 'column',
            target: 'tr'
        }
    },
    columnDefs: [ {
        className: 'control',
        orderable: false,
        targets:   0
    } ],
    order: [ 1, 'asc' ]
    
});