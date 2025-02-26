const { app, BrowserWindow } = require('electron');
const path = require('path');
const sqlite3 = require('sqlite3').verbose();
const db = new sqlite3.Database('database.db');

db.serialize(() => {
  db.run('CREATE TABLE IF NOT EXISTS users (id INTEGER PRIMARY KEY, name TEXT, email TEXT)');
});

function addUser(name, email) {
  const stmt = db.prepare('INSERT INTO users (name, email) VALUES (?, ?)');
  stmt.run(name, email, function (err) {
    if (err) {
      console.error(err.message);
    } else {
      console.log(`A row has been inserted with rowid ${this.lastID}`);
    }
  });
  stmt.finalize();
}

function getUsers(callback) {
  db.all('SELECT * FROM users', (err, rows) => {
    if (err) {
      console.error(err.message);
      return callback([]);
    }
    callback(rows);
  });
}

// Ejemplo de uso
addUser('John Doe', 'john.doe@example.com');
getUsers((users) => {
  console.log(users);
});


function createWindow() {
  const mainWindow = new BrowserWindow({
    width: 800,
    height: 600,
    webPreferences: {
      nodeIntegration: true,
      contextIsolation: false,
    },
  });

  mainWindow.loadFile('index.html');
}

app.whenReady().then(createWindow);

app.on('window-all-closed', () => {
  if (process.platform !== 'darwin') {
    app.quit();
  }
});

app.on('activate', () => {
  if (BrowserWindow.getAllWindows().length === 0) {
    createWindow();
  }
});
