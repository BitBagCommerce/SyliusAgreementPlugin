/* eslint-disable no-undef */
// eslint-disable-next-line func-names
$(document).on('click', '.agreement-toggle', function () {
    const $this = $(this);
    $this.prev().transition('fade down');
    $this.toggleClass('active');
    return true;
});
