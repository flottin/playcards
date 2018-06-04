# playcards

Sort a hand of play cards given by a webservice... This application can be run in console mode and in a web application
It requires php 7.1.3 minimum.
It's a symfony application, requiring guzzle

It shows the use of  :
- last version of symfony
- a console application
- a web based application
- a service processing the hand of cards (called by console and web page)
- guzzle with asynchronous multicall of webservice (with the key word yield) and the promise concept
- unit test of the service hand (sort method)
- logging error

## Improvments
- move constant from Hand service towards an interface class for display or making an helper
- add functionnals tests using goutte for exemple : https://github.com/FriendsOfPHP/Goutte

## installation

```console
# should display a version >= 7.1.3
php -v
git clone https://github.com/flottin/playcards.git
cd playcards
php composer.phar -vvvvv up
```

## testing

### unit tests
```console
 ./bin/phpunit --testdox
 ```

## runing

### in console
```console
php bin/console play:cards
php bin/console play:cards --iteration=5
```

### web
find your ip
```console
ifconfig
```

### run with embeded server: if your ip is 192.168.0.49
```console
sudo php bin/console server:start 192.168.0.49:80
```
then browse in your navigator to http://192.168.0.49/cards/play
or http://192.168.0.49//cards/play/6 if you want 6 iterations
