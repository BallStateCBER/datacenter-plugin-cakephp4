const flashMessage = {
    init: function () {
        const messages = document.getElementById('flash_messages');
        if (messages.style.display === 'none') {
            slideDown(messages);
        }
    },
    insert: function (message, className) {
        const alert = document.createElement('div');
        alert.className = 'alert alert-dismissible';
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

        const button = document.createElement('button');
        button.type = 'button';
        button.className = 'close';
        button.dataset.dismiss = 'alert';
        button.innerHTML = '<span aria-hidden="true">&times;</span>';
        alert.appendChild(button);
        alert.innerHTML += message;

        const container = document.getElementById('flash_messages');
        if (container.style.display !== 'none') {
            alert.style.display = 'none';
            container.appendChild(alert);
            slideDown(alert);
            return;
        }

        container.appendChild(alert);
        slideDown(container);
    }
};
