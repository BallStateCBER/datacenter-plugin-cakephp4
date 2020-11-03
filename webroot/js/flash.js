class FlashMessage {
    /**
     *
     * @param message
     * @param className 'error', 'success', or null for "info"
     * @param container Either an element object or a selector string, or null to use default container
     */
    insert(message, className, container)
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
        if (typeof container === 'undefined') {
            container = document.getElementById('flash-messages');
        } else if (typeof container === 'string') {
            container = document.querySelector(container);
        }

        alert.style.display = 'none';
        container.appendChild(alert);
        slideDown(alert);
    }
}
