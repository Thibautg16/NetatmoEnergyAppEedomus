# NetatmoEnergieAppEedomus
Gestion d'un [thermostat Netatmo](https://www.netatmo.com/fr-FR/product/energy/) via la box eedomus

Script cr�� par [@Thibautg16](https://twitter.com/Thibautg16/)

D�pot GIT : [https://github.com/Thibautg16/NetatmoEnergyAppEedomus/](https://github.com/Thibautg16/NetatmoEnergyAppEedomus/)

Changelog : [https://github.com/Thibautg16/NetatmoEnergyAppEedomus/blob/master/CHANGELOG.md](https://github.com/Thibautg16/NetatmoEnergyAppEedomus/blob/master/CHANGELOG.md)

## Pr�requis 
Vous devez au pr�alable disposer d'un thermostat Netatmo install� et configur� sur votre compte Netatmo.

## Installation
### Ajout du p�riph�rique 
Cliquez sur "Configuration" / "Ajouter ou supprimer un pr�riph�rique" / "Store eedomus" / "Netatmo Energie App" / "Cr�er"

![creer_peripherique](https://user-images.githubusercontent.com/4451322/42838486-4067865c-8a01-11e8-8b4d-327c5e2fca82.png)

### Configuration du p�riph�rique :
![netatmo_config_peripherique](https://user-images.githubusercontent.com/4451322/42838538-6ada67a6-8a01-11e8-90e2-c992905c7333.png)

#### Code d'autorisation Oauth :
Cliquez sur **Cliquez ici pour obtenir votre code code d'autorisation**. 

Vous �tes alors redirig�s vers le portail Netatmo:

![netatmo_oauth](https://user-images.githubusercontent.com/4451322/33577887-e5852324-d944-11e7-8796-f00ad385255f.png)


Vous �tes ensuite redirig�s vers le site **Eedomus**:

![netatmo_oauth_eedomus](https://user-images.githubusercontent.com/4451322/37258539-dd624998-2579-11e8-888a-5d8222907f21.png)

Copiez le *code d'autorisation Oauth Netatmo* obtenu sur la page eedomus qui est rest�e ouverte dans votre navigateur Internet ainsi que *le nom et l'id de votre maison* et *le nom et l'id de la pi�ce ou se trouvent le thermostat*. 

### Voici les diff�rents champs � renseigner :

**Configuration :**

* [Optionnel] - Nom personnalis� : personnalisation du nom de votre p�riph�rique
* [Obligatoire] - Pi�ce : vous devez d�finir dans quelle pi�ce se trouve votre thermostat
* [Obligatoire] - Code d'autorisation Oauth Netatmo
* [Obligatoire] - Nom de votre maison (nom dans l'application Netatmo)
* [Obligatoire] - Id de votre maison
* [Obligatoire] - Nom de votre pi�ce o� se trouve le thermostat (nom dans l'application Netatmo)
* [Obligatoire] - Id de votre pi�ce


**Informations Chauffage :**

* [Optionnel] - Mode manuel : personnaliser la dur�e de vos consignes manuelles
* [Optionnel] - Consigne chauffage : choisissez si vous souhaitez cr�er ce module
* [Optionnel] - Mode chauffage : choisissez si vous souhaitez cr�er ce module
* [Optionnel] - Etat chauffage : choisissez si vous souhaitez cr�er ce module
* [Optionnel] - Signal radio du relais : choisissez si vous souhaitez cr�er ce module
* [Optionnel] - Signal wifi du relais : choisissez si vous souhaitez cr�er ce module

**Informations Thermostat :**

* [Optionnel] - Signal radio du thermostat : choisissez si vous souhaitez cr�er ce module
* [Optionnel] - Accessibilit� thermostat : choisissez si vous souhaitez cr�er ce module
* [Optionnel] - Batterie thermostat : choisissez si vous souhaitez cr�er ce module



**Plusieurs modules sont cr��s sur votre box eedomus, ainsi que le script Netatmo:**

![netatmo_widget](https://user-images.githubusercontent.com/4451322/39837981-44f9a7a4-53d8-11e8-83a6-a70c0454d53b.png)


### Plugins disponibles :
* NetatmoEnergyRoomAppEedomus
* NetatmoEnergyVanneAppEedomus


## Mise � jour du script
Si vous poss�dez d�j� le p�riph�rique et que vous souhaitez simplement profiter de la mise � jour du script.

Dans un premier temps vous rendre dans la configuration de votre p�riph�rique et cliquer sur "V�rifier les mises � jour de netatmo\_thermostat\_oauth.php":

![eedomus_script_verif](https://user-images.githubusercontent.com/4451322/42839171-1d8dac9a-8a03-11e8-814c-1218fe9e74b8.png)


Cliquez alors sur "Mettre � jour netatmo_thermostat_oauth.php avec la derni�re version disponible.":

![eedomus_script_maj](https://user-images.githubusercontent.com/4451322/34960084-af7cbb3c-fa39-11e7-8ff1-b31f13cb525d.png)


[![release](https://img.shields.io/github/release/Thibautg16/NetatmoEnergyAppEedomus.svg?style=for-the-badge)](https://github.com/Thibautg16/NetatmoEnergyAppEedomus/releases)
[![license](https://img.shields.io/github/license/Thibautg16/NetatmoEnergyAppEedomus.svg?style=for-the-badge)](https://github.com/Thibautg16/NetatmoEnergyAppEedomus/blob/master/LICENSE)
[![Status: Beta](https://img.shields.io/badge/Status-Prod-green.svg?style=for-the-badge)](https://github.com/Thibautg16/NetatmoEnergyAppEedomus)
[![@Thibautg16](https://img.shields.io/badge/twitter-@Thibautg16-blue.svg?style=for-the-badge)](https://twitter.com/Thibautg16)