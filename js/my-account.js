const signUpButton = document.getElementById('signUp');
const signInButton = document.getElementById('signIn');
const container = document.getElementById('container');

signUpButton.addEventListener('click', () => {
    container.classList.add("right-panel-active");
});

signInButton.addEventListener('click', () => {
    container.classList.remove("right-panel-active");
});

function validateForm() {
    let valid = true;
    valid = validateUsername() && valid;
    valid = validateEmail() && valid;
    valid = validatePhone() && valid;
    return valid;
}

function validateUsername() {
    const username = document.getElementById("username").value;
    if (/[^a-zA-Z0-9]/.test(username)) {
        alert("Username hanya boleh berisi huruf dan angka!");
        return false;
    }
    return true;
}

function validateEmail() {
    const email = document.getElementById("email").value;
    const mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
    if (!email.match(mailformat)) {
        alert("Email tidak valid!");
        return false;
    }
    return true;
}

function validatePhone() {
    const phone = document.getElementById("telp").value;
    const phoneFormat = /^[0-9]{10,15}$/; 
    if (!phone.match(phoneFormat)) {
        alert("Nomor telepon tidak valid! Harus terdiri dari 10-15 digit angka.");
        return false;
    }
    return true;
}