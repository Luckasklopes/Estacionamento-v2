function showPopup(valor) {
    document.getElementById("valor").textContent = valor.toFixed(2).replace('.', ',');
    document.getElementById("modal").style.display = "block";
}

function closePopup() {
    document.getElementById("modal").style.display = "none";
}

function confirmExit() {
    window.location.href = "../index.php";
}
