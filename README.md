# Symfony6-docker

Starter kit pour applications Symfony 6.3

## Specifications:
- PHP 8.2
- Symfony 6.3
- Postgresql 15
- Nginx 1.23.4-alpine
- Node-js 20-alpine

## Utilisation :
- make install : build des images docker, composer install, npm install et build assets
- make start : démarrage des images php, nginx et postgresql
- make stop : arrêt des containers du projet
- make connect / node-connect : shell dans les containers php / nodejs
- make clear : vidage du cache
- make composer-update : mise à jour des vendors php
- make node-install : installation des vendors js
- make node-build : compilation des assets js et scss

- url par défaut en mode dev : http://localhost:8180