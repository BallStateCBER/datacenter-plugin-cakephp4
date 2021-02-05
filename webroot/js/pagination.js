class Pagination {
  constructor()
  {
    const selects = document.querySelectorAll('.paginator select');
    selects.forEach(function (select) {
      select.addEventListener('change', function (event) {
        const selectedOption = event.target.querySelector('option:checked');
        window.location = selectedOption.dataset.url;
      });
    });
  }
}
