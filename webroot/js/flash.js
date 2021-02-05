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
    const closeButton = document.createElement('button');
    closeButton.setAttribute('type', 'button');
    closeButton.className = 'close';
    closeButton.setAttribute('aria-label', 'Close');
    closeButton.innerHTML = '<span aria-hidden="true">&times;</span>';
    closeButton.addEventListener('click', function () {
      slideUp(alert);
    });

    alert.innerHTML = message;
    alert.append(closeButton);
    alert.style.display = 'none';

    if (typeof container === 'undefined') {
      container = document.getElementById('flash-messages');
    } else if (typeof container === 'string') {
      container = document.querySelector(container);
    }
    container.appendChild(alert);

    slideDown(alert);
  }
}
