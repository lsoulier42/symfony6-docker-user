# Starter-kit symfony with user

Starter kit pour applications Symfony 6.4 avec entité utilisateur

## Specifications:
- PHP 8.3
- Symfony 6.4
- Postgresql 16

## Utilisation :
- make install : build des images docker, composer install, build assets
- make start : démarrage des images php, nginx et postgresql
- make stop : arrêt des containers du projet
- make connect / node-connect : shell dans les containers php / nodejs
- make clear : vidage du cache
- make composer-update : mise à jour des vendors php

- url par défaut en mode dev : http://localhost:8222