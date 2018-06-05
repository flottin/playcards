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
- dryrun

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
php bin/console play:cards --help
php bin/console play:cards --dryrun=true
php bin/console play:cards --iteration=5 --dryrun=true
php bin/console play:cards
php bin/console play:cards --iteration=5
```

### run with embeded server
```console
sudo php bin/console server:start
```
then browse in your navigator to:
- http://127.0.0.1:8000/cards/play/1/true : launch with dryrun
- http://127.0.0.1:8000/cards/play/6/true : launch 6 iterations in dryrun
- http://127.0.0.1:8000/cards/play : launch
- http://127.0.0.1:8000/cards/play/6 : launch 6 iterations
