console.log('console log from product-index.js');

const deleteButtonElements = document.querySelectorAll('.js-delete-button');

deleteButtonElements.forEach(deleteButtonElement => {
    deleteButtonElement.addEventListener('click', function(event) {
        if (!window.confirm('Are you sure you want to delete this item?')) {
            event.preventDefault();
        }
    });
});