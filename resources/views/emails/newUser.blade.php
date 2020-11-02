@component('mail::message')
# {{ $details['title'] }}

Cześć, {{ $details['name'] }}!

Pracownik biblioteki właśnie utworzył Twoje konto!<br>
W celu bezpieczeństwa prosimy o przejście do strony, poprzez wciśnięcie przycisku poniżej.<br>
Zostaniesz poproszony o zmianę hasła.<br>
Proszę pamiętać, aby hasło do konta spełniało następujące wymagania:<br>

@component('mail::panel')
- 8 znaków<br>
- co najmniej jedna duża litera, cyfra oraz znak specjalny<br>
@endcomponent

@component('mail::button', ['url' => $details['url']])
KLIKNIJ TUTAJ
@endcomponent

Życzymy przyjemnego czytania,<br>
{{ config('app.name') }}
@endcomponent