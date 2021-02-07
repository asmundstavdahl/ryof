<?= render("header") ?>

<h1>Du er ikke innlogget</h1>

<p>
    Du må logge inn med Feide for å få tilgang til denne siden.
    <br>
    <a href="login?return-to=<?= $returnTo ?>" class="button">Logg inn med Feide</a>
</p>

<?= render("footer") ?>
