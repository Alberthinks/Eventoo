function deleteConfirm(id) {
    if (confirm("L'eliminazione dell'account è definitiva. Vuoi continuare?")) {
        location.href = "rimuovi?id=" + id;
    }
}

function changePassword(id) {
    document.getElementById("psw-container").style.display = "block";
    document.getElementById("nome-utente").innerText = id;
    document.getElementById("id_post").value = id;
}
