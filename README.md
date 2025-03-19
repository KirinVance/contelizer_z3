# 📝 Symfony Gorest API

## 📌 Zadanie 3 - Symfony Gorest API
Na widoku głównym i jedynym http://localhost:8000 możemy przełączać między dwoma trybami: LOCAL oraz API.
Tryb LOCAL używa GorestLocalController, którego źródłem danych jest repozytorium GorestUserRepository.
Tryb API używa GorestApiController, którego źródłem danych jest serwis GorestApi.
Do tego mamy przycisk "Sync Local With Api", który czyści wpisy lokalne i wstawia do lokalnej bazy 10 wpisów z Goresta.

## 📌 Setup
- ustawić dane bazy w .env
- composer install
- symfony serve
- npm run dev
- php bin/console doctrine:database:create
- php bin/console doctrine:migrations:migrate
- i zapraszam na http://localhost:8000

## 📌 Elementy projektu
- Użycie standardowych elementów Symfony takich jak Entity, Repository, Controller, FormType, Template, Service
- ajax pobiera formularze z backendu z szablonów typu create_form.html.twig
- ściezki create, update i delete poprawnie generują i walidują tokeny CSRF
- wyszukiwanie w obu trybach działa po name, email lub obu filtrach jednocześnie. Wystarczy wpisać część imienia lub emaila ayb wyszukiwanie zadziałało.

## 📌 Uwagi
W trybie API dalej wszystko wykonuje się za pośrednictwem lokalnego backendu, bo skoro task ma być o Symfony, to nie chciałem za dużo robić w samym JS.

## 📌 Podsumowanie
Dziękuję za czas poświęcony na sprawdzenie moich zadań. Bardzo zależy mi na pracy, więc mam nadzieję, że moje zadanka pozwoliły mi się choć trochę wyróżnić wśród reszty aplikantów.

PS. Jeżeli cokolwiek będzie nie działać to proszę się kontaktować :)
