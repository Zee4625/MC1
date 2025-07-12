const form = document.getElementById('command-form');
const input = document.getElementById('command-input');
const output = document.getElementById('console-output');

form.addEventListener('submit', async e => {
    e.preventDefault();
    const cmd = input.value.trim();
    if (!cmd) return;
    input.value = '';

    output.textContent += `> ${cmd}\n`;

    try {
        const res = await fetch('rcon.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ command: cmd })
        });
        const data = await res.json();
        if (data.error) {
            output.textContent += `[Eroare]: ${data.error}\n`;
        } else {
            output.textContent += `${data.output}\n`;
        }
        output.scrollTop = output.scrollHeight;
    } catch (err) {
        output.textContent += `[Eroare]: Nu s-a putut trimite comanda.\n`;
    }
});
