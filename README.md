Alyosha
=======

Alyosha est une interface entre le protocole IRC et ses différents plugins.
Le but est d'abstraire le maximum d'information du protocole pour que les développeurs de plugins n'aient pas à se préoccuper d'actions comme la récupération des utilisateurs connectés à un chan irc.
Il s'agit là d'une des motivations principales de ce projet, outre mon envie de progresser en php.

Le fonctionnement
=================

Les plugins enregistrés dans la configuration sont chargés. Pour chaque plugin, les actions et évènements sont mis en mémoire.
Puis, le bot peut démarrer et se connecter au server IRC. Dès lors, tout message reçu du serveur peut générer un évènement qui peut déclencher une ou plusieurs actions. Ces actions peuvent générer des messages qui seront alors envoyés au serveur IRC.
Ces messages peuvent être des commandes irc ou des messages à des chans ou utilisateurs.

Les plugins
===========
Alyosha fonctionne entièrement avec des plugins dans le sens où même les fonctionnalités principales sont dans un plugin: le CorePlugin.
Chaque plugin dispose de deux dossiers: Action et Event. Event contient les classes qui sont chargés en mémoire et qui permettent de reconnaitre des évènements IRC.

Les évènements
==============

Les actions
===========

Le container
============
