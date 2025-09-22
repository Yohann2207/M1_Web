from flask import Flask, request, render_template, redirect, url_for, session
import mysql.connector
from passlib.context import CryptContext

app = Flask(__name__)

# Clé secrète pour stocker les sessions dans un cookie
app.secret_key = "3401a8d160caed46992d4eecf3f806a39259e4dae888ce2df42758d7c0e8308f" 

# Configuration de la base MySQL
db_config = {
    "host": "localhost",
    "user": "root",
    "password": "",        
    "database": "login_m1"  
}

# Gestion du hash (bcrypt)
pwd_crypt = CryptContext(schemes=["bcrypt"], deprecated="auto")

# Ouvre une connexion MySQL et la renvoie
def get_db_connection():
    return mysql.connector.connect(**db_config)


# Routes

@app.route("/")
def index():
    return render_template("index.html")


@app.route("/signup", methods=["GET", "POST"])
def signup():
    if request.method == "POST":
        login = request.form["login"]
        compte_id = request.form["compte_id"]
        email = request.form["email"]
        password = request.form["password"]

        hashed_password = pwd_crypt.hash(password)  # bcrypt

        try:
            conn = get_db_connection()
            cursor = conn.cursor()
            cursor.execute(
                """
                INSERT INTO user (user_login, user_password, user_compte_id, user_mail)
                VALUES (%s, %s, %s, %s)
                """,
                (login, hashed_password, compte_id, email),
            )
            conn.commit()
            cursor.close()
            conn.close()
            return redirect(url_for("index"))
        except Exception as e:
            return f"Erreur serveur : {str(e)}", 500
    return render_template("signup.html")


@app.route("/login", methods=["POST"])
def login():
    login = request.form["login"]
    password = request.form["password"]

    try:
        conn = get_db_connection()
        cursor = conn.cursor(dictionary=True)
        cursor.execute("SELECT * FROM user WHERE user_login = %s", (login,))
        user = cursor.fetchone()
        cursor.close()
        conn.close()

        if user and pwd_crypt.verify(password, user["user_password"]):
            # Stocker les infos dans la session
            session["user_id"] = user["user_id"]
            session["user_login"] = user["user_login"]
            return redirect(url_for("menu"))
        else:
            return "Login ou mot de passe incorrect.", 401
    except Exception as e:
        return f"Erreur serveur : {str(e)}", 500


@app.route("/menu")
def menu():
    # Vérifie si l'utilisateur est connecté
    if "user_login" not in session:
        return redirect(url_for("index"))
    return render_template("menu.html", user_login=session["user_login"])


@app.route("/logout")
def logout():
    session.clear()
    return redirect(url_for("index"))


if __name__ == "__main__":
    app.run(debug=True)
