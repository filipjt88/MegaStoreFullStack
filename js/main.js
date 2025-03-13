fetch("http://localhost/megaStoreFullStack/api/register.php", {
    method: "POST",
    headers: {
        "Content-Type": "application/json"
    },
    body: JSON.stringify({
        firstname: "Filip",
        lastname: "Jotić",
        email: "filip@example.com",
        password: "123456"
    })
})
    .then(response => {
        if (!response.ok) {
            throw new Error('Server error: ' + response.statusText);
        }
        return response.json();
    })
    .then(data => {
        console.log("Server response:", data);
    })
    .catch(error => {
        console.error("Greška:", error.message);
        console.error("Detalji greške:", error);
    });
