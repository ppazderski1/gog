GOG rekrutacja
========================

Repozytorium zawierające zadanie rekrutacyjne.
Całość została oparta o Symfony 3.4.

Instalacja:

```
cp docker-compose.yml.dist docker-compose.yml && \
docker-compose up
```

```
make composer-install
```

```
make start-dbs
```

Api domyślnie dostępne na porcie 80 localhost

Dokumentacja i sandbox:

[localhost/doc](http://localhost/doc).


Testy:
```
make start-tests
```

