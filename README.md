# playcards

Sort a hand of play cards...

This application can be run in console mode and in a web application

It requires php7.0 minimum

It's a symfony application, requiring guzzle


```
git clone https://github.com/flottin/playcards.git
cd playcards
php composer.phar -vvvvv up
```

#find the ip
```
ifconfig
```

# run embed server: if your ip is 192.168.0.49
```
sudo php bin/console server:start 192.168.0.49:80
```

# browse in navigator to http://192.168.0.49/cards/play

# or in console
```
sudo php bin/console play:cards
```
