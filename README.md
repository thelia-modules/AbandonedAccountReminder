# fr_FR
## AbandonedAccountReminder

Ce module vous permet d’envoyer un courrier électronique après un délai défini afin de relancer les comptes inactifs, c’est-à-dire les clients n’ayant effectué aucune commande après la création de leur compte.

## Installation

### Manuellement

- Copiez ce module directement dans votre répertoire `<thelia_root>/local/modules/` et vérifiez que le nom du module soit **AbandonedAccountReminder**
- Activez-le dans votre back office Thelia

### Composer

Ajoutez cette ligne à votre fichier composer.json au cœur de votre Thélia :

    composer require thelia/abandoned-account-reminder-module:~1.0

## Usage

- Directement depuis votre back office, vous pouvez configurer le délai avant l’envoi des emails aux comptes inactifs.
- Vous devez également mettre en place un cron pour automatiser l’envoi.

Dans un terminal, tapez :

    crontab -e

Ajoutez cette ligne à la fin de votre fichier pour effectuer une vérification toutes les minutes :

    * * * * * /path/to/php /path/to/Theliadirectory/Thelia examine-abandoned-accounts >> /path/to/thelia/log/abandonedaccounts.log 2>&1

Sauvegardez.

---

# en_US
## AbandonedAccountReminder

This module allows you to send an email after a defined delay to remind inactive customers, meaning users who have not placed any order after creating their account.

## Installation

### Manually

- Copy the module into the `<thelia_root>/local/modules/` directory and make sure the module name is **AbandonedAccountReminder**
- Enable it in your Thelia administration panel

### Composer

Add this line to your main Thelia composer.json file:

    composer require thelia/abandoned-account-reminder-module:~1.0

## Usage

- From your back office, you can configure the delay before sending emails to inactive accounts.
- You also need to set up a cron to automate the process.

In a terminal, type:

    crontab -e

Add this line at the end of your crontab file to run a check every minute:

    * * * * * /path/to/php /path/to/Theliadirectory/Thelia examine-abandoned-accounts >> /path/to/thelia/log/abandonedaccounts.log 2>&1

Save it.