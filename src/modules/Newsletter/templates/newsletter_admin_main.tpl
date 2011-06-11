
{include file='newsletter_admin_header.tpl'}

<div class="z-admincontainer">
    <div class="z-adminpageicon">{img modname='Newsletter' src='admin-icon.png' alt='' height='48'}</div>

    <h3>{gt text="Welcome to Newsletter!"}</h3>

    <p>
        <a href="http://code.zikula.org/newsletter" target="_blank"><b>{gt text="Visit our project-page"}</b></a><br />
        {gt text="Please report errors or requests features for future versions in the Tracker!"}
    </p>

    <div class="z-warningmsg">
        <strong>{gt text='Attention'}:</strong><br />
        <a href="index.php?module=Mailer&type=admin">{gt text="Zikula Version 1.1.2 and up: To ensure html-mails are sent correctly, you have to look that in the Core-Mailer Module (Settings->Mailer) the butten -send mails in html-format- is checked!"}</a>
    </div>
</div>