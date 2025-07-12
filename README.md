# Minecraft Staff Panel - Consolă RCON PHP

Simplu panel pentru staff, cu autentificare și consolă live pentru server Minecraft folosind RCON.

---

## Setup

1. Clonează repo-ul:
```
git clone https://github.com/utilizator/minecraft-staff-panel.git
cd minecraft-staff-panel
```

2. Creează `config.php` din `config.sample.php` și editează cu datele serverului tău RCON.

3. Folosește un server local PHP (ex: XAMPP, Laragon) și deschide `login.php` în browser.

4. Login default:  
   - user: `admin`  
   - parola: `parola123`

---

## Cum funcționează?

- `login.php` gestionează autentificarea  
- `index.php` afișează consola și formularul de comenzi  
- `rcon.php` primește comenzi prin AJAX și răspunde cu output-ul serverului  
- `includes/Rcon.php` conține clasa RCON  
- `includes/Auth.php` gestionează sesiunea și login-ul  

---

## Securitate

- Nu uita să modifici parola default din `includes/Auth.php`
- Nu urca `config.php` pe GitHub!

