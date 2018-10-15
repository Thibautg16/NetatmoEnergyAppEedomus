# NetatmoEnergieAppEedomus
Gestion d'un [thermostat Netatmo](https://www.netatmo.com/fr-FR/product/energy/) via la box eedomus

Script créé par [@Thibautg16](https://twitter.com/Thibautg16/)

Dépot GIT : [https://github.com/Thibautg16/NetatmoEnergyAppEedomus/](https://github.com/Thibautg16/NetatmoEnergyAppEedomus/)

Changelog : [https://github.com/Thibautg16/NetatmoEnergyAppEedomus/blob/master/CHANGELOG.md](https://github.com/Thibautg16/NetatmoEnergyAppEedomus/blob/master/CHANGELOG.md)

## Prérequis 
Vous devez au préalable disposer d'un thermostat Netatmo installé et configuré sur votre compte Netatmo.

## Installation
### Ajout du périphérique 
Cliquez sur "Configuration" / "Ajouter ou supprimer un prériphérique" / "Store eedomus" / "Netatmo Energie App" / "Créer"

![creer_peripherique](https://user-images.githubusercontent.com/4451322/42838486-4067865c-8a01-11e8-8b4d-327c5e2fca82.png)

### Configuration du périphérique :
![netatmo_config_peripherique](https://user-images.githubusercontent.com/4451322/42838538-6ada67a6-8a01-11e8-90e2-c992905c7333.png)

#### Code d'autorisation Oauth :
Cliquez sur **Cliquez ici pour obtenir votre code code d'autorisation**. 

Vous êtes alors redirigés vers le portail Netatmo:

![netatmo_oauth](https://user-images.githubusercontent.com/4451322/33577887-e5852324-d944-11e7-8796-f00ad385255f.png)


Vous êtes ensuite redirigés vers le site **Eedomus**:

![netatmo_oauth_eedomus](https://user-images.githubusercontent.com/4451322/37258539-dd624998-2579-11e8-888a-5d8222907f21.png)

Copiez le *code d'autorisation Oauth Netatmo* obtenu sur la page eedomus qui est restée ouverte dans votre navigateur Internet ainsi que *le nom et l'id de votre maison* et *le nom et l'id de la pièce ou se trouvent le thermostat*. 

### Voici les différents champs à renseigner :

**Configuration :**

* [Optionnel] - Nom personnalisé : personnalisation du nom de votre périphérique
* [Obligatoire] - Pièce : vous devez définir dans quelle pièce se trouve votre thermostat
* [Obligatoire] - Code d'autorisation Oauth Netatmo
* [Obligatoire] - Nom de votre maison (nom dans l'application Netatmo)
* [Obligatoire] - Id de votre maison
* [Obligatoire] - Nom de votre pièce où se trouve le thermostat (nom dans l'application Netatmo)
* [Obligatoire] - Id de votre pièce


**Informations Chauffage :**

* [Optionnel] - Mode manuel : personnaliser la durée de vos consignes manuelles
* [Optionnel] - Consigne chauffage : choisissez si vous souhaitez créer ce module
* [Optionnel] - Mode chauffage : choisissez si vous souhaitez créer ce module
* [Optionnel] - Etat chauffage : choisissez si vous souhaitez créer ce module
* [Optionnel] - Signal radio du relais : choisissez si vous souhaitez créer ce module
* [Optionnel] - Signal wifi du relais : choisissez si vous souhaitez créer ce module

**Informations Thermostat :**

* [Optionnel] - Signal radio du thermostat : choisissez si vous souhaitez créer ce module
* [Optionnel] - Accessibilité thermostat : choisissez si vous souhaitez créer ce module
* [Optionnel] - Batterie thermostat : choisissez si vous souhaitez créer ce module



**Plusieurs modules sont créés sur votre box eedomus, ainsi que le script Netatmo:**

![netatmo_widget](https://user-images.githubusercontent.com/4451322/39837981-44f9a7a4-53d8-11e8-83a6-a70c0454d53b.png)


### Plugins disponibles :
* NetatmoEnergyRoomAppEedomus
* NetatmoEnergyVanneAppEedomus


## Mise à jour du script
Si vous possédez déjà le périphérique et que vous souhaitez simplement profiter de la mise à jour du script.

Dans un premier temps vous rendre dans la configuration de votre périphérique et cliquer sur "Vérifier les mises à jour de netatmo\_thermostat\_oauth.php":

![eedomus_script_verif](https://user-images.githubusercontent.com/4451322/42839171-1d8dac9a-8a03-11e8-814c-1218fe9e74b8.png)


Cliquez alors sur "Mettre à jour netatmo_thermostat_oauth.php avec la dernière version disponible.":

![eedomus_script_maj](https://user-images.githubusercontent.com/4451322/34960084-af7cbb3c-fa39-11e7-8ff1-b31f13cb525d.png)


[![release](https://img.shields.io/github/release/Thibautg16/NetatmoEnergyAppEedomus.svg?style=for-the-badge)](https://github.com/Thibautg16/NetatmoEnergyAppEedomus/releases)
[![license](https://img.shields.io/github/license/Thibautg16/NetatmoEnergyAppEedomus.svg?style=for-the-badge)](https://github.com/Thibautg16/NetatmoEnergyAppEedomus/blob/master/LICENSE)
[![Status: Beta](https://img.shields.io/badge/Status-Prod-green.svg?style=for-the-badge)](https://github.com/Thibautg16/NetatmoEnergyAppEedomus)
[![@Thibautg16](https://img.shields.io/badge/twitter-@Thibautg16-blue.svg?style=for-the-badge)](https://twitter.com/Thibautg16)