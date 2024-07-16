/* ---------------------------------
 * Fancybox
 * --------------------------------- */

Fancybox.defaults = {
    ...Fancybox.defaults,

    // ...
};

Fancybox.bind('[data-fancybox]', {});

/* ---------------------------------
 * JQuery Confirm
 * --------------------------------- */

jconfirm.defaults = {
    ...jconfirm.defaults,
    animateFromElement: false,

    // ...
};

/* ---------------------------------
 * Redot Livewire Datatables
 * --------------------------------- */

$(document).on('submit', '.livewire-datatable form', (event) => {
    event.preventDefault();

    $.confirm({
        type: 'red',
        title: __('Are you sure?'),
        content: __('This action is irreversible.'),
        buttons: {
            confirm: {
                text: __('Yes'),
                btnClass: 'btn-primary',
                action: () => {
                    event.target.submit();
                },
            },
            cancel: {
                text: __('No'),
                btnClass: 'btn-default',
            },
        },
    });
});
