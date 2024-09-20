console.log("JavaScript is running!")

function handleDeletePopup() {
    document.getElementById('confirmation-modal').style.display = 'block';

    document.getElementById('close-modal').addEventListener('click', function () {
        document.getElementById('confirmation-modal').style.display = 'none';
    });

    document.getElementById('cancel-delete').addEventListener('click', function () {
        document.getElementById('confirmation-modal').style.display = 'none';
    });

    document.getElementById('confirm-delete').addEventListener('click', function () {
        document.getElementById('delete-form').submit();
    });

    window.onclick = function (event) {
        const modal = document.getElementById('confirmation-modal');
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    };
}
