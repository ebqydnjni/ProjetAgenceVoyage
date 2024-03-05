<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Accueil</title>
    <link rel="stylesheet" href="style.css" />
    <link rel="icon" href="images/Icon.png" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  </head>
  <body>
    <?php session_start(); ?>
    <header class="navbar" id="navbar">
      <img
        class="logo"
        width="100px"
        height="66"
        src="images/logo.png"
        alt="logo"
      />

      <nav>
        <ul class="navigation">
          <li><a href="#Recherche">Rechercher</a></li>
          <li><a href="#Apropos">A Propos</a></li>
          <li><a href="#Contact">Contact</a></li>
          <li class="connection"><a id="openPopup2" href="#">Connexion</a></li>
          <li class="inscription">
            <button id="openPopup" type="submit">Inscription</button>
          </li>
        </ul>
      </nav>

      <!-- Sign up-->

      <div id="popup" class="popup">
        <div class="popup-content">
          <div class="sign_up_form">
            <span class="close" id="closePopup">&times;</span>
            <h2>Inscription</h2>
            <form action="">
              <label for="name">Nom</label>
              <input type="text" placeholder="Nom" />
              <label for="text">Prénom</label>
              <input type="text" placeholder="Prénom" />
              <label for="mail">Mail</label>
              <input type="mail" placeholder="adresse mail" />
              <label for="password">Mot de passe</label>
              <input type="password" placeholder="mot de passe" />
              <button class="signup_button">S'inscrire</button>
              <h5>
                vous avez un compte?
                <span class="sign_in_span" id="signin-link">Se connecter</span>
              </h5>
            </form>
          </div>
        </div>
      </div>

      <!-- Sign In-->
      <div id="popup2" class="popup2">
        <div class="popup2-content">
          <div class="sign_in_form">
            <span class="close2" id="closePopup2">&times;</span>
            <h2>Connexion</h2>
            <form action="">
              <label for="mail">Mail</label>
              <input type="mail" placeholder="adresse mail" />
              <label for="password">Mot de passe</label>
              <input type="password" placeholder="mot de passe" />
              <button class="signin_button">Se connecter</button>
              <h5>
                vous n'avez pas de compte?
                <span class="sign_up_span" id="signup-link">S'inscrire</span>
              </h5>
            </form>
          </div>
        </div>
      </div>
    </header>

    <main>
      <content id="Recherche">
        <div class="mainTitle">
          <h2>Profitez au m<span class="ax">ax!</span></h2>
          <h3>Profitez de nos services de voyages inédits</h3>
        </div>
        <div class="searchBlock">
          <div class="voyageBlock1">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="24"
              height="24"
              viewBox="0 0 24 24"
              fill="none"
            >
              <path
                d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM11 19.93C7.05 19.44 4 16.08 4 12C4 11.38 4.08 10.79 4.21 10.21L9 15V16C9 17.1 9.9 18 11 18V19.93ZM17.9 17.39C17.64 16.58 16.9 16 16 16H15V13C15 12.45 14.55 12 14 12H8V10H10C10.55 10 11 9.55 11 9V7H13C14.1 7 15 6.1 15 5V4.59C17.93 5.78 20 8.65 20 12C20 14.08 19.2 15.97 17.9 17.39Z"
                fill="#FA8B02"
              /></svg
            ><span class="fontResultat">Recherche</span>
          </div>

          <div class="search">
            <form class="searchForm" action="">
              <input type="text" placeholder="Départ" name="depart" id="" />
              <input type="text" placeholder="Arrivée" name="" id="" />
              <input
                type="text"
                value="Date"
                name="Date"
                onfocus="this.type='date'"
                onblur="this.type"
                ="date"
                id=""
              />
              <input
                type="text"
                name=""
                placeholder="Heure"
                onfocus="this.type='time'"
                onblur="this.type='time'"
                ="date"
                id=""
              />
              <button class="searchButton">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="24"
                  height="24"
                  viewBox="0 0 33 32"
                  fill="none"
                >
                  <path
                    d="M28.5003 28L22.519 22.008M25.8337 14C25.8337 17.0058 24.6396 19.8885 22.5142 22.0139C20.3888 24.1393 17.5061 25.3334 14.5003 25.3334C11.4945 25.3334 8.61186 24.1393 6.48645 22.0139C4.36104 19.8885 3.16699 17.0058 3.16699 14C3.16699 10.9942 4.36104 8.11156 6.48645 5.98614C8.61186 3.86073 11.4945 2.66669 14.5003 2.66669C17.5061 2.66669 20.3888 3.86073 22.5142 5.98614C24.6396 8.11156 25.8337 10.9942 25.8337 14V14Z"
                    stroke="white"
                    stroke-width="2"
                    stroke-linecap="round"
                  />
                </svg>
              </button>
            </form>
          </div>
        </div>
      </content>
      <div class="Apropos">
        <content id="Apropos" class="Apropos_content_1">
          <img
            class="Apropos_Image_1"
            src="images/apropos1.png"
            width="202px"
            height="279px"
            alt=""
          />
          <span class="Apropos_text">
            <h3 class="Apropos_title">
              LA COMPAGNIE <span class="percentage">100%</span> ÉTUDIANTE
            </h3>

            <h4>
              Nous sommes un groupe d'étudiants passionés par l'informatique et
              les avancées technologique. Dans cette optique, nous avons décidés
              de créer l'agence de voyage <i>Negn'Go</i>, "Allons-y". Un mélange
              de wolof et d'anglais pour mettre en exergue la liberté et pousser
              nos perspectives à leurs paroxisme. Participez à cette expérience
              formidable et réservez vos billets à des prix somptueux!
            </h4>
          </span>
        </content>
        <content id="Contact" class="Apropos_content_2">
          <div class="contact">
            <h3 class="contact_title">Comment nous joindre?</h3>
            <h4 class="contact_text">
              Vous avez des requêtes spéciales, des feedbacks où des
              suggestions? contactez nous par mail ou par téléphone. Nous somme
              à votre disposition?
            </h4>
            <button>Contactez nous</button>
          </div>
        </content>
        <div class="Apropos_image_2">
          <img src="images/Apropos2.png" width="400px" height="400px" alt="" />
        </div>
      </div>
    </main>

    <script src="script.js"></script>
    
  </body>
</html>

<form action="auth.php" method="post">
  <label for="login_mail">Mail</label>
  <input type="email" name="login_mail" placeholder="adresse mail" required />
  <label for="login_password">Mot de passe</label>
  <input type="password" name="login_password" placeholder="mot de passe" required />
  <button type="submit" name="action" value="login" class="signin_button">Se connecter</button>
</form>

<form action="auth.php" method="post">
  <!-- Vos champs d'inscription -->
  <button type="submit" name="action" value="register" class="signup_button">S'inscrire</button>
</form>
