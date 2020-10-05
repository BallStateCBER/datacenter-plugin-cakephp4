class flashMessage {
    insert(message, className)
    {
        const alert = document.createElement('div');
        alert.className = 'alert alert-dismissible fade show';
        alert.role = 'alert';
        switch (className) {
            case 'error':
                alert.className += ' alert-danger';
                break;
            case 'success':
                alert.className += ' alert-success';
                break;
            default:
                alert.className += ' alert-info';
        }
        alert.innerHTML = message +
            '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
            '<span aria-hidden="true">&times;</span>' +
            '</button>';
        const container = document.getElementById('flash-messages');

        alert.style.display = 'none';
        container.appendChild(alert);
        slideDown(alert);
    }
}
