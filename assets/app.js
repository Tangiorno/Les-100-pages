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

window.queryAJAX = () => {
    const emailInput = document.getElementById('email');
    const emailVerifText = document.getElementById('emailVerifText')
    const codeUniqueInput = document.getElementById('codeUnique');
    const codeUniqueVerifText = document.getElementById('codeUniqueVerifText')
    let timeout = null;

    emailInput.addEventListener('input', function () {
        clearTimeout(timeout);
        timeout = setTimeout(function () {
            checkEmail(emailInput.value);
        }, 50);
    });
    codeUniqueInput.addEventListener('input', function () {
        clearTimeout(timeout);
        timeout = setTimeout(function () {
            checkCodeUnique(codeUniqueInput.value);
        }, 500);
    });

    function checkEmail(email) {
        fetch(Routing.generate("check_field_not_taken", {key: "email", value: email}), {method: 'HEAD'})
            .then(response => response.status)
            .then(code => {
                if (code === 204) {
                    emailVerifText.innerHTML = 'Cet e-mail est déjà pris !'
                } else if (code === 404) {
                    emailVerifText.innerHTML = "E-mail disponible !"
                } else if (code === 422) {
                    emailVerifText.innerHTML = "E-mail ne respecte pas le format !"
                }
            })
            .catch(error => console.error('Error:', error));
    }

    function checkCodeUnique(code) {
        fetch(Routing.generate("check_field_not_taken", {key: "codeUnique", value: code}), {method: 'HEAD'})
            .then(response => response.status)
            .then(code => {
                if (code === 204) {
                    codeUniqueVerifText.innerHTML = 'Ce code unique est déjà pris !'
                } else if (code === 404) {
                    codeUniqueVerifText.innerHTML = "Code unique disponible !"
                } else if (code === 422) {
                    codeUniqueVerifText.innerHTML = "Code unique ne respecte pas le format (alphanumérique) !"
                }
            })
            .catch(error => console.error('Error:', error));
    }
}

function flashes(){
    const flashMessages = document.querySelectorAll("#flash-message");
    flashMessages.forEach(function(flashMessage) {
        setTimeout(function() {
            flashMessage.classList.remove('show');
            flashMessage.classList.add('fade');}, 4000);
    });
}
